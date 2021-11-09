<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['sku', 'name', 'price', 'stock'];

    public function detail_purchase_orders()
    {
        return $this->hasMany(DetailPurchaseOrder::class);
    }

    public function detail_sale_orders()
    {
        return $this->hasMany(DetailSaleOrder::class);
    }
}
