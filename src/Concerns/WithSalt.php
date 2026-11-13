<?php

namespace Guava\Sqids\Concerns;

use Illuminate\Database\Eloquent\Model;

trait WithSalt
{
    protected bool $salted = false;

    protected string | int | null $salt = null;

    protected ?Model $model = null;

    /**
     * Salts the ids, so the same number gives a different id under each salt. Without a
     * value the salt is the morph class of the model the ids belong to (the instance's own
     * class name when it is used on its own). A salt must never change once ids are stored
     * or handed out: pass a fixed one when the model may be renamed and has no morph alias.
     */
    public function salt(string | int | null $salt = null): static
    {
        $this->salted = true;
        $this->salt = $salt;

        return $this;
    }

    public function getSalt(): ?int
    {
        $salt = $this->resolveSalt();

        return $salt === null ? null : crc32($salt);
    }

    protected function resolveSalt(): ?string
    {
        if (! $this->salted) {
            return null;
        }

        return (string) ($this->salt ?? $this->model?->getMorphClass() ?? static::class);
    }
}
