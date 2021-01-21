<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customers extends Model
{
    protected $appends = ["balance"];
    use HasFactory;
    public function saleOrder(){
        return $this->hasMany(SaleOrders::class);
    }
    public function getBalanceAttribute(){
        $balance =  Ledger::where("nature_id",$this->id)->where('nature','customer')->orderBy('id', 'desc')->get('balance')->first();
        return (empty($balance) ? 0: $balance['balance'] );
    }
}
