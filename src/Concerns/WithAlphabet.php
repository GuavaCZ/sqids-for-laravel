<?php

namespace Guava\Sqids\Concerns;

use Sqids\Sqids;

trait WithAlphabet
{
    public function alphabet(string $alphabet): static
    {
        $this->alphabet = $alphabet;

        return $this;
    }

    public function getAlphabet(): string
    {
        return $this->alphabet ?? Sqids::DEFAULT_ALPHABET;
    }
}
