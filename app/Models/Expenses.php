<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expenses extends Model
{
    use HasFactory;
    public function expense_category(){
        return $this->belongsTo(ExpenseCategories::class);
    }
}
