<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Companies extends Model
{
    use HasFactory;
    protected $guarded = [];

    // get the company category

    public function category()
    {
        return $this->belongsTo(CompanyCategories::class,'category')->select('id','name','number');
    }
}
