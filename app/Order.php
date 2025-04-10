<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = ['customerid',
        'products',
        'user_id',
        'quantities',
        'method',
        'pay_amount',
        'txnid',
        'charge_id',
        'products',
        'sizes',
        'order_number',
        'total_amount',
        'payment_status',
        'customer_email',
        'customer_name',
        'customer_phone',
        'customer_address',
        'customer_city',
        'customer_zip',
        'booking_date',
        'status'];
    // protected $fillable = [
    //     'user_id',
    //     'customerid',
    //     'customer_name',
    //     'customer_email',
    //     'customer_phone',
    //     'customer_address',
    //     'customer_city',
    //     'customer_zip',
    //     'order_number',
    //     'products',
    //     'quantities',
    //     'sizes',
    //     'total_amount',
    //     'pay_amount',
    //     'status',
    //     'payment_status',
    // ];
    public $timestamps            = false;
    public static $withoutAppends = false;

    public function getProductsAttribute($products)
    {
        if (self::$withoutAppends) {
            return $products;
        }
        return explode(',', $products);
    }
    public function getQuantitiesAttribute($quantities)
    {
        if (self::$withoutAppends) {
            return $quantities;
        }
        return explode(',', $quantities);
    }

}
