<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\Message_Trait;
use App\Http\Traits\Upload_image;
use App\Models\admin\Branch;
use App\Models\admin\Companies;
use App\Models\admin\CompanyCategories;
use App\Models\admin\CompanyType;
use App\Models\admin\FinanialTransaction;
use App\Models\admin\Region;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class CompaniesController extends Controller
{
    use Message_Trait;
    use Upload_image;

    public function index(Request $request)
    {

        $user = Auth::user();
        if ($user->type == 'admin') {
            $companies = Companies::with('subcategory','categorydata', 'companytype')->orderby('id', 'desc')->get();
            //  dd($companies);
        } elseif ($user->type == 'supervisor') {
            $query = Companies::where('region', $user->regions);
            // إذا كان لدى المشرف فرع معين، أضف شرط الفرع
            if ($user->branches !== null) {
                $query->where('branch', $user->branches);
            }
            $companies = $query->with('subcategory','categorydata', 'companytype')->get();
        } elseif ($user->type == 'money') {
            $companies = Companies::with('subcategory','categorydata', 'companytype')->where('market_confirm', '1')->orderby('id', 'desc')->where('region', $user->regions)->where('branch', $user->branches)->get();
        } elseif ($user->type == 'market') {
            $companies = Companies::with('subcategory','categorydata', 'companytype')->orderby('id', 'desc')->where('region', $user->regions)->where('branch', $user->branches)->get();
        }
        $categories = CompanyCategories::where('status', '1')->where('parent_id', '0')->get();
        $types = CompanyType::where('status', '1')->get();
        return view('admin.companies.index', compact('companies', 'categories', 'types'));
    }

    public function getBranches($region_id)
    {
        $branches = Branch::where('region_id', $region_id)->get();
        // dd($branches);
        return response()->json($branches);
    }

    public function getsubcategories($main_category)
    {

        $subcategories = CompanyCategories::where('parent_id', $main_category)->get();
        return response()->json($subcategories);

    }

    public function store(Request $request)
    {
        if (Auth::user()->type == 'admin') {
            $regions = Region::all();
        } elseif (Auth::user()->type == 'supervisor') {
            $regions = Region::where('id', Auth::user()->regions)->get();
        } else {
            // $regions = null;
            $regions = Region::all();
        }
        $categories = CompanyCategories::where('status', '1')->where('parent_id', '0')->get();
        $types = CompanyType::where('status', '1')->get();
        if ($request->isMethod('post')) {
            try {
                $data = $request->all();
                $rules = [
                    'name' => 'required',

                    'birthplace' => 'required',
                    'nationality' => 'required',
                    'id_number' => 'required',
                    'place' => 'required',
                    'personal_number' => 'required',
                    'trade_name' => 'required',
                    'category' => 'required',
                    'money_head' => 'required',
                    'bank_name' => 'required',
                    'licenseـnumber' => 'required',
                    'tax_number' => 'required',
                    'address' => 'required',
                    'mobile' => 'required',
                    'email' => 'required',
                    'commercial_number' => 'required',
                    'jihad_isdar' => 'required',
//                    'active_circle' => 'required',
                    'isdar_date' => 'required',
                    'isadarـduration' => 'required',
                    'type' => 'required',
                    'status' => 'required',
                    'regions' => 'required',
                    'branches' => 'required',
                ];
                $messages = [
                    'name.required' => ' من فضلك ادخل اسم الممثل القانوني  ',

                    'birthplace.required' => 'من فضلك ادخل مكان الميلاد',
                    'nationality.required' => 'من فضلك ادخل الجنسية ',
                    'id_number.required' => 'من فضلك ادخل الرقم الوطني ',
                    'place.required' => 'من فضلك ادخل محل الاقامة ',
                    'personal_number.required' => 'من فضلك ادخل رقم اثبات الشخصية ',
                    'trade_name.required' => 'من فضلك ادخل الاسم التجاري ',
                    'category.required' => 'من فضلك حدد نوع النشاط ',
                    'money_head.required' => 'من فضلك حدد راس المال ',
                    'bank_name.required' => 'من فضلك ادخل المصرف ',
                    'licenseـnumber.required' => 'من فضلك ادخل رقم الترخيص ',
                    'tax_number.required' => 'من فضلك ادخل الرقم الضريبي ',
                    'address.required' => 'من فضلك ادخل العنوان ',
                    'mobile.required' => 'من فضلك ادخل رقم الهاتف ',
                    'email.required' => 'من فضلك ادخل البريد الالكتروني ',
                    'commercial_number.required' => 'من فضلك ادخل رقم السجل التجاري ',
                    'jihad_isdar.required' => 'من فضلك ادخل جهه الاصدار',
//                    'active_circle.required' => 'من فضلك ادخل دائرة النشاط ',
                    'isdar_date.required' => 'من فضلك حدد تاريخ الاصدار',
                    'isadarـduration.required' => 'من فضلك حدد الفترة الزمنية ',
                    'type.required' => 'من فضلك حدد التصنيف ',
                    'status.required' => 'من فضلك حدد حالة الشركة ',
                    'regions.required' => ' من فضلك حدد المنطقة  ',
                    'branches.required' => '  من فضلك حدد الفرع '
                ];

                $validator = Validator::make($data, $rules, $messages);
                if ($validator->fails()) {
                    return Redirect::back()->withInput()->withErrors($validator);
                }
                $company = new Companies();
                $company->name = $data['name'];
                $company->birthplace = $data['birthplace'];
                $company->nationality = $data['nationality'];
                $company->id_number = $data['id_number'];
                $company->place = $data['place'];
                $company->personal_number = $data['personal_number'];
                $company->trade_name = $data['trade_name'];
                $company->category = $data['category'];
                $company->sub_category = $data['sub_category'];
                $company->money_head = $data['money_head'];
                $company->bank_name = $data['bank_name'];
                $company->licenseـnumber = $data['licenseـnumber'];
                $company->tax_number = $data['tax_number'];
                $company->address = $data['address'];
                $company->mobile = $data['mobile'];
                $company->email = $data['email'];
                $company->commercial_number = $data['commercial_number'];
                $company->jihad_isdar = $data['jihad_isdar'];
//                $company->active_circle = $data['active_circle'];
                $company->isdar_date = $data['isdar_date'];
                $company->isadarـduration = $data['isadarـduration'];
                $company->type = $data['type'];
                $company->status = $data['status'];
                $company->region = $data['regions'];
                $company->branch = $data['branches'];

                $company->save();
                return $this->success_message('تم اضافة شركة جديدة بنجاح ');

            } catch (\Exception $e) {
                return $this->exception_message($e);
            }
        }
        return view('admin.companies.store', compact('categories', 'types', 'regions'));
    }

    public function update(Request $request, $id)
    {
        if (Auth::user()->type == 'admin') {
            $regions = Region::all();
        } elseif (Auth::user()->type == 'supervisor') {
            $regions = Region::where('id', Auth::user()->regions)->get();
        } else {
            $regions = null;
        }

        try {
            $company = Companies::findOrFail($id);
            $categories = CompanyCategories::where('status', '1')->where('parent_id', '0')->get();
            $types = CompanyType::where('status', '1')->get();

            if ($request->isMethod('post')) {
                $data = $request->all();
                $rules = [
                    'name' => 'required', 'birthplace' => 'required', 'nationality' => 'required',
                    'id_number' => 'required', 'place' => 'required', 'personal_number' => 'required', 'trade_name' => 'required',
                    'category' => 'required', 'money_head' => 'required', 'bank_name' => 'required',
                    'licenseـnumber' => 'required', 'tax_number' => 'required', 'address' => 'required', 'mobile' => 'required', 'email' => 'required',
                    'commercial_number' => 'required', 'jihad_isdar' => 'required',
                    'isdar_date' => 'required', 'isadarـduration' => 'required', 'type' => 'required', 'status' => 'required',
                    'regions' => 'required', 'branches' => 'required'
                ];
                $messages = [
                    'name.required' => ' من فضلك ادخل اسم الممثل القانوني  ',
                    'birthplace.required' => 'من فضلك ادخل مكان الميلاد',
                    'nationality.required' => 'من فضلك ادخل الجنسية ',
                    'id_number.required' => 'من فضلك ادخل الرقم الوطني ',
                    'place.required' => 'من فضلك ادخل محل الاقامة ',
                    'personal_number.required' => 'من فضلك ادخل رقم اثبات الشخصية ',
                    'trade_name.required' => 'من فضلك ادخل الاسم التجاري ',
                    'category.required' => 'من فضلك حدد نوع النشاط ',
                    'money_head.required' => 'من فضلك حدد راس المال ',
                    'bank_name.required' => 'من فضلك ادخل المصرف ',
                    'licenseـnumber.required' => 'من فضلك ادخل رقم الترخيص ',
                    'tax_number.required' => 'من فضلك ادخل الرقم الضريبي ',
                    'address.required' => 'من فضلك ادخل العنوان ',
                    'mobile.required' => 'من فضلك ادخل رقم الهاتف ',
                    'email.required' => 'من فضلك ادخل البريد الالكتروني ',
                    'commercial_number.required' => 'من فضلك ادخل رقم السجل التجاري ',
                    'jihad_isdar.required' => 'من فضلك ادخل جهه الاصدار',
//                    'active_circle.required' => 'من فضلك ادخل دائرة النشاط ',
                    'isdar_date.required' => 'من فضلك حدد تاريخ الاصدار',
                    'isadarـduration.required' => 'من فضلك حدد الفترة الزمنية ',
                    'type.required' => 'من فضلك حدد التصنيف ',
                    'status.required' => 'من فضلك حدد حالة الشركة ',
                    'regions.required' => ' من فضلك حدد المنطقة  ',
                    'branches.required' => '  من فضلك حدد الفرع '
                ];

                $validator = Validator::make($data, $rules, $messages);
                if ($validator->fails()) {
                    return Redirect::back()->withInput()->withErrors($validator);
                }
                $company->update([
                    "name" => $data['name'],

                    "birthplace" => $data['birthplace'],
                    "nationality" => $data['nationality'],
                    "id_number" => $data['id_number'],
                    "place" => $data['place'],
                    "personal_number" => $data['personal_number'],
                    "trade_name" => $data['trade_name'],
                    "sub_category" => $data['sub_category'],
                    "category" => $data['category'],
                    "money_head" => $data['money_head'],
                    "bank_name" => $data['bank_name'],
                    "licenseـnumber" => $data['licenseـnumber'],
                    "tax_number" => $data['tax_number'],
                    "address" => $data['address'],
                    "mobile" => $data['mobile'],
                    "email" => $data['email'],
                    "commercial_number" => $data['commercial_number'],
                    "jihad_isdar" => $data['jihad_isdar'],
//                    "active_circle" => $data['active_circle'],
                    "isdar_date" => $data['isdar_date'],
                    "isadarـduration" => $data['isadarـduration'],
                    "type" => $data['type'],
                    "status" => $data['status'],
                    'region' => $data['regions'],
                    'branch' => $data['branches']
                ]);
                return $this->success_message(' تم تعديل الشركة بنجاح  ');
            }
        } catch (\Exception $e) {
            return $this->exception_message($e);
        }
        return view('admin.companies.update', compact('company', 'categories', 'types', 'regions'));
    }

    public function destroy($id)
    {
        try {
            $company = Companies::findOrFail($id);
            $company->delete();
            return $this->success_message('تم حذف الشركة بنجاح');
        } catch (\Exception $e) {
            return $this->exception_message($e);
        }
    }

    public function transactions($id)
    {
        $transactions = FinanialTransaction::where('company_id', $id)->get();
        $company = Companies::findOrFail($id);
        return view('admin.companies.transactions', compact('transactions', 'company'));
    }

    // Market Confrim Status companies

    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    public function market_confirm(Request $request, $id)
    {
        $alldata = $request->all();
        $company = Companies::findOrFail($id);

        // توثيق الشركة
        $company->update([
            'market_confirm' => 1,
        ]);

        // إذا لم يكن هناك توثيق أول، نقوم بإضافة التاريخ الحالي كتاريخ التوثيق الأول
//        if (empty($company->first_market_confirm_date)) {
//            $company->update([
//                'first_market_confirm_date' => date('Y-m-d'), // إضافة التاريخ الحالي فقط
//            ]);
//        } else {
//            // إذا تم التوثيق من قبل، نحسب التاريخ الجديد بناءً على آخر توثيق
//            $lastConfirmDate = $company->new_market_confirm_date
//                ? Carbon::parse($company->new_market_confirm_date)
//                : Carbon::parse($company->first_market_confirm_date);
//
//            // الحصول على مدة القيد (عدد السنوات للتجديد)
//            $duration = $company->isadarـduration;
//
//            // حساب السنة الجديدة بإضافة عدد السنوات من مدة القيد
//            $newYear = $lastConfirmDate->copy()->addYears($duration)->year;
//
//            // الحفاظ على اليوم والشهر ثابتين من آخر توثيق
//            $fixedDayMonth = $lastConfirmDate->format('m-d');
//
//            // تكوين التاريخ الجديد مع السنة الجديدة واليوم والشهر الثابتين
//            $newMarketConfirmDate = Carbon::createFromFormat('Y-m-d', "$newYear-$fixedDayMonth");
//
//            // تحديث تاريخ التوثيق الجديد مع التأكد أن التنسيق يعرض التاريخ فقط
//            $company->update([
//                'new_market_confirm_date' => $newMarketConfirmDate->format('Y-m-d'), // التأكد من حفظ التاريخ فقط
//            ]);
//        }
        return $this->success_message('تم توثيق الشركة بنجاح');
    }

    // Money Confirmed
    //////////////////////////////////////////////////////////////////// Money Confirm //////////////////////////////////////
    public function money_confirm(Request $request, $id)
    {
        $company = Companies::findOrFail($id);
        try {
            if ($request->isMethod('post')) {
                $alldata = $request->all();
                $rules = [
                    'trans_number' => 'required|numeric',
                    'company_id' => 'required',
                    'trans_type' => 'required',
                    'trans_price' => 'required|numeric',
                    //'file' => 'required'
                ];
                $messages = [
                    'trans_type.required' => 'من فضلك حدد نوع المعاملة ',
                    'trans_number.numeric' => ' رقم الايصال يجب ان يكون رقم صحيح  ',
                    'company_id.required' => 'من فضلك حدد الشركة ',
                    'trans_number.required' => 'من فضلك ادخل رقم الايصال ',
                    'trans_price.required' => 'من فضلك ادخل قيمة الايصال ',
                    'trans_price.numeric' => ' قيمة الايصال يجب ان يكون رقم صحيح  ',
                    // 'file.required' => 'من فضلك ادخل مرفقات الايصال '
                ];
                $validator = Validator::make($alldata, $rules, $messages);
                if ($validator->fails()) {
                    return redirect()->back()->withInput()->withErrors($validator);
                }
                $filename = '';
                if ($request->hasFile('file')) {
                    $filename = $this->saveImage($request->file, public_path('assets/files/transaction_files'));
                }
                DB::beginTransaction();
                $transaction = new FinanialTransaction();
                $transaction->trans_number = $alldata['trans_number'];
                $transaction->trans_price = $alldata['trans_price'];
                $transaction->company_id = $alldata['company_id'];
                $transaction->trans_type = $alldata['trans_type'];
                $transaction->region = $company['region'];
                $transaction->branch = $company['branch'];
                $transaction->notes = $alldata['notes'];
                $transaction->file = $filename;
                $transaction->employe_id = Auth::user()->id;
                $transaction->save();
                ///// Update Company Status
                ///
                $company->update([
                    'money_confirm' => 1
                ]);
                // إذا لم يكن هناك توثيق أول، نقوم بإضافة التاريخ الحالي كتاريخ التوثيق الأول
                if (empty($company->first_market_confirm_date)) {
                    if (isset($alldata['special_date']) && $alldata['special_date'] != '') {
                        $company->update([
                            'first_market_confirm_date' => $alldata['special_date'], // إضافة التاريخ الحالي فقط
                        ]);
                    } else {
                        $company->update([
                            'first_market_confirm_date' => date('Y-m-d'), // إضافة التاريخ الحالي فقط
                        ]);
                    }
                } else {
                    // إذا تم التوثيق من قبل، نحسب التاريخ الجديد بناءً على آخر توثيق
                    $lastConfirmDate = $company->new_market_confirm_date
                        ? Carbon::parse($company->new_market_confirm_date)
                        : Carbon::parse($company->first_market_confirm_date);

                    // الحصول على مدة القيد (عدد السنوات للتجديد)
                    $duration = $company->isadarـduration;

                    // حساب السنة الجديدة بإضافة عدد السنوات من مدة القيد
                    $newYear = $lastConfirmDate->copy()->addYears($duration)->year;

                    // الحفاظ على اليوم والشهر ثابتين من آخر توثيق
                    $fixedDayMonth = $lastConfirmDate->format('m-d');

                    // تكوين التاريخ الجديد مع السنة الجديدة واليوم والشهر الثابتين
                    $newMarketConfirmDate = Carbon::createFromFormat('Y-m-d', "$newYear-$fixedDayMonth");

                    // تحديث تاريخ التوثيق الجديد مع التأكد أن التنسيق يعرض التاريخ فقط
                    $company->update([
                        'new_market_confirm_date' => $newMarketConfirmDate->format('Y-m-d'), // التأكد من حفظ التاريخ فقط
                    ]);
                }

                DB::commit();

                return $this->success_message(' تم اضافه المعامله بنجاح وتاكيد دفع الشركه  ');
            }

        } catch (\Exception $e) {
            return $this->exception_message($e);
        }
    }

    // UnConfirmed Companies With Market Team
    public function companies_unconfirmed()
    {
        $user = Auth::user();
        $companies = Companies::where('market_confirm', '0')->where('region', $user->regions)->where('branch', $user->branches)->orderby('id', 'desc')->get();
        $categories = CompanyCategories::where('status', '1')->get();
        $types = CompanyType::where('status', '1')->get();
        return view('admin.companies.index', compact('companies', 'categories', 'types'));

    }

    public function money_unconfirmed()
    {
        $user = Auth::user();
        $companies = Companies::where('money_confirm', '0')->where('market_confirm', '1')->where('region', $user->regions)->where('branch', $user->branches)->get();
        $categories = CompanyCategories::where('status', '1')->get();
        $types = CompanyType::where('status', '1')->get();
        return view('admin.companies.index', compact('companies', 'categories', 'types'));
    }

    public function certificate(Request $request, $id)
    {

        $company = Companies::with('subcategory','categorydata', 'companytype')->where('id', $id)->first()->toArray();
        $confirmationDate = $company['new_market_confirm_date'] ?? $company['first_market_confirm_date'];

        // Calculate the expiration date by adding `isadar_duration` to the confirmation date
        $expirationDate = Carbon::parse($confirmationDate)->addYears($company['isadarـduration']);
        // dd($company);
        try {
            if ($request->isMethod('post')) {
                $data = $request->all();
                dd($data);
            }
        } catch (\Exception $e) {
            return $this->exception_message($e);
        }

        $company_type = CompanyType::where('id',$company['type'])->first();
        $type_name = $company_type['name'];

      //  dd($company);
        return view('admin.companies.certificate', compact('company', 'expirationDate','type_name'));
    }


    ////////////////////////////////////////////////// Start Expire Companies ////////////
    public function expire_companies()
    {
        return view('admin.companies.expire');
    }

    public function getFilteredCompanies(Request $request)
    {
        $user = Auth::user();
        if ($user->type == 'admin') {
            $query = Companies::query();
        } elseif ($user->type == 'supervisor') {
            $query = Companies::where('region', $user->regions); // إزالة query() لأنه غير مطلوب
            if ($user->branches !== null) {
                $query->where('branch', $user->branches);
            }
        }


        // فلتر الشركات التي انتهت صلاحيتها بناءً على تواريخ التوثيق
        $query->where(function ($subQuery) use ($request) {
            // فلترة بناءً على السنة
            if ($request->filled('year')) {
                $subQuery->where(function ($query) use ($request) {
                    $query->whereYear(DB::raw('DATE_ADD(first_market_confirm_date, INTERVAL isadarـduration YEAR)'), $request->year)
                        ->whereNull('new_market_confirm_date');
                })->orWhere(function ($query) use ($request) {
                    $query->whereYear(DB::raw('DATE_ADD(new_market_confirm_date, INTERVAL isadarـduration YEAR)'), $request->year);
                });
            }

            // فلترة بناءً على الشهر
            if ($request->filled('month')) {
                $subQuery->where(function ($query) use ($request) {
                    $query->whereMonth(DB::raw('DATE_ADD(first_market_confirm_date, INTERVAL isadarـduration YEAR)'), $request->month)
                        ->whereNull('new_market_confirm_date');
                })->orWhere(function ($query) use ($request) {
                    $query->whereMonth(DB::raw('DATE_ADD(new_market_confirm_date, INTERVAL isadarـduration YEAR)'), $request->month);
                });
            }
        });

        // استرجاع الشركات المفلترة
        $companies = $query->orderBy('id', 'desc')->get();
        $expiredCount = $companies->count();

        // عرض النتيجة في صفحة
        return view('admin.companies.expire', compact('companies', 'expiredCount'));
    }

    public function MainFilter(Request $request)
    {

//        $query = Companies::query();

        $user = Auth::user();
        if ($user->type == 'admin') {
            $query = Companies::query();
        } elseif ($user->type == 'supervisor') {
            $query = Companies::where('region', $user->regions); // إزالة query() لأنه غير مطلوب
            if ($user->branches !== null) {
                $query->where('branch', $user->branches);
            }
        }
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        if ($request->filled('sub_category')) {
            $query->where('sub_category', $request->sub_category);
        }

        $companies = $query->orderBy('id', 'desc')->get();
        $categories = CompanyCategories::where('status', '1')->where('parent_id', '0')->get();
        $types = CompanyType::where('status', '1')->get();
        return view('admin.companies.index', compact('companies', 'categories', 'types'));

    }

    public function expiredCompanies()
    {
        $user = Auth::user();
        // بناء الاستعلام الأساسي بناءً على نوع المستخدم
        if ($user->type == 'admin') {
            $query = Companies::with('subcategory','categorydata', 'companytype');
        } elseif ($user->type == 'supervisor') {
            $query = Companies::with('subcategory','categorydata', 'companytype')->where('region', $user->regions);
            if ($user->branches !== null) {
                $query->where('branch', $user->branches);
            }
        }

        // فلترة الشركات التي انتهت صلاحيتها باستخدام تواريخ التوثيق ومدة الإيداع
        $query->where(function ($subQuery) {
            // حساب تاريخ انتهاء صلاحية الشركة بناءً على تاريخ التوثيق الأول أو الجديد
            $subQuery->where(function ($query) {
                $query->whereRaw('DATE_ADD(first_market_confirm_date, INTERVAL isadarـduration YEAR) < NOW()')
                    ->whereNull('new_market_confirm_date');
            })
                ->orWhere(function ($query) {
                    $query->whereRaw('DATE_ADD(new_market_confirm_date, INTERVAL isadarـduration YEAR) < NOW()');
                });
        });

        // جلب الشركات المفلترة والمنتهية الصلاحية
        $companies = $query->orderBy('id', 'desc')->get();

        // تحديث جميع الشركات المنتهية الصلاحية
        foreach ($companies as $company) {
            $company->update([
                'market_confirm' => 0,  // إرجاع توثيق السوق إلى 0
                'money_confirm' => 0,   // إرجاع تأكيد الدفع إلى 0
            ]);
        }

        // حساب عدد الشركات المنتهية
        $expiredCount = $companies->count();


        // عرض النتيجة في صفحة
        return view('admin.companies.expire', compact('companies', 'expiredCount'));
    }


    ///////////////  Expire In This ٣٠ days
    public function expiringCompaniesinlastmonth()
    {
        $user = Auth::user();

        // بناء الاستعلام الأساسي بناءً على نوع المستخدم
        if ($user->type == 'admin') {
            $query = Companies::with('subcategory','categorydata', 'companytype');
        } elseif ($user->type == 'supervisor') {
            $query = Companies::with('subcategory','categorydata', 'companytype')->where('region', $user->regions);
            if ($user->branches !== null) {
                $query->where('branch', $user->branches);
            }
        }

        // فلترة الشركات التي تنتهي صلاحيتها خلال الشهر الحالي
        $query->where(function ($subQuery) {
            // حساب تاريخ انتهاء صلاحية الشركة بناءً على تاريخ التوثيق الأول أو الجديد
            $subQuery->where(function ($query) {
                $query->whereRaw('DATE_ADD(first_market_confirm_date, INTERVAL isadarـduration YEAR) BETWEEN NOW() AND DATE_ADD(NOW(), INTERVAL 1 MONTH)')
                    ->whereNull('new_market_confirm_date');
            })
                ->orWhere(function ($query) {
                    $query->whereRaw('DATE_ADD(new_market_confirm_date, INTERVAL isadarـduration YEAR) BETWEEN NOW() AND DATE_ADD(NOW(), INTERVAL 1 MONTH)');
                });
        });

        // جلب الشركات المفلترة التي ستنتهي صلاحيتها خلال الشهر الحالي
        $companies = $query->orderBy('id', 'desc')->get();

        // حساب عدد الشركات التي ستنتهي صلاحيتها
        $expiringCount = $companies->count();

        // عرض النتيجة في صفحة
        return view('admin.companies.expiremonth', compact('companies', 'expiringCount'));
    }




    ///////////////////// User Store Company
    ///
    public function user_store(Request $request)
    {
        $regions = Region::all();
        $categories = CompanyCategories::where('status', '1')->where('parent_id', '0')->get();
        $types = CompanyType::where('status', '1')->get();
        if ($request->isMethod('post')) {
            try {
                $data = $request->all();
                $rules = [
                    'name' => 'required',

                    'birthplace' => 'required',
                    'nationality' => 'required',
                    'id_number' => 'required',
                    'place' => 'required',
                    'personal_number' => 'required',
                    'trade_name' => 'required',
                    'category' => 'required',
                    'money_head' => 'required',
                    'bank_name' => 'required',
                    'licenseـnumber' => 'required',
                    'tax_number' => 'required',
                    'address' => 'required',
                    'mobile' => 'required',
                    'email' => 'required',
                    'commercial_number' => 'required',
                    'jihad_isdar' => 'required',
//                    'active_circle' => 'required',
                    'isdar_date' => 'required',
                    'isadarـduration' => 'required',
                    'type' => 'required',
                    'status' => 'required',
                    'regions' => 'required',
                    'branches' => 'required',
                ];
                $messages = [
                    'name.required' => ' من فضلك ادخل اسم الممثل القانوني  ',

                    'birthplace.required' => 'من فضلك ادخل مكان الميلاد',
                    'nationality.required' => 'من فضلك ادخل الجنسية ',
                    'id_number.required' => 'من فضلك ادخل الرقم الوطني ',
                    'place.required' => 'من فضلك ادخل محل الاقامة ',
                    'personal_number.required' => 'من فضلك ادخل رقم اثبات الشخصية ',
                    'trade_name.required' => 'من فضلك ادخل الاسم التجاري ',
                    'category.required' => 'من فضلك حدد نوع النشاط ',
                    'money_head.required' => 'من فضلك حدد راس المال ',
                    'bank_name.required' => 'من فضلك ادخل المصرف ',
                    'licenseـnumber.required' => 'من فضلك ادخل رقم الترخيص ',
                    'tax_number.required' => 'من فضلك ادخل الرقم الضريبي ',
                    'address.required' => 'من فضلك ادخل العنوان ',
                    'mobile.required' => 'من فضلك ادخل رقم الهاتف ',
                    'email.required' => 'من فضلك ادخل البريد الالكتروني ',
                    'commercial_number.required' => 'من فضلك ادخل رقم السجل التجاري ',
                    'jihad_isdar.required' => 'من فضلك ادخل جهه الاصدار',
//                    'active_circle.required' => 'من فضلك ادخل دائرة النشاط ',
                    'isdar_date.required' => 'من فضلك حدد تاريخ الاصدار',
                    'isadarـduration.required' => 'من فضلك حدد الفترة الزمنية ',
                    'type.required' => 'من فضلك حدد التصنيف ',
                    'status.required' => 'من فضلك حدد حالة الشركة ',
                    'regions.required' => ' من فضلك حدد المنطقة  ',
                    'branches.required' => '  من فضلك حدد الفرع '
                ];

                $validator = Validator::make($data, $rules, $messages);
                if ($validator->fails()) {
                    return Redirect::back()->withInput()->withErrors($validator);
                }
                $company = new Companies();
                $company->name = $data['name'];
                $company->birthplace = $data['birthplace'];
                $company->nationality = $data['nationality'];
                $company->id_number = $data['id_number'];
                $company->place = $data['place'];
                $company->personal_number = $data['personal_number'];
                $company->trade_name = $data['trade_name'];
                $company->category = $data['category'];
                $company->sub_category = $data['sub_category'];
                $company->money_head = $data['money_head'];
                $company->bank_name = $data['bank_name'];
                $company->licenseـnumber = $data['licenseـnumber'];
                $company->tax_number = $data['tax_number'];
                $company->address = $data['address'];
                $company->mobile = $data['mobile'];
                $company->email = $data['email'];
                $company->commercial_number = $data['commercial_number'];
                $company->jihad_isdar = $data['jihad_isdar'];
//                $company->active_circle = $data['active_circle'];
                $company->isdar_date = $data['isdar_date'];
                $company->isadarـduration = $data['isadarـduration'];
                $company->type = $data['type'];
                $company->status = $data['status'];
                $company->region = $data['regions'];
                $company->branch = $data['branches'];
                $company->active_status = 0;

                $company->save();
                return $this->success_message(' تم اضافة الشركة بنجاح انتظر التفعيل من الادارة  ');

            } catch (\Exception $e) {
                return $this->exception_message($e);
            }
        }
        return view('admin.companies.store-company', compact('categories', 'types', 'regions'));
    }

    ////////////// Archive Company ///////////
    ///
    public function company_under_view()
    {
        $user = Auth::user();
        if ($user->type == 'admin') {
            $companies = Companies::with('subcategory','categorydata', 'companytype')->orderby('id', 'desc')->where('active_status', 0)->get();
            //  dd($companies);
        } elseif ($user->type == 'supervisor') {
            $query = Companies::where('region', $user->regions);
            // إذا كان لدى المشرف فرع معين، أضف شرط الفرع
            if ($user->branches !== null) {
                $query->where('branch', $user->branches);
            }
            $companies = $query->with('subcategory','categorydata', 'companytype')->where('active_status', 0)->get();
        } elseif ($user->type == 'money') {
            $companies = Companies::with('subcategory','categorydata', 'companytype')->where('active_status', 0)->where('market_confirm', '1')->orderby('id', 'desc')->where('region', $user->regions)->where('branch', $user->branches)->get();
        } elseif ($user->type == 'market') {
            $companies = Companies::with('subcategory','categorydata', 'companytype')->where('active_status', 0)->orderby('id', 'desc')->where('region', $user->regions)->where('branch', $user->branches)->get();
        }

        $categories = CompanyCategories::where('status', '1')->where('parent_id', '0')->get();
        $types = CompanyType::where('status', '1')->get();

        return view('admin.companies.company_under_view', compact('companies', 'categories', 'types'));
    }

    public function confirm_archive($id)
    {
        $company = Companies::findOrFail($id);
        $company->update([
            'active_status' => 1
        ]);
        return $this->success_message(' تم اضافة الشركة بنجاح  ');
    }
}
