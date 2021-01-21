<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;
    protected $table = 'inventories';
    protected $appends = ["stock","total"];

    public function item(){
        return $this->belongsTo(Items::class);
    }
    public function getStockAttribute(){
        return Inventory::where("id",$this->id)->sum('quantity');
    }

    public function getTotalAttribute(){
        return Inventory::where("id",$this->id)->sum('cost_total');
    }

}
