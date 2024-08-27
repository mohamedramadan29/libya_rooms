<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyCategories extends Model
{
    protected $guarded = [];
    use HasFactory;
    // Get Parent Category

    public function getparent()
    {
        return $this->belongsTo(CompanyCategories::class,'parent_id');
    }
}
