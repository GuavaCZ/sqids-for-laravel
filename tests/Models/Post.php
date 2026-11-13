<?php

namespace Guava\Sqids\Tests\Models;

use Guava\Sqids\Concerns\HasSqids;
use Guava\Sqids\Concerns\HasSqidsRouting;
use Guava\Sqids\Sqids;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasSqids;
    use HasSqidsRouting;

    public $timestamps = false;

    protected $guarded = [];

    protected function getSqids(): Sqids
    {
        return Sqids::make()->salt();
    }
}
