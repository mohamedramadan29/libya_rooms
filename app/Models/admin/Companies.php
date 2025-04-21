<?php

namespace App\Models\admin;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
    public function expiringCompaniesinlastmonth()
    {
        $user = Auth::user();

        // استعلام الشركات بناءً على نوع المستخدم
        if ($user->type == 'admin') {
            $query = Companies::with('subcategory', 'categorydata', 'companytype');
        } elseif ($user->type == 'supervisor') {
            $query = Companies::with('subcategory', 'categorydata', 'companytype')
                ->where('region', $user->regions);

            if ($user->branches !== null) {
                $query->where('branch', $user->branches);
            }
        }

        // جلب الشركات (بدون فلترة التاريخ بعد)
        $allCompanies = $query->orderBy('id', 'desc')->get();

        // فلترة الشركات التي سينتهي توثيقها خلال الشهر القادم باستخدام accessor
        $companies = $allCompanies->filter(function ($company) {
            $expiryDate = $company->expiry_date;

            return $expiryDate >= now() && $expiryDate <= now()->addMonth();
        });

        // حساب العدد
        $expiringCount = $companies->count();

        // إرسال للواجهة
        return view('admin.companies.expiremonth', [
            'companies' => $companies,
            'expiringCount' => $expiringCount
        ]);
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
