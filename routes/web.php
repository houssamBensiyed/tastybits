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

/*
|--------------------------------------------------------------------------
| Part 2: Menu Routes with Parameters
|--------------------------------------------------------------------------
*/

// Show full menu
Route::get('/menu', function () {
    return 'Displaying full menu';
})->name('menu');

// Search menu (optional parameter)
Route::get('/menu/search/{query?}', function (?string $query = null) {
    if ($query) {
        return "Searching menu for : {$query}";
    }
    return 'Search page - Enter your search term';
})->name('menu.search');

// Show specials page (for the promo redirect)
Route::get('/menu/specials', function () {
    return 'Today\'s special offers';
})->name('menu.specials');

// Show menu by category (required parameter)
Route::get('/menu/{category}', function (string $category) {
    return "Displaying menu category: {$category}";
})->name('menu.category');

// Show single item (two required parameters)
Route::get('/menu/{category}/{item}', function (string $category, string $item) {
    return "Displaying {$item} from {$category} category";
})->name('menu.item');