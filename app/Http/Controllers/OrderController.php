<?php
namespace App\Http\Controllers;

use App\Mail\OrderConfirmation;
use App\Mail\OrderConfirmationMail;
use App\Order;
use App\OrderedProducts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{

    public function checkout()
    {
        $user = auth()->user();

        // Order logic here...

        // Send order confirmation email
        Mail::to($user->email)->send(new OrderConfirmation($order));

        return redirect()->route('user.orders')->with('success', 'Order placed successfully! Check your email for confirmation.');
    }

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Handle order confirmation and send email.
     */
    public function sendOrderConfirmation($order)
    {
        // Prepare order data for email
        $orderData = [
            'name'         => $order->user->name,
            'order_number' => $order->order_number,
            'amount'       => $order->total_amount,
            'method'       => $order->payment_method,
            'status'       => $order->status,
            'booking_date' => $order->booking_date,
            'file_url'     => asset('C:\xampp2\htdocs\project\public\storage\virtual_products\1742896236_download (1).jpeg'), // Path to the file in public/downloads
            'file_name'    => 'file.pdf',                                                                                     // Name for download
        ];

        // Send order confirmation email
        Mail::to($order->user->email)->send(new OrderConfirmationMail($orderData));

        return response()->json(['message' => 'Order confirmation email sent successfully!']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //Order::$withoutAppends = true;
        $orders = Order::where('payment_status', "Completed")->orderBy('id', 'desc')->get();
        return view('admin.orderlist', compact('orders'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $order    = Order::findOrFail($id);
        $products = OrderedProducts::where('orderid', $id)->get();
        return view('admin.orderdetails', compact('order', 'products'));
    }

    public function status($id, $status)
    {
        $mainorder = Order::findOrFail($id);
        if ($mainorder->status == "completed") {
            return redirect('admin/orders')->with('message', 'This Order is Already Completed');
        } else {
            $stat['status'] = $status;

            if ($status == "completed") {
                $orders = OrderedProducts::where('orderid', $id)->get();

                foreach ($orders as $payee) {
                    $order = OrderedProducts::findOrFail($payee->id);

                    if ($order->owner == "vendor") {
                        $vendor                     = Vendors::findOrFail($payee->vendorid);
                        $balance['current_balance'] = $vendor->current_balance + $payee->cost;
                        $vendor->update($balance);
                    }
                    $sts['paid']   = "yes";
                    $sts['status'] = "completed";
                    $order->update($sts);
                }
            }

            $mainorder->update($stat);
            return redirect('admin/orders')->with('message', 'Order Status Updated Successfully');
        }
    }

    public function email($id)
    {
        $order = Order::findOrFail($id);
        return view('admin.sendmail', compact('order'));
    }

    // public function sendemail(Request $request)
    // {
    //     mail($request->to, $request->subject, $request->message);
    //     return redirect('admin/orders')->with('message', 'Email Send Successfully');
    // }

    public function sendemail(Request $request)
    {
        $to      = $request->to;
        $subject = $request->subject;
        $message = $request->message;

        // Send email
        Mail::raw($message, function ($msg) use ($to, $subject) {
            $msg->to($to)->subject($subject);
        });

        return redirect('admin/orders')->with('message', 'Email Sent Successfully');
        // mail($request->to, $request->subject, $request->message);
        // return redirect('admin/vendors')->with('message', 'Email Send Successfully');
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
