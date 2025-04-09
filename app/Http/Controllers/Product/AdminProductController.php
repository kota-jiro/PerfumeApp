<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class AdminProductController extends Controller
{
    public function index(Request $request)
    {
        // Check if a category filter is selected (Male/Female)
        $categoryFilter = $request->input('category', null);

        // If a filter is selected, filter products based on the selected category
        if ($categoryFilter) {
            $products = Product::where('category', $categoryFilter)->orderBy('id', 'desc')->paginate(2);
            $totalFiltered = Product::where('category', $categoryFilter)->count();
        } else {
            $products = Product::orderBy('id', 'desc')->paginate(2);
            $totalFiltered = Product::count(); // Total products count when no filter is applied
        }

        // Count the total number of products and the count of Male and Female categories
        $totalMale = Product::where('category', 'Male')->count();
        $totalFemale = Product::where('category', 'Female')->count();
        $total = Product::count();

        // Get all distinct categories for the filter dropdown
        $categories = Product::distinct()->pluck('category');

        return view('admin.product.home', compact(
            'products',
            'total',
            'totalMale',
            'totalFemale',
            'categoryFilter',
            'totalFiltered',
            'categories'
        ));
    }


    public function create()
    {
        return view('admin.product.create');
    }
    public function save(Request $request)
    {
        $validation = $request->validate([
            'title' => 'required|string|max:255|unique:products,title',
            'category' => 'required|in:Male Perfume,Female Perfume',
            'description' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'stock_small' => 'nullable|integer|min:0',
            'stock_medium' => 'nullable|integer|min:0',
            'stock_large' => 'nullable|integer|min:0',
            'price_small' => 'nullable|numeric|min:1199|max:2399',
            'price_medium' => 'nullable|numeric|min:3599|max:5999',
            'price_large' => 'nullable|numeric|min:11999|max:23999',
        ]);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images/products/'), $imageName);
            $validation['image'] = $imageName;
        } else {
            $validation['image'] = 'default.jpg'; // Default image if none is uploaded
        }

        $data = Product::create($validation);

        if ($data) {
            return redirect(route('admin.products'))->with('success', 'Product "' . $data->title . '" Added Successfully!');
        }
        return redirect(route('admin.products.create'))->with('error', 'Some Problem Occurred!');
    }

    public function edit($id)
    {
        $products = Product::findOrFail($id);
        return view('admin.product.update', compact('products'));
    }
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        
        $request->validate([
            'title' => 'required|string|max:255|unique:products,title,' . $product->id,
            'category' => 'required|in:Male Perfume,Female Perfume',
            'description' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'stock_small' => 'nullable|integer|min:0',
            'stock_medium' => 'nullable|integer|min:0',
            'stock_large' => 'nullable|integer|min:0',
            'price_small' => 'nullable|numeric|min:1199|max:2399',
            'price_medium' => 'nullable|numeric|min:3599|max:5999',
            'price_large' => 'nullable|numeric|min:11999|max:23999',
        ]);

        $data = $request->only([
            'title',
            'category',
            'description',
            'stock_small',
            'stock_medium',
            'stock_large',
            'price_small',
            'price_medium',
            'price_large',
        ]);

        // Handle image update
        if ($request->hasFile('image')) {
            // Delete old image if not default
            if ($product->image && $product->image !== 'default.jpg') {
                $oldImagePath = public_path('images/products/' . $product->image);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }

            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images/products/'), $imageName);
            $data['image'] = $imageName;
        }

        $product->update($data);
        if ($product) {
            return redirect(route('admin.products'))->with('success', 'Product "' . $product->title . '" Updated Successfully!');
        }
        return redirect(route('admin.products.update'))->with('error', 'Some Problem Occurred');
    }

    public function destroy($id)
    {
        $product = Product::find($id);

        if (!$product) {
            session()->flash('error', 'Product "' . $product->title . '" Not Found!');
            return redirect()->route('admin.products');
        }

        // Check if the product has an image and it's not the default image
        if ($product->image && $product->image !== 'default.jpg') {
            $imagePath = public_path('images/products/' . $product->image);

            // Delete image file if it exists
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }

        if ($product->delete()) {
            session()->flash('success', 'Product "' . $product->title . '" Deleted Successfully!');
        } else {
            session()->flash('error', 'Product "' . $product->title . '" Not Deleted!');
        }

        return redirect()->route('admin.products');
    }
}
