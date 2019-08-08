<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class BugEstate extends Pivot
{
    protected $table = 'bug_estate';
    protected $casts = [
        'meta' => 'array'
    ];
}
