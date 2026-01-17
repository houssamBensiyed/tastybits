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


/*
|--------------------------------------------------------------------------
| Part 3: Order Routes
|--------------------------------------------------------------------------
*/

// View Cart
Route::get('/cart', function () {
    return 'Your shopping cart';
})->name('cart.index');

// Add item to cart
Route::post('/cart/{itemId}', function (string $itemId) {
    return "Adding item {$itemId} to cart";
})->name('cart.add');

// Update cart item quantity
Route::patch('/cart/{cartItemId}', function (string $cartItemId) {
    return "Updating quantity for cart Item {$cartItemId}";
})->name('cart.update');

// Remove item from cart
Route::delete('/cart/{cartItemId}', function (string $cartItemId) {
    return "Removing cart item {$cartItemId}";
})->name('cart.remove');

// Checkout page
Route::get('/checkout', function () {
    return 'Checkout page';
})->name('checkout.index');

// Place order
Route::post('/checkout', function () {
    return 'Proccessing you order...';
})->name('checkout.place');

// Order confirmation
Route::get('/order/{orderNumber}', function (string $orderNumber) {
    return "Order confirmed! Your order number is : {$orderNumber}";
})->name('order.confirmation')->whereNumber('orderNumber');

/*
|--------------------------------------------------------------------------
| Part 4: Kitchen Panel Routes (Route Group)
|--------------------------------------------------------------------------
*/

Route::prefix('kitchen')->name('kitchen.')->group(function () {

    // Dashboard - view all incoming orders
    Route::get('dashboard', function () {
        return 'Kitchen Dashboard';
    })->name('dashboard');

    // View Single Order
    Route::get('/orders/{orderId}', function (string $orderId) {
        return "Viewing order details fro order #{$orderId}";
    })->name('order.show');

    // Update Order Status
    Route::patch('/orders/{orderId}', function (string $orderId) {
        return "Updating status for order #{$orderId}";
    })->name('order.update');

    // Order history with optional date parameter
    Route::get('/history/{date?}', function (?string $date = null) {
        if ($date) {
            return "Order history for date: {$date}";
        }
        return "Complete order history";
    })->name('history');
});

/*
|--------------------------------------------------------------------------
| Part 5: API Routes Group
|--------------------------------------------------------------------------
*/

Route::prefix('api')->name('api.')->group(function () {
    
    // API Version 1
    Route::prefix('v1')->name('v1.')->group(function () {
        
        // Get all menu items
        Route::get('/menu', function () {
            return response()->json([
                'success' => true,
                'data' => [
                    ['id' => 1, 'name' => 'Margherita Pizza', 'price' => 12.99],
                    ['id' => 2, 'name' => 'Cheeseburger', 'price' => 8.99],
                    ['id' => 3, 'name' => 'Cola', 'price' => 2.49],
                ]
            ]);
        })->name('menu.index');
        
        // Get single menu item
        Route::get('/menu/{itemId}', function (string $itemId) {
            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $itemId,
                    'name' => 'Margherita Pizza',
                    'price' => 12.99,
                    'description' => 'Classic Italian pizza'
                ]
            ]);
        })->name('menu.show');
        
        // Get restaurant status
        Route::get('/status', function () {
            $hour = date('H');
            $isOpen = ($hour >= 10 && $hour < 22);
            
            return response()->json([
                'success' => true,
                'data' => [
                    'is_open' => $isOpen,
                    'message' => $isOpen ? 'We are open!' : 'Sorry, we are closed',
                    'hours' => '10:00 AM - 10:00 PM'
                ]
            ]);
        })->name('status');
        
    });
    
});

/*
|--------------------------------------------------------------------------
| Bonus: Multiple HTTP Methods Route
|--------------------------------------------------------------------------
*/

Route::match(['get', 'post'], '/reservation', function () {
    if (request()->isMethod('post') {
        return 'Processing your reservation...';
    })
    return 'Reservation form';
})->name('reservation');


/*
|--------------------------------------------------------------------------
| Part 6: Fallback Route
|--------------------------------------------------------------------------
*/

Route::fallback(function () {
    return response()->json([
        'error' => true,
        'message' => 'Route not found'
    ], 404);
});