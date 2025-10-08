<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\SubcategoryDataTable;
use App\Http\Controllers\Controller;
use App\Models\Subcategory;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class SubcategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(SubcategoryDataTable $dataTable)
    {

        return $dataTable->render('admin.subcategory.index');
        // return view('admin.subcategory.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("admin.subcategory.create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {

            $validate = $request->validate([
                "name" => "required",
            ]);

            Subcategory::create($validate);

            return  redirect()->route("admin.subcategory.index")->with(["success" => "Subcategory Added Successfully"]);
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

       
        try {

            $subcategory = Subcategory::findOrFail($id);

            if ($subcategory) {

                $subcategory->delete();
            }
           return response()->json([
            "success"=>"Data Deleted Successfully"
           ]);
        } catch (Exception $e) {

            return back()->with(["error" => "Something went wrong"]);
        }
    }
}
