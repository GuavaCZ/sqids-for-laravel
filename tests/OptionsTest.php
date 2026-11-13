<?php

use Guava\Sqids\Sqids;

it('encodes with a fluently set alphabet like an instance constructed with it', function () {
    expect(Sqids::make()->alphabet('0123456789abcdef')->encode([1, 2, 3]))
        ->toBe(Sqids::make(['alphabet' => '0123456789abcdef'])->encode([1, 2, 3]))
        ->toBe('489158')
    ;
});

it('matches the ids of other Sqids implementations for a custom alphabet and minimum length', function () {
    $sqids = Sqids::make()->alphabet('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789')->minLength(5);

    expect($sqids->encode(22))->toBe('KYAC9')
        ->and($sqids->encode(98298))->toBe('LHVQM')
        ->and($sqids->decode('KYAC9'))->toBe([22])
    ;
});

it('pads ids to a fluently set minimum length', function () {
    expect(Sqids::make()->minLength(8)->encode([1, 2, 3]))->toBe('86Rf07xd');
});

it('avoids the words of a fluently set blocklist', function () {
    expect(Sqids::make()->encode([1, 2, 3]))->toBe('86Rf07')
        ->and(Sqids::make()->blocklist(['86Rf07'])->encode([1, 2, 3]))->toBe('se8ojk')
    ;
});

it('keeps earlier options when another one is set', function () {
    $sqids = Sqids::make()->blocklist(['86Rf07'])->minLength(8)->alphabet('0123456789abcdef');

    expect($sqids->getAlphabet())->toBe('0123456789abcdef')
        ->and($sqids->getMinLength())->toBe(8)
        ->and($sqids->getBlocklist())->toBe(['86Rf07'])
    ;
});

it('rejects an invalid option set fluently', function (Closure $configure) {
    expect(fn () => $configure(Sqids::make()))->toThrow(InvalidArgumentException::class);
})->with([
    'an alphabet with repeated characters' => [fn (Sqids $sqids) => $sqids->alphabet('aabc')],
    'an alphabet that is too short' => [fn (Sqids $sqids) => $sqids->alphabet('ab')],
    'a negative minimum length' => [fn (Sqids $sqids) => $sqids->minLength(-1)],
]);
