<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPurchaseOrder extends Model
{
    use HasFactory;

    protected $fillable = ['purchase_order_id', 'product_id', 'qty', 'price'];

    protected $with = ['product'];

    public function purchase_order()
    {
        return $this->belongsTo(PurchaseOrder::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
