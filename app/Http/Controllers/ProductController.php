<?php
namespace App\Http\Controllers;

use App\Category;
use App\Gallery;
use App\Models\SerialNumber;
use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class ProductController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::orderBy('id', 'desc')->get();
        return view('admin.productlist', compact('products'));
    }

//    public function pending()
    //    {
    //        $products = Product::where('status','2')->where('approved','no')->orderBy('id','desc')->get();
    //        return view('admin.pendingproduct',compact('products'));
    //    }
    //
    //    public function pendingdetails($id)
    //    {
    //        $product = Product::findOrFail($id);
    //        return view('admin.pendingdetails',compact('product'));
    //    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::where('role', 'main')->get();
        return view('admin.productadd', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = new Product();
        $data->fill($request->all());
        $data->category = $request->mainid . "," . $request->subid . "," . $request->childid;

        if ($file = $request->file('photo')) {
            $photo_name = time() . $request->file('photo')->getClientOriginalName();
            $file->move('assets/images/products', $photo_name);
            $data['feature_image'] = $photo_name;
        }

        if ($request->featured == 1) {
            $data->featured = 1;
        }

        if ($request->pallow == "") {
            $data->sizes = null;
        }

        $data->save();
        $lastid = $data->id;

        if ($files = $request->file('gallery')) {
            foreach ($files as $file) {
                $gallery    = new Gallery;
                $image_name = str_random(2) . time() . $file->getClientOriginalName();
                $file->move('assets/images/gallery', $image_name);
                $gallery['image']     = $image_name;
                $gallery['productid'] = $lastid;
                $gallery->save();
            }
        }
        Session::flash('message', 'New Product Added Successfully.');
        return redirect('admin/products');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    // public function edit($id)
    // {
    //     $product    = Product::findOrFail($id);
    //     $child      = Category::where('role', 'child')->where('subid', $product->category[1])->get();
    //     $subs       = Category::where('role', 'sub')->where('mainid', $product->category[0])->get();
    //     $categories = Category::where('role', 'main')->get();
    //     return view('admin.productedit', compact('product', 'categories', 'child', 'subs'));
    // }

    public function edit($id)
    {
        $product = Product::findOrFail($id);

        // Convert category to an array if needed
        $category = is_array($product->category) ? $product->category : json_decode($product->category, true);

        // Fetch child and subcategories only if respective indexes exist
        $child = Category::where('role', 'child')->where('subid', $category[1] ?? null)->get();
        $subs  = Category::where('role', 'sub')->where('mainid', $category[0] ?? null)->get();

        // Get all main categories
        $categories = Category::where('role', 'main')->get();

        return view('admin.productedit', compact('product', 'categories', 'child', 'subs'));
    }

    // public function edit($id)
    // {
    //     $product = Product::findOrFail($id);

    //     // Ensure category is an array
    //     $category = is_array($product->category) ? $product->category : json_decode($product->category, true);

    //     // Ensure indexes exist before accessing
    //     $child = isset($category[1])
    //     ? Category::where('role', 'child')->where('subid', $category[1])->get()
    //     : collect();

    //     $subs = isset($category[0])
    //     ? Category::where('role', 'sub')->where('mainid', $category[0])->get()
    //     : collect();

    //     $categories = Category::where('role', 'main')->get();

    //     return view('admin.productedit', compact('product', 'categories', 'child', 'subs'));
    // }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $product           = Product::findOrFail($id);
        $input             = $request->all();
        $input['category'] = $request->mainid . "," . $request->subid . "," . $request->childid;

        if ($file = $request->file('photo')) {
            $photo_name = time() . $request->file('photo')->getClientOriginalName();
            $file->move('assets/images/products', $photo_name);
            $input['feature_image'] = $photo_name;
        }

        if ($request->galdel == 1) {
            $gal = Gallery::where('productid', $id);
            $gal->delete();
        }

        if ($request->pallow == "") {
            $input['sizes'] = null;
        }

        if ($request->featured == 1) {
            $input['featured'] = 1;
        } else {
            $input['featured'] = 0;
        }

        // Store Serial Numbers and Uploaded Files if stock > 0
        if ($product->stock > 0) {
            $serialNumbers = $request->input('serial_numbers', []);
            foreach ($serialNumbers as $serial) {
                SerialNumber::create([
                    'product_id'    => $product->id,
                    'serial_number' => strtoupper($serial),
                    'status'        => 'available',
                    'file_name'     => null,
                ]);
            }

            // Define storage paths for different file types
            $fileInputs = [
                'pdf_files'     => 'pdfs',
                'zip_gif_files' => 'zip_gifs',
                'mp3_mp4_files' => 'mp3_mp4s',
            ];

            foreach ($fileInputs as $inputName => $folder) {
                if ($request->hasFile($inputName)) {
                    foreach ($request->file($inputName) as $file) {
                        if ($file->isValid()) {
                            $fileName = time() . '_' . preg_replace('/\s+/', '_', $file->getClientOriginalName());
                            $filePath = "assets/virtual_products/{$folder}/" . $fileName;

                            // Ensure directory exists
                            if (! file_exists(public_path("assets/virtual_products/{$folder}"))) {
                                mkdir(public_path("assets/virtual_products/{$folder}"), 0777, true);
                            }

                            // Move file to the correct location
                            $file->move(public_path("assets/virtual_products/{$folder}"), $fileName);

                            // Save serial number linked to this file
                            SerialNumber::create([
                                'product_id'    => $product->id,
                                'serial_number' => strtoupper(pathinfo($fileName, PATHINFO_FILENAME)),
                                'status'        => 'available',
                                'file_name'     => $filePath,
                            ]);

                            Log::info("File Stored: " . $filePath);
                        } else {
                            Log::error("Invalid file detected in input: " . $inputName);
                        }
                    }
                } else {
                    Log::error("No files found in input: " . $inputName);
                }
            }
        }

        $product->update($input);

        if ($files = $request->file('gallery')) {
            foreach ($files as $file) {
                $gallery    = new Gallery;
                $image_name = str_random(2) . time() . $file->getClientOriginalName();
                $file->move('assets/images/gallery', $image_name);
                $gallery['image']     = $image_name;
                $gallery['productid'] = $id;
                $gallery->save();
            }
        }

        Session::flash('message', 'Product Updated Successfully.');
        return redirect('admin/products');
    }

    public function status($id, $status)
    {
        $product         = Product::findOrFail($id);
        $input['status'] = $status;

        $product->update($input);
        Session::flash('message', 'Product Status Updated Successfully.');
        return redirect('admin/products');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        unlink('assets/images/products/' . $product->feature_image);
        $product->delete();
        return redirect('admin/products')->with('message', 'Product Delete Successfully.');
    }
}
