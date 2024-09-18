<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinanialTransaction extends Model
{
    use HasFactory;
    protected $guarded = [];

    // To get Company data

    public function company_data()
    {
        return $this->belongsTo(Companies::class,'company_id')->select('name','id');
    }
    // get employee data
    public function employe_data()
    {
        return $this->belongsTo(User::class,'employe_id')->select('name','id');
    }
}
