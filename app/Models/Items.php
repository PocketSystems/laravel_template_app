<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Items extends Model
{
    use HasFactory;
    protected $table = 'items';
    protected $appends = ["inventory_qty","stock"];

    public function category(){
        return $this->belongsTo(Categories::class);
    }
    public function purchaseOrder(){
        return $this->hasMany(PurchaseOrderItems::class);
    }
    public function inventory(){
        return $this->hasMany(Inventory::class,'item_id');
    }
    public function saleOrder(){
        return $this->hasMany(SaleOrderItems::class);
    }

    public function getInventoryQtyAttribute(){
        return Inventory::where("item_id",$this->attributes['id'])->sum('quantity');
    }


    public function getStockAttribute(){
        return Inventory::where("item_id",$this->id)->sum('quantity');
    }


    public function getLastAddedDateAttribute(){
        return Inventory::with("item_id",$this->id)->sum('cost_total');
    }
}
