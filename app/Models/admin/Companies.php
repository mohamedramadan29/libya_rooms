<?php

namespace App\Models\admin;

use Carbon\Carbon;
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


    //////////////// Strat Get The Expire Company Date
    ///
    public function getExpiryDateAttribute()
    {
        return Carbon::parse($this->isdar_date)->addYears($this->isadarـduration);
    }

    public function isExpired()
    {
        return $this->expiry_date->isPast();
    }

    // التحقق مما إذا كانت الشركة تنتهي خلال مدة معينة
    public function expiresIn($months)
    {
        $targetDate = Carbon::now()->addMonths($months);
        return $this->expiry_date->between(Carbon::now(), $targetDate);
    }



}
