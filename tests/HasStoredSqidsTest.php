<?php

use Guava\Sqids\Sqids;
use Guava\Sqids\Tests\Models\Invoice;
use Guava\Sqids\Tests\Models\Ticket;

it('stores the sqid when the model is created', function () {
    $invoice = Invoice::create();

    expect($invoice->sqid)->toBe(Sqids::make()->encode($invoice->id))
        ->and(Invoice::query()->where('sqid', $invoice->sqid)->sole()->is($invoice))->toBeTrue()
    ;
});

it('stores the sqid in the model\'s own column with its own options and salt', function () {
    $ticket = Ticket::create();

    expect($ticket->code)
        ->toBe(Sqids::make(['alphabet' => 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789', 'minLength' => 5])->salt('ticket')->encode($ticket->id))
        ->toHaveLength(5)
        ->and($ticket->getAttributes())->not->toHaveKey('sqid')
    ;
});

it('uses the sqid as the route key', function () {
    $ticket = Ticket::create();

    expect($ticket->getRouteKey())->toBe($ticket->code);
});

it('finds a model by its stored sqid in a route binding', function () {
    $ticket = Ticket::create();

    expect((new Ticket)->resolveRouteBinding($ticket->code)?->is($ticket))->toBeTrue();
});

it('normalizes the sqid of a route binding before looking it up', function () {
    $ticket = Ticket::create();

    expect((new Ticket)->resolveRouteBinding(' '.mb_strtolower($ticket->code).' ')?->is($ticket))->toBeTrue();
});

it('does not find a model by a shortened sqid', function () {
    $ticket = Ticket::create();

    expect((new Ticket)->resolveRouteBinding(mb_substr($ticket->code, 0, 4)))->toBeNull();
});

it('does not find a model by its numeric id', function () {
    $ticket = Ticket::create();

    expect((new Ticket)->resolveRouteBinding($ticket->id))->toBeNull();
});

it('still resolves a route binding by another column', function () {
    $ticket = Ticket::create(['summary' => 'Broken monitor']);

    expect((new Ticket)->resolveRouteBinding('Broken monitor', 'summary')?->is($ticket))->toBeTrue();
});

it('does not fire model events when it stores the sqid', function () {
    $updates = 0;
    Ticket::updated(function () use (&$updates) {
        $updates++;
    });

    Ticket::create();

    expect($updates)->toBe(0);
});

it('backfills the sqid of rows inserted without model events', function () {
    Ticket::query()->insert([['summary' => 'First'], ['summary' => 'Second']]);
    $existing = Ticket::create();

    $filled = Ticket::backfillSqids();

    expect($filled)->toBe(2)
        ->and(Ticket::query()->whereNull('code')->count())->toBe(0)
        ->and(Ticket::query()->pluck('code')->unique())->toHaveCount(3)
        ->and($existing->fresh()->code)->toBe($existing->code)
    ;
});
