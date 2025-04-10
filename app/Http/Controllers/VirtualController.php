<?php
namespace App\Http\Controllers;

use App\Cart;
use App\Category;
use App\Models\SerialNumber;
use App\Product;
use App\Settings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class VirtualController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::where('role', 'main')->get();
        return view('admin.productaddvirtual', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    // public function store(Request $request)
    // {
    //     try {
    //         // Handle file upload for product image
    //         $photoName = null;

    //         if ($request->hasFile('virtual_files')) {
    //             $photo = $request->file('virtual_files');

    //             if ($photo->isValid()) {
    //                 $photoName = time() . '_' . preg_replace('/\s+/', '_', $photo->getClientOriginalName());
    //                 $photo->move(public_path('assets/images/products'), $photoName);
    //             }
    //         }

    //         // Create New Product
    //         $product        = new Product();
    //         $product->title = $request->input('title');

    //         // Assign category logic
    //         $mainid  = $request->input('mainid');
    //         $subid   = $request->input('subid');
    //         $childid = $request->input('childid');

    //         $product->category      = $childid ?? $subid ?? $mainid ?? 'Virtual Products';
    //         $product->feature_image = $photoName; // Store the uploaded image name in DB

    //         // Store product details
    //         $product->previous_price = $request->input('previous_price');
    //         $product->stock          = $request->input('stock');
    //         $product->price          = $request->input('virtual_price');
    //         $product->description    = $request->input('description');
    //         $product->product_type   = 'Virtual';
    //         $product->status         = 1;
    //         $product->featured       = $request->has('featured') ? 1 : 0;

    //         // Save product to database
    //         $product->save();

    //         // Store Serial Numbers and Uploaded Files if stock > 0
    //         if ($product->stock > 0) {
    //             // Store manually entered serial numbers
    //             $serialNumbers = $request->input('serial_numbers', []);

    //             foreach ($serialNumbers as $serial) {
    //                 SerialNumber::create([
    //                     'product_id'    => $product->id,
    //                     'serial_number' => strtoupper($serial),
    //                     'status'        => 'available',
    //                     'file_name'     => null, // No file associated
    //                 ]);
    //             }

    //             // Define storage paths for different file types
    //             $fileInputs = [
    //                 'pdf_files'     => 'pdfs',
    //                 'zip_gif_files' => 'zip_gifs',
    //                 'mp3_mp4_files' => 'mp3_mp4s',
    //             ];

    //             foreach ($fileInputs as $inputName => $folder) {
    //                 if ($request->hasFile($inputName)) {
    //                     foreach ($request->file($inputName) as $file) {
    //                         $fileName = time() . '_' . $file->getClientOriginalName(); // Unique file name
    //                         $filePath = "assets/virtual_products/{$folder}/" . $fileName;

    //                         // Move file to the public storage
    //                         $file->move(public_path("assets/virtual_products/{$folder}"), $fileName);

    //                         SerialNumber::create([
    //                             'product_id'    => $product->id,
    //                             'serial_number' => strtoupper(pathinfo($fileName, PATHINFO_FILENAME)), // Use filename as serial
    //                             'status'        => 'available',
    //                             'file_name'     => $filePath, // Store the actual file path
    //                         ]);
    //                     }
    //                 }
    //             }
    //         }

    //         return redirect('admin/products')->with('success', 'Virtual product added successfully!');
    //     } catch (\Exception $e) {
    //         return redirect()->back()->with('error', 'An error occurred while adding the product. Please try again!');
    //     }
    // }

    public function store(Request $request)
    {
        try {
            // Handle file upload for product image
            $photoName = null;
            if ($request->hasFile('virtual_files')) {
                $photo = $request->file('virtual_files');
                if ($photo->isValid()) {
                    $photoName = time() . '_' . preg_replace('/\s+/', '_', $photo->getClientOriginalName());
                    $photo->move(public_path('assets/images/products'), $photoName);
                }
            }

            // Create New Product
            $product        = new Product();
            $product->title = $request->input('title');

            // Assign category logic
            $mainid                 = $request->input('mainid');
            $subid                  = $request->input('subid');
            $childid                = $request->input('childid');
            $product->category      = $childid ?? $subid ?? $mainid ?? 'Virtual Products';
            $product->feature_image = $photoName;

            // Store product details
            $product->previous_price = $request->input('previous_price');
            $product->stock          = $request->input('stock');
            $product->price          = $request->input('virtual_price');
            $product->description    = $request->input('description');
            $product->product_type   = 'Virtual';
            $product->status         = 1;
            $product->featured       = $request->has('featured') ? 1 : 0;

            // Save product to database
            $product->save();

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

            return redirect('admin/products')->with('success', 'Virtual product added successfully!');
        } catch (\Exception $e) {
            Log::error('Error while adding product: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while adding the product. Please try again!');
        }
    }

    //Submit Review
    public function checkout1()
    {
        $product  = 0;
        $quantity = 0;
        $sizes    = 0;
        $settings = Settings::findOrFail(1);
        $carts    = Cart::where('uniqueid', Session::get('uniqueid'));
        $cartdata = $carts->get();
        if ($carts->count() > 0) {
            $total = $carts->sum('cost') + $settings->shipping_cost;
            foreach ($carts->get() as $cart) {
                if ($product == 0 && $quantity == 0) {
                    $product  = $cart->product;
                    $quantity = $cart->quantity;
                    $sizes    = $cart->size;
                } else {
                    $product  = $product . "," . $cart->product;
                    $quantity = $quantity . "," . $cart->quantity;
                    $sizes    = $sizes . "," . $cart->size;

                }
            }
            return view('checkout1', compact('product', 'sizes', 'quantity', 'total', 'cartdata', 'user'));
        }

        return redirect()->route('user.cart')->with('message', 'You don\'t have any product to checkout.');
    }

//     public function checkout1()
    //     {
    //         $product  = '';
    //         $quantity = '';
    //         $sizes    = '';
    //         $settings = Settings::findOrFail(1);
    //         $carts    = Cart::where('uniqueid', Session::get('uniqueid'));
    //         $cartdata = $carts->get();

//         if ($carts->count() > 0) {
    //             $total = $carts->sum('cost') + $settings->shipping_cost;

//             $isVirtualProduct = true; // Assume all products are virtual by default
    //             foreach ($cartdata as $cart) {
    //                 // Fetch product and check if it's virtual or physical
    //                 $productModel = Product::find($cart->product);
    //                 if ($productModel && $productModel->type !== 'virtual') {
    //                     $isVirtualProduct = false;
    //                 }

//                 // Prepare product, quantity, and size strings for order
    //                 $product .= $product == '' ? $cart->product : ',' . $cart->product;
    //                 $quantity .= $quantity == '' ? $cart->quantity : ',' . $cart->quantity;
    //                 $sizes .= $sizes == '' ? $cart->size : ',' . $cart->size;
    //             }

//             // Set order status based on product type
    //             $status = $isVirtualProduct ? 'complete' : 'pending';

//             /// Generate a unique order number
    //             $order_number = strtoupper('ORD-' . uniqid());

//             // Get the authenticated user's information
    //             $user         = auth()->user();
    //             $user_email   = $user->email;
    //             $user_name    = $user->name;           // Assuming 'name' field exists in users table
    //             $user_phone   = $user->phone ?? 'N/A'; // Assuming 'phone' field exists or set a default
    //             $user_address = $user->address ?? 'N/A';
    //             $user_city    = $user->city ?? 'N/A';
    //             $user_zip     = $user->zip ?? 'N/A';
    // // Create the order
    //             $order = Order::create([
    //                 'user_id'          => $user->id,     // Pass user ID
    //                 'customerid'       => $user->id,     // Pass customer ID if required
    //                 'customer_name'    => $user_name,    // Add customer name here
    //                 'customer_email'   => $user_email,   // Add customer email here
    //                 'customer_phone'   => $user_phone,   // Add customer phone here
    //                 'customer_address' => $user_address, // Add customer address here
    //                 'customer_city'    => $user_city,    // Add customer city here
    //                 'customer_zip'     => $user_zip,     // Add customer zip here
    //                 'order_number'     => $order_number, // Add order_number here
    //                 'products'         => $product,
    //                 'quantities'       => $quantity,
    //                 'sizes'            => $sizes,
    //                 'total_amount'     => $total, // Total amount
    //                 'pay_amount'       => $total, // Add this line to pass pay_amount
    //                 'status'           => $status,
    //                 'payment_status'   => 'pending', // Add default payment status
    //             ]);
    //             // Send order confirmation email to the user
    //             $user = auth()->user();
    //             Mail::to($user->email)->send(new OrderConfirmation($order));

//             // Clear the cart after successful checkout
    //             $carts->delete();

//             // Redirect to order success page
    //             return redirect()->route('user.orders')->with('success', 'Order placed successfully! Check your email for confirmation.');
    //         }

//         return redirect()->route('user.cart')->with('message', 'You don\'t have any products to checkout.');
    //     }

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
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
