<?php

namespace Guava\Sqids\Tests\Models;

use Guava\Sqids\Concerns\HasSqids;
use Guava\Sqids\Sqids;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasSqids;

    public $timestamps = false;

    protected $guarded = [];

    protected function getSqids(): Sqids
    {
        return Sqids::make()->salt();
    }
}
