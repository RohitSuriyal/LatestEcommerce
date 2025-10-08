<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\BrandDataTable;
use App\Http\Controllers\Controller;
use App\Models\Admin\Brand;
use Dotenv\Exception\ValidationException as ExceptionValidationException;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(BrandDataTable $dataTable)
    {
    
       return  $dataTable->render('admin.brand.index');
      
       
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.brand.create');

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

            Brand::create($validate);

           return redirect()->route('admin.brand.index')->with(["success"=>"Brand Added Successfully"]);

        }catch(ValidationException $e)
        {
            
            return back()->withErrors($e->errors())->withInput();

         }catch(ExceptionValidationException $e)
         {

              return back()->with(["error"=>$e->getMessage()]);
              
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

        try{

        $brand=Brand::findOr($id);

        if($brand){

            $brand->delete();


        return response()->json([
            "success"=>"Data deleted Successfully"
        ]);


        }else{



        }


            
        }catch(Exception $_ENV){


        }
        
    }
}
