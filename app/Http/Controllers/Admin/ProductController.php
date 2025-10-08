<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\ProductDataTable;
use App\Http\Controllers\Controller;
use App\Models\Admin\Brand;
use App\Models\Admin\ProductCategory;
use App\Models\Product;
use App\Models\Subcategory;
use Exception;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use PhpParser\NodeVisitor\CommentAnnotatingVisitor;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(ProductDataTable $dataTable)
    {
      
        // return view("admin.product.index");
        return $dataTable->render('admin.product.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $productcategories = ProductCategory::all();
        $productsubcategories = Subcategory::all();
        $brands = Brand::all();
        return view('admin.product.create', compact('productcategories', 'productsubcategories', 'brands'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

      
        try {

            $validate = $request->validate([
                "name" => 'required',
                "price" => "required",
                "rating" => "required",
                "discount" => "required",
                "category" => "required",
                "subcategory" => "required",
                "description" => "required",
                "sale_price" => "required",
                "stock" => "required",
                "main_image" => "required",
                "product_images" => "required",
                "brand"=>"required",

            ]);

            $uploadedFiles = json_decode($request->uploaded_files, true);
            $filename = $request->file('main_image')->getClientOriginalName();
            $imagePath = $request->file('main_image')->storeAs('products', $filename, 'public');

            $validate["main_image"] = $imagePath;

            $validate["description"]=json_encode($request->description);

            $product_images = json_encode($request->product_images);
            $validate["product_images"]= $product_images;

            Product::create($validate);

            return redirect()->route('admin.product.index')->with(["success"=>"Product Successfully Added"]);
        } catch (ValidationException $e) {
        
          return back()->withErrors($e->errors())->withInput();

        } catch (Exception $e) {

           

               return back()->with(["error"=>"Something Went wrong"]);

        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
