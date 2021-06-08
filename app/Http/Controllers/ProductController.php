<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Closet;
use Illuminate\Support\Facades\Cache;
use App\AlertHelper;

class ProductController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function createProduct(Request $request)
    {
        // too many images, somehow reload create_listing page with this error message.
        if(sizeof($request['images']) > 8) {
            abort(404);
        }

        $images_urls = [];
        $images_ids = [];

        if($request->hasFile('images')) {
            $files = $request->file('images');

            foreach($files as $file){
                $i = FileController::store($file, 'images/user/' . auth()->user()->id . '/posts/');
                array_push($images_urls, $i->getURL());
                array_push($images_ids, $i->id);
            }
        }

        $product = new Product();
        $product->closet_id = Closet::where('user_id', auth()->user()->id)->first()->id;

        $original_price = intval(str_replace('.', '', doubleval($request['price-original']) * 100));
        $purchase_price = intval(str_replace('.', '', $request['price-purchase']));
        $rental_price = intval(str_replace('.', '', $request['price-rent']));

        if($purchase_price > 0) {
            if($purchase_price < env('MINIMUM_PURCHASE_PRICE', 3)) {
                AlertHelper::alertError('Purchase price cannot be less than $' . env('MINIMUM_PURCHASE_PRICE', 3) . '.');
                return back();
            }

            if($purchase_price * 100 > $original_price) {
                AlertHelper::alertError('Original price cannot be less than the purchase price.');
                return back();
            }

            $product->purchase_price = $purchase_price * 100;
        } 

        if($rental_price > 0) {
            if($rental_price < env('MINIMUM_RENTAL_PRICE', 3)) {
                AlertHelper::alertError('Rental price cannot be less than $' . env('MINIMUM_RENTAL_PRICE', 3) . '.');
                return back();
            }

            if($rental_price * 100 > $original_price) {
                AlertHelper::alertError('Original price cannot be less than the rental price.');
                return back();
            }

            $full_outfit = strpos(\App\Models\Category::where("id", $request['category'])->first()->name, 'Full Outfit') !== false;

            if (! $full_outfit) {
                AlertHelper::alertError('Only full outfits may be posted for rent.');
                return back();
            }

            $product->rental_price = $rental_price * 100;
            $product->rentable = true;
        } 


        // Validate at top
        $product->user_id = auth()->user()->id;
        $product->category = $request['category'];
        $product->subcategory = $request['subcategory'];
        $product->quantity = $request['quantity'];
        $product->size = $request['size'];
        $product->title = $request['title'];
        $product->description = $request['description'];
        $product->original_price = $original_price;
        $product->images = $images_ids;
        $product->weight = $request['weight'];
        $product->brand_designer = $request['brand'];
        $product->new_with_tags = $request['new_with_tags'];

        if($request['refund_days'] > 0) {
            if($request['refund_days'] > 30) {
                $product->refund_period = 30;
            } else {
                $product->refund_period = $request['refund_days'];
            }
        }

        $product->save();

        return redirect('/item?id=' . $product->id);
    }
}
