<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use App\Models\ProductVariantPrice;
use App\Models\Variant;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function index()
    {
        $products = DB::table('products as p')->select('p.id','p.title','p.sku','p.description','p.created_at','pvp.price','pvp.stock', 'pv.variant')
            ->join('product_variants as pv', 'pv.product_id', 'p.id')
            ->join('product_variant_prices as pvp', 'pvp.product_id', 'p.id')
            ->get();
//        dd($products);
        return view('products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function create()
    {
        $variants = Variant::all();
        return view('products.create', compact('variants'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $data = $request->all();

        return  $data;
//            'title' => 'nullable', 'string',
//            'sku' => ['nullable', 'string'],
//            'description' => ['nullable', 'string'],
//            'product_image' => ['nullable', 'image'],
//            'product_variant_prices' => ['nullable', 'string'],
//            'product_variant' => ['nullable', 'string']
//        ]);

        $product = new Product();
        $product->title = $data['title'];
        $product->sku = $data['sku'];
        $product->description = $data['description'];
        $product->save();



        if ($request->product_image){
         $path = $request->product_image->store('Product');
            DB::table('product_images')->insert([
                'file_path' => $path,
                'product_id' =>$product->id,
            ]);
        }

    }


    /**
     * Display the specified resource.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function show($product)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        $variants = Variant::all();
        return view('products.edit', compact('variants'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        //
    }
}
