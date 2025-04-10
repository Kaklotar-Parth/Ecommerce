<?php
namespace App\Http\Controllers;

use App\Cart;
use Illuminate\Support\Facades\DB;

// Corrected Cart facade

class CartController extends Controller
{

    // Show cart with dynamic category buttons
    public function showCart1()
    {
                                      // Get all cart items from the Shoppingcart package
        $cartItems = Cart::content(); // Fetch items correctly from the cart

        // Default category to physical
        $productCategory = 'physical';

        // Loop through all items to check if any is virtual
        foreach ($cartItems as $item) {
            // Fetch product category from the 'products' table based on item ID
            $product = DB::table('products')->where('id', $item->id)->first();

            // If any product is virtual, set category to virtual
            if ($product && $product->category == 'virtual') {
                $productCategory = 'virtual';
                break; // Stop checking once a virtual product is found
            }
        }

        // Calculate the total amount (if not handled by the cart package)
        $sum = $cartItems->sum(function ($item) {
            return $item->qty * $item->price;
        });

        // Return the view with necessary data
        return view('cart', compact('productCategory', 'sum', 'cartItems'));
    }
}
