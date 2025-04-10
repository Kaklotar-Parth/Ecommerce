<?php
namespace App\Models;

use App\Product;
use Illuminate\Database\Eloquent\Model;

class SerialNumber extends Model
{
    // protected $fillable = ['product_id', 'serial_number', 'status'];
    protected $fillable = [
        'product_id',
        'serial_number',
        'status',
        'file_name',
        'file_path',
        'file_type',
        'mime_type',
    ];
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
