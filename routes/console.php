<?php

use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment('Stay pragmatic, ship value.');
})->purpose('Display an inspiring quote');
