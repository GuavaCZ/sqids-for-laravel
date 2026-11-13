<?php

namespace Guava\Sqids\Concerns;

use Guava\Sqids\Sqids;
use Illuminate\Database\Eloquent\Casts\Attribute;

trait HasSqids
{
    public function sqid(): Attribute
    {
        return Attribute::make(
            get: function () {
                return $this->getSqids()
                    ->forModel($this)
                    ->encode($this->getKey())
                ;
            },
        );
    }

    protected function getSqids(): Sqids
    {
        return Sqids::make();
    }
}
