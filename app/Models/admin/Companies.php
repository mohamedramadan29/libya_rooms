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

    public function categorydata()
    {
        return $this->belongsTo(CompanyCategories::class,'category');
    }

    public function subcategory()
    {
        return $this->belongsTo(CompanyCategories::class,'sub_category')->select('id','name','number');
    }

    public function companytype()
    {
        return $this->belongsTo(CompanyType::class, 'type');
    }




    // تحديد تاريخ انتهاء الشركة بناءً على آخر توثيق ومدة القيد
    public function getExpiryDateAttribute()
    {
        // إذا كان هناك تاريخ تجديد توثيق، نستخدمه؛ وإلا نستخدم أول توثيق
        $marketConfirmDate = $this->new_market_confirm_date
            ? Carbon::parse($this->new_market_confirm_date)
            : Carbon::parse($this->first_market_confirm_date);

        // إضافة مدة القيد (عدد السنوات)

        return $marketConfirmDate->addYears(intval($this->isadarـduration));
    }

    // التحقق مما إذا كانت الشركة منتهية الصلاحية
    public function isExpired()
    {
        return $this->expiry_date->isPast(); // مقارنة تاريخ الانتهاء بالتاريخ الحالي
    }

    // التحقق مما إذا كانت الشركة ستنتهي خلال مدة معينة (عدد الأشهر)
    public function expiresIn($months)
    {
        $targetDate = Carbon::now()->addMonths($months); // تحديد التاريخ المستهدف بعد عدد الأشهر
        return $this->expiry_date->between(Carbon::now(), $targetDate); // التحقق بين التاريخ الحالي والتاريخ المستهدف
    }



}
