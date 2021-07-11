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
//        $data = $request->all();

     $data = $request->validate([
            'title' => 'nullable', 'string',
            'sku' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
            'product_image' => ['nullable'],
            'product_variant_prices' => ['nullable',],
            'product_variant' => ['nullable',]
        ]);

        $product = new Product();
        $product->title = $data['title'];
        $product->sku = $data['sku'];
        $product->description = $data['description'];
        $product->save();
        foreach($data['product_variant'] as $variants){
            $ProductVariants = new ProductVariant();
            $ProductVariants->variant_id = $variants['option'];
            foreach ($variants['tags'] as $key=>$tag){
                $ProductVariants->variant = $tag;
                $ProductVariants->product_id = $product->id;
                $ProductVariants->save();
            }
        }
        $ProductVariantPrice = new ProductVariantPrice();
        foreach($data['product_variant_prices'] as $p_variant_price){
            $ProductVariantPrice->product_variant_one = 1;
            $ProductVariantPrice->product_variant_two = 2;
            $ProductVariantPrice->product_variant_three = 3;
            $ProductVariantPrice->price = $p_variant_price['price'];
            $ProductVariantPrice->stock = $p_variant_price['stock'];
            $ProductVariantPrice->product_id = $product->id;
            $ProductVariantPrice->save();
        }



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
