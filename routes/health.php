<?php

use Illuminate\Support\Facades\Route;

Route::get('/', fn () => ['status' => 'ok'])->name('health');
