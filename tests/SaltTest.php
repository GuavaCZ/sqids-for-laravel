<?php

use Guava\Sqids\Sqids;

it('gives the same ids for a salt on every run', function () {
    expect(Sqids::make()->salt('my-salt')->encode([1, 2, 3]))->toBe('MNtmFG')
        ->and(Sqids::make()->salt('my-salt')->encode([42]))->toBe('e7')
        ->and(Sqids::make()->alphabet('abcdef')->salt('my-salt')->encode([10]))->toBe('fcd')
        ->and(Sqids::make()->salt()->encode([1]))->toBe('rQ')
    ;
});

it('gives different ids under different salts', function () {
    expect(Sqids::make()->salt('first')->encode([1, 2, 3]))
        ->not->toBe(Sqids::make()->salt('second')->encode([1, 2, 3]))
        ->not->toBe(Sqids::make()->encode([1, 2, 3]))
    ;
});

it('uses its own class name as the salt when none is given outside a model', function () {
    expect(Sqids::make()->salt()->encode([1, 2, 3]))
        ->toBe(Sqids::make()->salt(Sqids::class)->encode([1, 2, 3]))
    ;
});

it('returns the same id when a salted instance encodes repeatedly', function () {
    $sqids = Sqids::make()->salt('my-salt');

    expect($sqids->encode([1, 2, 3]))->toBe('MNtmFG')
        ->and($sqids->encode([1, 2, 3]))->toBe('MNtmFG')
        ->and($sqids->encode([1, 2, 3]))->toBe('MNtmFG')
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
    $sqids->decode('e7');

    expect($sqids->getAlphabet())->toBe($alphabet);
});

it('restores the alphabet when encoding throws', function () {
    $sqids = Sqids::make()->salt('my-salt');

    expect(fn () => $sqids->encode([-1]))->toThrow(InvalidArgumentException::class)
        ->and($sqids->encode([42]))->toBe('e7')
    ;
});

it('leaves the random number generator alone', function () {
    mt_srand(5);
    $expected = mt_rand();

    mt_srand(5);
    Sqids::make()->salt('my-salt')->encode([1, 2, 3]);

    expect(mt_rand())->toBe($expected);
});
