<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

abstract class Service
{
    protected $db = DB::class;
}
