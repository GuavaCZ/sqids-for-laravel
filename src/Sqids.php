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

        if ($salt = $this->getSalt()) {
            srand($salt);
            $this->alphabet = str_shuffle($this->getAlphabet());
        }

        return parent::encode($numbers);
    }

    public function decode(string $id): array
    {
        if ($salt = $this->getSalt()) {
            srand($salt);
            $this->alphabet = str_shuffle($this->getAlphabet());
        }

        return parent::decode($id);
    }

    public static function make(array $parameters = []): static
    {
        return app(static::class, $parameters);
    }
}
