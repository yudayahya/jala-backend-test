<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailSaleOrder extends Model
{
    use HasFactory;

    protected $fillable = ['sale_order_id', 'product_id', 'qty', 'price'];

    protected $with = ['product'];

    public function sale_order()
    {
        return $this->belongsTo(SaleOrder::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
