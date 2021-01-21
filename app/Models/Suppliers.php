<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Suppliers extends Model
{
    use HasFactory;
    protected $appends = ["balance"];
    protected $table = 'suppliers';
    protected $fillable = [ 'image', 'name','phone','email','address'];
    public function purchaseOrder(){
        return $this->hasMany(PurchaseOrders::class);
    }
    public function getBalanceAttribute(){
        $balance =  Ledger::where("nature_id",$this->id)->where('nature','supplier')->orderBy('id', 'desc')->get('balance')->first();
        return (empty($balance) ? 0: $balance['balance'] );
    }
}
