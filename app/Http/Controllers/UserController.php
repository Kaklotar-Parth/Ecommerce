<?php
namespace App\Http\Controllers;

use App\Mail\OrderConfirmation;
use App\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
    // Show user dashboard
    public function index()
    {
        return view('users.index');
    }

    // Place an order and send confirmation
    public function orders(Request $request)
    {
        $user = auth()->user();

        // Check for missing values and use default if necessary
        $order                   = new Order();
        $order->customerid       = $user->id;
        $order->customer_name    = $user->name;
        $order->customer_email   = $user->email;
        $order->customer_phone   = $user->phone ?: 'N/A';   // Handle null phone
        $order->customer_address = $user->address ?: 'N/A'; // Handle null address
        $order->customer_city    = $user->city ?: 'N/A';    // Handle null city
        $order->customer_zip     = $user->zip ?: '000000';  // Handle null zip
        $order->order_number     = 'ORD-' . strtoupper(str_random(8));
        $order->products         = json_encode($request->products) ?: '[]'; // Default empty array
        $order->quantities       = json_encode($request->quantities) ?: '[]';
        $order->total_amount     = $request->total_amount ?: 0.00;
        $order->pay_amount       = $request->total_amount ?: 0.00;
        $order->payment_status   = 'Pending';
        $order->status           = 'Pending';
        $order->save();

        // Send order confirmation email
        Mail::to($user->email)->send(new OrderConfirmation($order));

        // Redirect with success message
        return redirect()->route('user.orders')->with('success', 'Order placed successfully! Check your email for confirmation.');
    }

}
