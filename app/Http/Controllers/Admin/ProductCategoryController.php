<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\ProductCategoryDataTable;
use App\Http\Controllers\Controller;
use App\Models\Admin\ProductCategory;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ProductCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(ProductCategoryDataTable $dataTable)
    {

        return $dataTable->render('admin.productcategory.index');

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.productcategory.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {  
        

        try{

            $validate=$request->validate([

            "name"=>"required",
         ]);

         ProductCategory::create($validate);

         return redirect()->route("admin.Productcategory.index")->with(["success"=>"Product Added Successfully"]);

        }catch(ValidationException $e){
         

         return back()->withErrors($e->errors())->withInput();



        }catch(Exception $e){


            return back()->with([
                "error"=>$e->getMessage()
            ]);


        }
       

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        
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
    

  
        try{

            $productcategory=ProductCategory::findOrFail($id);
            
            if($productcategory){

                $productcategory->delete();
            }

            return response()->json([
                "success"=>"successfully deleted"
            ]);



        }catch(Exception){



        }
    }
}
