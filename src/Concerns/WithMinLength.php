<?php

namespace Guava\Sqids\Concerns;

trait WithMinLength
{
    public function minLength(int $minLength): static
    {
        return $this->configure($this->configuredAlphabet, $minLength, $this->blocklist);
    }

    public function getMinLength(): int
    {
        return $this->minLength;
    }
}
