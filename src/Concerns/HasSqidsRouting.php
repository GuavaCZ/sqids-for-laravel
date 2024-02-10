<?php

namespace Guava\Sqids\Concerns;

use Illuminate\Support\Str;

trait HasSqidsRouting
{
    public function resolveRouteBindingQuery($query, $value, $field = null)
    {
        $field ??= 'sqid';

        return Str::afterLast($field, '.') === 'sqid'
            ? $query->where($this->getKeyName(), $this->getSqids()->decode($value))
            : parent::resolveRouteBindingQuery($query, $value, $field);
    }

    /**
     * @see parent
     */
    public function getRouteKey()
    {
        return $this->sqid;
    }
}
