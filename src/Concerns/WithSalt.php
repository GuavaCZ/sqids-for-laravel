<?php

namespace Guava\Sqids\Concerns;

trait WithSalt
{
    protected ?int $salt = null;

    public function salt(string | int $salt = null): static
    {
        $this->salt = crc32($salt ?? static::class);

        return $this;
    }

    public function getSalt(): ?int
    {
        return $this->salt;
    }
}
