<?php

namespace Guava\Sqids\Concerns;

use Guava\Sqids\Sqids;
use Illuminate\Support\Str;

/**
 * Keeps the model's sqid in a column of its own, so it can be searched (by prefix too),
 * joined and read by anything that reads the database. The id is known only after the
 * insert, so the column is filled right after the model is created: make it nullable,
 * with a unique index.
 *
 * Use it in place of HasSqids and HasSqidsRouting, which compute the sqid on every read.
 */
trait HasStoredSqids
{
    public static function bootHasStoredSqids(): void
    {
        static::created(fn (self $model) => $model->storeSqid());
    }

    /**
     * Fills the sqid of every row that has none: rows inserted without model events
     * (bulk inserts, seeders using WithoutModelEvents) or before the column existed.
     */
    public static function backfillSqids(): int
    {
        $filled = 0;

        static::query()
            ->whereNull((new static)->getSqidColumn())
            ->lazyById()
            ->each(function (self $model) use (&$filled): void {
                $model->storeSqid();
                $filled++;
            })
        ;

        return $filled;
    }

    public function getSqidColumn(): string
    {
        return 'sqid';
    }

    public function generateSqid(): string
    {
        return $this->getSqids()
            ->forModel($this)
            ->encode($this->getKey())
        ;
    }

    public function storeSqid(): void
    {
        $this->forceFill([$this->getSqidColumn() => $this->generateSqid()])->saveQuietly();
    }

    public function getRouteKeyName()
    {
        return $this->getSqidColumn();
    }

    public function resolveRouteBindingQuery($query, $value, $field = null)
    {
        $field ??= $this->getRouteKeyName();

        return Str::afterLast($field, '.') === $this->getSqidColumn()
            ? $query->where($field, $this->normalizeSqid((string) $value))
            : parent::resolveRouteBindingQuery($query, $value, $field);
    }

    /**
     * Brings a sqid someone typed or followed in a link to the form it is stored in,
     * e.g. uppercase when the alphabet has no lowercase letters.
     */
    public function normalizeSqid(string $sqid): string
    {
        return $sqid;
    }

    protected function getSqids(): Sqids
    {
        return Sqids::make();
    }
}
