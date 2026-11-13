<?php

namespace Guava\Sqids\Tests\Models;

use Guava\Sqids\Concerns\HasStoredSqids;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasStoredSqids;

    public $timestamps = false;

    protected $guarded = [];
}
