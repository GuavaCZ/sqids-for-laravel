<?php

use Guava\Sqids\Sqids;

it('keeps salted ids identical to previous releases', function () {
    expect(Sqids::make()->salt('my-salt')->encode([1, 2, 3]))->toBe('rx035W')
        ->and(Sqids::make()->salt('my-salt')->encode([42]))->toBe('3V')
        ->and(Sqids::make()->alphabet('abcdef')->salt('my-salt')->encode([10]))->toBe('eaf')
        ->and(Sqids::make()->salt()->encode([1]))->toBe('2A')
    ;
});

it('returns the same id when a salted instance encodes repeatedly', function () {
    $sqids = Sqids::make()->salt('my-salt');

    expect($sqids->encode([1, 2, 3]))->toBe('rx035W')
        ->and($sqids->encode([1, 2, 3]))->toBe('rx035W')
        ->and($sqids->encode([1, 2, 3]))->toBe('rx035W')
    ;
});

it('decodes its own ids when a salted instance is reused', function () {
    $sqids = Sqids::make()->salt('my-salt');

    $id = $sqids->encode([42]);

    expect($sqids->decode($id))->toBe([42])
        ->and($sqids->decode($id))->toBe([42])
        ->and($sqids->encode([42]))->toBe($id)
    ;
});

it('does not change the alphabet of a salted instance', function () {
    $sqids = Sqids::make()->salt('my-salt');
    $alphabet = $sqids->getAlphabet();

    $sqids->encode([1]);
    $sqids->decode('3V');

    expect($sqids->getAlphabet())->toBe($alphabet);
});

it('restores the alphabet when encoding throws', function () {
    $sqids = Sqids::make()->salt('my-salt');

    expect(fn () => $sqids->encode([-1]))->toThrow(InvalidArgumentException::class)
        ->and($sqids->encode([42]))->toBe('3V');
});
