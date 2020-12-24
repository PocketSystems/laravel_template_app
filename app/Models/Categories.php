<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categories extends Model
{
    protected $table = 'categories';
    protected $fillable = [ 'image', 'name','status','user_id','company_id','is_archive'];
    use HasFactory;

    public function items(){
        return $this->hasMany(Items::class);
    }
}
