<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\BannerDataTable;
use App\Http\Controllers\Controller;
use App\Models\Admin\Banner;
use Exception;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class BannerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(BannerDataTable $bannerDataTable)
    {
        return $bannerDataTable->render('admin.banner.index');
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.banner.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validate = $request->validate([
                "name" => "required",
                "image" => "required"
            ]);

            if ($file = $request->file('image')) {
                $filename =  $file->getClientOriginalName(); // make it unique
                $imagePath = $file->storeAs('products', $filename, 'public'); // store in storage/app/public/products
                $validate['image'] = $imagePath; // save relative path for DB
            }

            Banner::create($validate);

            return redirect()->route('admin.banner.index')->with(["success" => "Banner Added success full"]);
        } catch (ValidationException $e) {

            return back()->withErrors($e->errors())->withInput();
        } catch (Exception $e) {


            return back()->with(["error" => $e->getMessage()]);
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

        
        $bannerimage=Banner::findOrFail($id);

        if($bannerimage){

         $bannerimage->delete();
        }


        return response()->json([
            "success"=>"Banner Deleted Successfully"
        ]);
    }
}
