<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleCart extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'product_id', 'qty', 'price'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
