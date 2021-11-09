<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleOrder extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'invoice', 'customer', 'status'];

    public function detail_sale_orders()
    {
        return $this->hasMany(DetailSaleOrder::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
