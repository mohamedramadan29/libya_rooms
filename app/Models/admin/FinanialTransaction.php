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
        return $this->belongsTo(Companies::class,'company_id');
    }
    // get employee data
    public function employe_data()
    {
        return $this->belongsTo(User::class,'employe_id')->select('name','id');
    }

    public static function total_transaction($company_id)
    {
        return FinanialTransaction::where('company_id', $company_id)->sum('trans_price');
      //  $total_transaction = FinanialTransaction::where('company_id',$company_id)->sum();
    }

}
