<?php

namespace Guava\Sqids\Concerns;

trait WithBlocklist
{
    public function blocklist(array $blocklist): static
    {
        return $this->configure($this->configuredAlphabet, $this->minLength, $blocklist);
    }

    public function getBlocklist(): array
    {
        return $this->blocklist;
    }
}
