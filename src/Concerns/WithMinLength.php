<?php

namespace Guava\Sqids\Concerns;

use Sqids\Sqids;

trait WithMinLength
{
    public function minLength(int $minLength): static
    {
        $this->minLength = $minLength;

        return $this;
    }

    public function getMinLength(): int
    {
        return $this->minLength ?? Sqids::DEFAULT_MIN_LENGTH;
    }
}
