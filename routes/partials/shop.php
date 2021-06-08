<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use \App\Models\ShippingInfo;
use \App\AlertHelper;

Route::get('/search', function() {
    $criteria = request('criteria');

    $products = \App\Models\Product::query()
   ->where('title', 'LIKE', "%{$criteria}%") 
   ->orWhere('description', 'LIKE', "%{$criteria}%") 
   ->get();

   return view('shop')->with('hide_sold', false)->with('products', $products)->with('criteria', 0);
});

Route::get('/create-listing', function() {
    return view('create_listing');
})->name('create_listing');

Route::post('/create-product', [App\Http\Controllers\ProductController::class, 'createProduct'])->name('create-product');

Route::get('/closet', function () {
    if(!\App\Models\Closet::where('id', \request('id'))->exists()) abort(404);

    $closet = \App\Models\Closet::where('id', \request('id'))->first();

    if(request('tab') == 'loves') {
        return view('closet')->with('closet', $closet)->with('loves', \App\Models\User::where('id', $closet->user_id)->first()->getLovedProducts());
    } else {
        return view('closet')->with('closet', $closet);
    }
});

Route::get('/shop', [App\Http\Controllers\ShopController::class, 'index'])->name('shop');

Route::get('/get-loves', [App\Http\Controllers\ClosetController::class, 'getLoves'])->name('get-loves');

Route::get('/get-closet', [App\Http\Controllers\ClosetController::class, 'getCloset'])->name('get-closet');

Route::get('/item', function() {
    if(! \App\Models\Product::where('id', \request('id'))->exists()) {
        abort(404);
    }

    $product = \App\Models\Product::where('id', \request('id'))->first();
    return view('item')->with('product', $product);
})->name('item');

Route::get('/categories', function() {
    if(!empty(\request('id'))) {
        if(\App\Models\Category::where('id', \request('id'))->exists()) {
            return view('categories')->with('categories', \App\Models\Category::where('parent_category', \request('id'))->get())->with('parent_category', \request('id'));
        }
    }
    return view('categories')->with('categories', \App\Models\Category::where('parent_category', null)->get())->with('parent_category', '');
});

Route::post('/create-category', [App\Models\Category::class, 'createCategory'])->name('create-category');

Route::post('/delete-category', [App\Models\Category::class, 'deleteCategory'])->name('delete-category');

Route::post('/create-size', [App\Models\Size::class, 'createSize'])->name('create-size');

Route::post('/delete-size', [App\Models\Size::class, 'deleteSize'])->name('delete-size');