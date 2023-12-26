<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductImage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::when($request->search, function ($query, $search) {
            $query->where('name', 'like', '%' . $search . '%')
                ->orWhere('description', 'like', '%' . $search . '%');
        })
        ->when($request->category, function ($query, $category) {
            $query->where('category', $category);
        })
        ->paginate(5)
        ->withQueryString();
    
        return Inertia::render('Product/Index', [
            'products' => $products,
            'filters' => $request->only('search', 'category'),
        ]);
    
    }

    public function create()
    {
        return Inertia::render(
            'Product/Create'
        );
    }

    public function store(Request $request)
    {


        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string',
            'description' => 'required',
            'date_and_time' => 'required',
            'files.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048', 
        ],[
            'files.*.image' => 'file must be an image.',
            'files.*.mimes' => 'file must be a valid image format (jpeg, png, jpg, gif, svg).',
            'files.*.max' => 'file must not exceed 2048 kilobytes.',
        ]);

        $product = Product::create([
            'name' => $request->name,
            'category' => $request->category,
            'description' => $request->description,
            'date_and_time' => date('Y-m-d H:i:s', strtotime($request->date_and_time)),
        ]);

        $lastInsertedProductId = $product->id;
    
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $path = $file->storeAs('public/product_images/'.$lastInsertedProductId, $file->getClientOriginalName());
    
                $url = asset('storage/product_images/'.$lastInsertedProductId.'/' . $file->getClientOriginalName());

                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $url,
                    'name' => $file->getClientOriginalName(),
                ]);
            }
        }
    
        return redirect()->route('products.index')->with('message', 'Product Created Successfully');
    
    
    }


    public function show(Product $product)
    {
        //
    }


    public function edit(Product $product)
    {
        $productImages = ProductImage::where('product_id', $product->id)->get();
        return Inertia::render(
            'Product/Edit',
            [
                'product' => $product,
                'productImages' => $productImages
            ]
        );
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string',
            'description' => 'required',
            'date_and_time' => 'required',
            'files.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);


        $product->update([
            'name' => $request->name,
            'category' => $request->category,
            'description' => $request->description,
            'date_and_time' => date('Y-m-d H:i:s', strtotime($request->date_and_time)),
        ]);

        ProductImage::where('product_id', $product->id)->delete();

        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $path = $file->storeAs('public/product_images/'.$product->id, $file->getClientOriginalName());

                $url = asset('storage/product_images/'.$product->id.'/' . $file->getClientOriginalName());

                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $url,
                    'name' => $file->getClientOriginalName(),
                ]);
            }
        }

        return redirect()->route('products.index')->with('message', 'Product Updated Successfully');
    }



    public function destroy(Product $product)
    {
        $product->delete();
        sleep(1);

        return redirect()->route('products.index')->with('message', 'Product Delete Successfully');
    }



}