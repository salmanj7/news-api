<?php

use App\Console\Commands\FetchArticlesCommand;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Artisan::command('articles:fetch', function () {
    Artisan::call('articles:fetch'); // Calls your registered FetchArticlesCommand
})->purpose('Fetch articles from news APIs')->everyMinute();
