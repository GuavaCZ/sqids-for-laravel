<?php

namespace Guava\Sqids\Tests\Models;

use Guava\Sqids\Concerns\HasStoredSqids;
use Guava\Sqids\Sqids;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasStoredSqids;

    public $timestamps = false;

    protected $guarded = [];

    public function getSqidColumn(): string
    {
        return 'code';
    }

    public function normalizeSqid(string $sqid): string
    {
        return mb_strtoupper(trim($sqid));
    }

    protected function getSqids(): Sqids
    {
        return Sqids::make(['alphabet' => 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789', 'minLength' => 5])->salt('ticket');
    }
}
