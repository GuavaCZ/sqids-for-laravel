<?php

namespace Guava\Sqids;

use Illuminate\Support\Arr;

class Sqids extends \Sqids\Sqids
{
    use Concerns\WithAlphabet;
    use Concerns\WithBlocklist;
    use Concerns\WithMinLength;
    use Concerns\WithSalt;

    public function encode(array | int $numbers): string
    {
        $numbers = Arr::wrap($numbers);

        return $this->withSaltedAlphabet(fn () => parent::encode($numbers));
    }

    public function decode(string $id): array
    {
        return $this->withSaltedAlphabet(fn () => parent::decode($id));
    }

    /**
     * Runs the callback with the salted alphabet and restores the original one afterwards,
     * so the instance stays unchanged and repeated calls return the same result.
     */
    protected function withSaltedAlphabet(callable $callback): mixed
    {
        if (! $salt = $this->getSalt()) {
            return $callback();
        }

        $alphabet = $this->alphabet;

        srand($salt);
        $this->alphabet = str_shuffle($this->getAlphabet());

        try {
            return $callback();
        } finally {
            $this->alphabet = $alphabet;
        }
    }

    public static function make(array $parameters = []): static
    {
        return app(static::class, $parameters);
    }
}
