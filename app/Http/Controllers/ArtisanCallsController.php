<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

/**
 * https://laravel.com/docs/9.x/artisan#programmatically-executing-commands
 */
class ArtisanCallsController extends Controller
{
    public function runMigrateFreshSeed()
    {
        Artisan::call('migrate:fresh --seed');
    }
}
