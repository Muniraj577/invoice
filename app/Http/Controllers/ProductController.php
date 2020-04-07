<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function app()
    {
        return view('layouts.app');
    }
    public function index()
    {
        $products = Product::all();
        return view('products.index', compact('products'))->with('id');
    }

    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
           'name' => 'required',
           'code' => 'required',
           'price'=> 'required'
        ],[
            'code.required' => 'The product code is required.',
            'name.required' => 'The product name is required.',
            'price.required' => 'The product price is required.'
        ]);
        $product = new Product();
        $product->code = $request->code;
        $product->name = $request->name;
        $product->price = $request->price;
        $product->save();
        return redirect()->route('products.index')->with('success', 'Product created successfully.');
    }

    public function show(Product $product)
    {
        //
    }

    public function edit(Request $request, $id)
    {
        $product = Product::find($id);
        return view('products.edit', compact('product'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request,[
           'code' => 'required',
           'name' => 'required',
           'price' => 'required',
        ], [
            'code.required' => 'The product code is required.',
            'name.required' => 'The product name is required.',
            'price.required' => 'The product price is required.'
        ]);
        $product = Product::find($id);
        $product->code = $request->code;
        $product->name = $request->name;
        $product->price = $request->price;
        $product->save();
        return redirect()->route('products.index')->with('success', 'Product updated successfully');
    }

    public function destroy(Product $product)
    {
        //
    }
}
