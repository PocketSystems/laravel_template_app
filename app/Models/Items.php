<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Items extends Model
{
    use HasFactory;
    protected $table = 'items';
    public function category(){
        return $this->belongsTo(Categories::class);
    }
    public function purchaseOrder(){
        return $this->hasMany(PurchaseOrderItems::class);
    }
    public function saleOrder(){
        return $this->hasMany(SaleOrderItems::class);
    }
}
