<?php

use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Part 1: Basic Public Routes
|--------------------------------------------------------------------------
*/

// View Route for homepage
Route::view('/', 'home')->name('home');

// View route with Data passed to the view
Route::view('/contact', 'contact', ['phone' => '555-1234'])->name('contact');

// Permanent Redirect 301
Route::redirect('/food-menu', '/menu', 301);

// Temporary Redirect 302
Route::redirect('/promo', '/menu/specials', 302);
