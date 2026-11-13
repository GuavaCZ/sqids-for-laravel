<?php

namespace Guava\Sqids\Concerns;

trait WithAlphabet
{
    public function alphabet(string $alphabet): static
    {
        return $this->configure($alphabet, $this->minLength, $this->blocklist);
    }

    /**
     * The alphabet as configured, before Sqids shuffles it.
     */
    public function getAlphabet(): string
    {
        return $this->configuredAlphabet;
    }
}
