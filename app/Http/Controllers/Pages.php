<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Slider;
use Illuminate\Http\Request;

class Pages extends Controller
{
    public function home()
    {
        $sliders = Slider::where('is_active', true)->get();
        return view('store.home', compact('sliders'));
    }

    public function about()
    {
        return view('store.about');
    }

    public function contact()
    {
        return view('store.contact');
    }

    public function product($slug = null)
    {
        if (is_null($slug)) {
            $products = Product::all();
            return view('store.products', compact('products'));
        }
        $product = Product::where('slug', $slug)->first();
        if (!$product) {
            abort(404);
        }
        return view('store.product', compact('slug', 'product'));
    }


}
