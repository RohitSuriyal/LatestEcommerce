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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator as FacadesValidator;
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
                "brand" => "required",

            ]);

            $uploadedFiles = json_decode($request->uploaded_files, true);
            $filename = $request->file('main_image')->getClientOriginalName();
            $imagePath = $request->file('main_image')->storeAs('products', $filename, 'public');

            $validate["main_image"] = $imagePath;

            $validate["description"] = $request->description;
           
            
             
            $validate["product_images"] = json_encode($request->product_images);
            //  'product_images' => json_encode($productImages, JSON_UNESCAPED_SLASHES), 
            $validate["user_id"]=Auth::guard('admin')->user()->id;
            Product::create($validate);

            return redirect()->route('admin.product.index')->with(["success" => "Product Successfully Added"]);
        } catch (ValidationException $e) {

            return back()->withErrors($e->errors())->withInput();
        } catch (Exception $e) {
          dd($e->getMessage());
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

        $product = Product::findOrFail($id);

        if ($product) {

            $product->delete();

            return response()->json([
                "success" => "Product deleted successfully"
            ]);
        } else {

            return response()->json([
                "error" => "error ocuured"
            ]);
        }
    }


    // Bulk Upload Process
    public function bulkUpload(Request $request)
 {
    try {
        $request->validate([
            'excel_file' => 'required|mimes:xlsx,xls,csv|max:10240',
        ]);

        $file = $request->file('excel_file');

        // Load the Excel file
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file->getRealPath());
        $worksheet = $spreadsheet->getActiveSheet();
        
        // Extract all images from Excel grouped by row and column
        $extractedImages = [];
        foreach ($worksheet->getDrawingCollection() as $drawing) {
            $coordinates = $drawing->getCoordinates(); // e.g., "K2", "L2"
            
            // Parse coordinate to get column and row
            preg_match('/([A-Z]+)(\d+)/', $coordinates, $matches);
            $column = $matches[1];
            $rowNum = $matches[2];
            
            // Get image content
            $imageContents = null;
            $extension = 'png'; // default
            
            if ($drawing instanceof \PhpOffice\PhpSpreadsheet\Worksheet\MemoryDrawing) {
                ob_start();
                call_user_func($drawing->getRenderingFunction(), $drawing->getImageResource());
                $imageContents = ob_get_contents();
                ob_end_clean();
                
                // Get extension from mime type
                switch ($drawing->getMimeType()) {
                    case 'image/jpeg':
                    case 'image/jpg':
                        $extension = 'jpg';
                        break;
                    case 'image/png':
                        $extension = 'png';
                        break;
                    case 'image/gif':
                        $extension = 'gif';
                        break;
                    case 'image/webp':
                        $extension = 'webp';
                        break;
                }
            } else {
                $imageContents = file_get_contents($drawing->getPath());
                $extension = $drawing->getExtension();
            }
            
            // Generate unique filename
            $imageName = 'product_' . $rowNum . '_' . $column . '_' . time() . '_' . uniqid() . '.' . $extension;
            $imagePath = 'products/' . $imageName;
            
            // Save image to storage
            Storage::disk('public')->put($imagePath, $imageContents);
            
            // Store image path by row and column
            // Column L can have multiple images, so store as array
            if (!isset($extractedImages[$rowNum])) {
                $extractedImages[$rowNum] = [];
            }
            if (!isset($extractedImages[$rowNum][$column])) {
                $extractedImages[$rowNum][$column] = [];
            }
            $extractedImages[$rowNum][$column][] = $imagePath;
        }
        
        $rows = $worksheet->toArray();
        $headers = array_shift($rows);

        $successCount = 0;
        $errorCount = 0;
        $errors = [];

        foreach ($rows as $index => $row) {
            try {
                // Skip empty rows
                if (empty(array_filter($row))) {
                    continue;
                }

                $rowNumber = $index + 2; // +2 because array is 0-indexed and we removed header

                // Get category and subcategory names from Excel
                $categoryName = $row[4] ?? null;  // Column E
                $subcategoryName = $row[5] ?? null;  // Column F
                $brandname = $row[6] ?? null;

                // Find category ID by name
                $category = ProductCategory::where('name', $categoryName)->first();
                if (!$category) {
                    $errors[] = "Row {$rowNumber}: Category '{$categoryName}' not found";
                    $errorCount++;
                    continue;
                }

                // Find subcategory ID by name
                $subcategory = Subcategory::where('name', $subcategoryName)->first();
                if (!$subcategory) {
                    $errors[] = "Row {$rowNumber}: Subcategory '{$subcategoryName}' not found under category '{$categoryName}'";
                    $errorCount++;
                    continue;
                }
               
                // Find brand ID by name
                $brand = Brand::where("name", $brandname)->first();
                if (!$brand) {
                    $errors[] = "Row {$rowNumber}: Brand '{$brandname}' not found";
                    $errorCount++;
                    continue;
                }
                
                // Process description - convert comma-separated to array
                $descriptionRaw = $row[7] ?? null;  // Column H
                $descriptionArray = [];

                if (!empty($descriptionRaw)) {
                    $descriptionArray = array_filter(array_map('trim', explode(',', $descriptionRaw)));
                }

                // Get main image from column K (single image)
                $mainImage = null;
                if (isset($extractedImages[$rowNumber]['K']) && !empty($extractedImages[$rowNumber]['K'])) {
                    $mainImage = $extractedImages[$rowNumber]['K'][0]; // First image from column K
                }
                
                // Get multiple product images from column L (multiple images in same column)
                $productImages = [];
                if (isset($extractedImages[$rowNumber]['L']) && !empty($extractedImages[$rowNumber]['L'])) {
                    $productImages = $extractedImages[$rowNumber]['L']; // All images from column L
                }

                // Map Excel columns to database fields
                $productData = [
                    'name' => $row[0] ?? null,    
                    'user_id'=>Auth::guard("admin")->user()->id,       // Column A
                    'price' => $row[1] ?? null,          // Column B
                    'rating' => $row[2] ?? null,         // Column C
                    'discount' => $row[3] ?? null,       // Column D
                    'category' => $category->id,         // Column E (converted to ID)
                    'subcategory' => $subcategory->id,   // Column F (converted to ID)
                    'brand' => $brand->id,               // Column G
                    'description' => $descriptionArray,  // Column H (converted to array)
                    'sale_price' => $row[8] ?? null,     // Column I
                    'stock' => $row[9] ?? null,          // Column J
                    'main_image' => $mainImage,          // Column K (single extracted image)
                    'product_images' => json_encode($productImages, JSON_UNESCAPED_SLASHES), // Column L (multiple extracted images)
                ];

                // Create product
                Product::create($productData);
                $successCount++;
                
            } catch (Exception $e) {
                 
                dd($e->getMessage());
                $errors[] = "Row {$rowNumber}: " . $e->getMessage();
                $errorCount++;
                return back()->with('error', 'Failed to process file: ' . $e->getMessage());
            }
        }

        $message = "Bulk upload completed. Success: {$successCount}, Failed: {$errorCount}";

        if ($errorCount > 0) {
            return back()->with([
                'warning' => $message,
                'errors' => $errors
            ]);
        }

        return redirect()->route('admin.product.index')->with('success', $message);
        
    } catch (Exception $e) {

        dd($e->getMessage());

       
        return back()->with('error', 'Failed to process file: ' . $e->getMessage());
    }
}
}
