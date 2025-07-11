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
            $companies = Companies::with('subcategory', 'categorydata', 'companytype')->where('active_status', 1)->orderby('id', 'desc')->get();
            //  dd($companies);
        } elseif ($user->type == 'supervisor') {
            $query = Companies::where('region', $user->regions)->where('active_status', 1);
            // إذا كان لدى المشرف فرع معين، أضف شرط الفرع
            if ($user->branches !== null) {
                $query->where('branch', $user->branches);
            }
            $companies = $query->with('subcategory', 'categorydata', 'companytype')->get();
        } elseif ($user->type == 'money') {
            $companies = Companies::with('subcategory', 'categorydata', 'companytype')->where('active_status', 1)->where('market_confirm', '1')->orderby('id', 'desc')->where('region', $user->regions)->where('branch', $user->branches)->get();
        } elseif ($user->type == 'market') {
            $companies = Companies::with('subcategory', 'categorydata', 'companytype')->where('active_status', 1)->orderby('id', 'desc')->where('region', $user->regions)->where('branch', $user->branches)->get();
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
                    // 'company_number' => 'required',
                    'name' => 'required|unique:companies,name',
                    'birthplace' => 'required',
                    'nationality' => 'required',
                    'id_number' => 'required',
                    'place' => 'required',
                    'personal_number' => 'required|unique:companies,personal_number',
                    'trade_name' => 'required|unique:companies,trade_name',
                    'category' => 'required',
                    'money_head' => 'required',
                    'bank_name' => 'required',
                    'licenseـnumber' => 'required|unique:companies,licenseـnumber',
                    'tax_number' => 'required|unique:companies,tax_number',
                    'address' => 'required',
                    'mobile' => 'required|unique:companies,mobile',
                    'email' => 'email',
                    'commercial_number' => 'required|unique:companies,commercial_number',
                    'jihad_isdar' => 'required',
                    //                    'active_circle' => 'required',
                    'isdar_date' => 'required',
                    'isadarـduration' => 'required',
                    'type' => 'required',
                    'status' => 'required',
                    'regions' => 'required',
                    'branches' => 'required',
                    'request_date' => 'required',
                    'request_type' => 'required',
                ];
                if ($request->hasFile('commercial_image')) {
                    $rules['commercial_image'] = 'mimes:jpg,png,jpeg,gif,svg,pdf,webp';
                }
                if ($request->hasFile('commercial_record')) {
                    $rules['commercial_record'] = 'mimes:jpg,png,jpeg,gif,svg,pdf,webp';
                }
                if ($request->hasFile('tourism_image')) {
                    $rules['tourism_image'] = 'mimes:jpg,png,jpeg,gif,svg,pdf,webp';
                }
                if ($request->hasFile('room_certificate')) {
                    $rules['room_certificate'] = 'mimes:jpg,png,jpeg,gif,svg,pdf,webp';
                }


                $messages = [
                    'commercial_image.mimes' => ' الملفات المسموح بها فقط تكون من نوع => jpg,png,jpeg,gif,svg,webp,pdf ',
                    'commercial_record.mimes' => ' الملفات المسموح بها فقط تكون من نوع => jpg,png,jpeg,gif,webp,svg,pdf ',
                    'tourism_image.mimes' => ' الملفات المسموح بها فقط تكون من نوع => jpg,png,jpeg,gif,webp,svg,pdf ',
                    'room_certificate.mimes' => ' الملفات المسموح بها فقط تكون من نوع => jpg,png,jpeg,gif,webp,svg,pdf ',
                    //'company_number.required' => ' من فضلك ادخل رقم القيد ',
                    'company_number.unique' => ' رقم القيد متواجد من قبل  ',
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
                    'email.email' => ' من فضلك ادخل البريد بشكل صحيح  ',
                    'commercial_number.required' => 'من فضلك ادخل رقم السجل التجاري ',
                    'jihad_isdar.required' => 'من فضلك ادخل جهه الاصدار',
                    //                    'active_circle.required' => 'من فضلك ادخل دائرة النشاط ',
                    'isdar_date.required' => 'من فضلك حدد تاريخ الاصدار',
                    'isadarـduration.required' => 'من فضلك حدد الفترة الزمنية ',
                    'type.required' => 'من فضلك حدد التصنيف ',
                    'status.required' => 'من فضلك حدد حالة الشركة ',
                    'regions.required' => ' من فضلك حدد المنطقة  ',
                    'branches.required' => '  من فضلك حدد الفرع ',
                    'name.unique' => 'اسم الممثل القانوني مستخدم بالفعل.',
                    'personal_number.unique' => 'رقم إثبات الشخصية مستخدم بالفعل.',
                    'trade_name.unique' => 'الاسم التجاري مستخدم بالفعل.',
                    'licenseـnumber.unique' => 'رقم الترخيص مستخدم بالفعل.',
                    'tax_number.unique' => 'الرقم الضريبي مستخدم بالفعل.',
                    'mobile.unique' => 'رقم الهاتف مستخدم بالفعل.',
                    'commercial_number.unique' => 'رقم السجل التجاري مستخدم بالفعل.',
                    'request_date.required' => 'من فضلك حدد تاريخ تقديم الطلب',
                    'request_type.required' => 'من فضلك حدد نوع الطلب',
                ];

                $validator = Validator::make($data, $rules, $messages);
                if ($validator->fails()) {
                    return Redirect::back()->withInput()->withErrors($validator);
                }
                $commercial_image = '';
                if ($request->hasFile('commercial_image')) {
                    $commercial_image = $this->SaveImage($request->file('commercial_image'), public_path('assets/files/company_register'));
                }
                $commercial_record = '';
                if ($request->hasFile('commercial_record')) {
                    $commercial_record = $this->SaveImage($request->file('commercial_record'), public_path('assets/files/company_register'));
                }
                $tourism_image = '';
                if ($request->hasFile('tourism_image')) {
                    $tourism_image = $this->SaveImage($request->file('tourism_image'), public_path('assets/files/company_register'));
                }
                $room_certificate = '';
                if ($request->hasFile('room_certificate')) {
                    $room_certificate = $this->SaveImage($request->file('room_certificate'), public_path('assets/files/company_register'));
                }
                $company = new Companies();
                $company->name = $data['name'];
                $company->company_number = $data['company_number'];
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
                $company->tourism_expire_date = $data['tourism_expire_date'];
                $company->commercial_image = $commercial_image;
                $company->commercial_record = $commercial_record;
                $company->tourism_image = $tourism_image;
                $company->room_certificate = $room_certificate;
                $company->request_date = $data['request_date'];
                $company->request_type = $data['request_type'];
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
                    'name' => 'required|unique:companies,name,' . $company->id,
                    'company_number' => 'nullable',
                    'birthplace' => 'required',
                    'nationality' => 'required',
                    'id_number' => 'required',
                    'place' => 'required',
                    'personal_number' => 'required|unique:companies,personal_number,' . $company->id,
                    'trade_name' => 'required|unique:companies,trade_name,' . $company->id,
                    'category' => 'required',
                    'money_head' => 'required',
                    'bank_name' => 'required',
                    'licenseـnumber' => 'required|unique:companies,licenseـnumber,' . $company->id,
                    'tax_number' => 'required|unique:companies,tax_number,' . $company->id,
                    'address' => 'required',
                    'mobile' => 'required|unique:companies,mobile,' . $company->id,
                    'email' => 'email',
                    'commercial_number' => 'required|unique:companies,commercial_number,' . $company->id,
                    'jihad_isdar' => 'required',
                    'isdar_date' => 'required',
                    'isadarـduration' => 'required',
                    'type' => 'required',
                    'status' => 'required',
                    'regions' => 'required',
                    'branches' => 'required',
                    'request_date' => 'required',
                    'request_type' => 'required',
                ];

                if ($request->hasFile('commercial_image')) {
                    $rules['commercial_image'] = 'mimes:jpg,png,jpeg,gif,svg,pdf,webp';
                }
                if ($request->hasFile('commercial_record')) {
                    $rules['commercial_record'] = 'mimes:jpg,png,jpeg,gif,svg,pdf,webp';
                }
                if ($request->hasFile('tourism_image')) {
                    $rules['tourism_image'] = 'mimes:jpg,png,jpeg,gif,svg,pdf,webp';
                }
                if ($request->hasFile('room_certificate')) {
                    $rules['room_certificate'] = 'mimes:jpg,png,jpeg,gif,svg,pdf,webp';
                }

                $messages = [
                    'name.required' => ' من فضلك ادخل اسم الممثل القانوني  ',
                    // 'company_number.unique' => ' رقم القيد متواجد من قبل  ',
                    // 'company_number.required' => ' من فضلك ادخل رقم القيد ',
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
                    'branches.required' => '  من فضلك حدد الفرع ',
                    'name.unique' => 'اسم الممثل القانوني مستخدم بالفعل.',
                    'personal_number.unique' => 'رقم إثبات الشخصية مستخدم بالفعل.',
                    'trade_name.unique' => 'الاسم التجاري مستخدم بالفعل.',
                    'licenseـnumber.unique' => 'رقم الترخيص مستخدم بالفعل.',
                    'tax_number.unique' => 'الرقم الضريبي مستخدم بالفعل.',
                    'mobile.unique' => 'رقم الهاتف مستخدم بالفعل.',
                    'commercial_number.unique' => 'رقم السجل التجاري مستخدم بالفعل.',
                    'commercial_image.required' => ' من فضلك ادخل صورة الرخصة التجارية ',
                    'commercial_image.mimes' => ' الملفات المسموح بها فقط تكون من نوع => jpg,png,jpeg,gif,svg,webp,pdf ',
                    'commercial_record.required' => ' من فضلك ادخل صورة السجل التجاري ',
                    'commercial_record.mimes' => ' الملفات المسموح بها فقط تكون من نوع => jpg,png,jpeg,gif,webp,svg,pdf ',
                    'tourism_image.required' => ' من فضلك ادخل صورة اذن السياحة ',
                    'tourism_image.mimes' => ' الملفات المسموح بها فقط تكون من نوع => jpg,png,jpeg,gif,webp,svg,pdf ',
                    'request_date.required' => 'من فضلك حدد تاريخ تقديم الطلب',
                    'request_type.required' => 'من فضلك حدد نوع الطلب',
                ];

                $validator = Validator::make($data, $rules, $messages);
                if ($validator->fails()) {
                    return Redirect::back()->withInput()->withErrors($validator);
                }
                if ($request->hasFile('commercial_image')) {
                    /////  Delete Old File
                    $commercial_old_image = public_path('assets/files/company_register/' . $company->commercial_image);
                    if (file_exists($commercial_old_image)) {
                        @unlink($commercial_old_image);
                    }
                    $commercial_image = $this->SaveImage($request->file('commercial_image'), public_path('assets/files/company_register'));

                    $company->update([
                        "commercial_image" => $commercial_image
                    ]);
                }
                if ($request->hasFile('commercial_record')) {
                    ##### Delete Old File
                    $commercial_old_record = public_path('assets/files/company_register/' . $company->commercial_record);
                    if (file_exists($commercial_old_record)) {
                        @unlink($commercial_old_record);
                    }
                    $commercial_record = $this->SaveImage($request->file('commercial_record'), public_path('assets/files/company_register'));
                    $company->update([
                        "commercial_record" => $commercial_record
                    ]);
                }
                if ($request->hasFile('tourism_image')) {
                    ##### Delete Old File
                    $tourism_old_image = public_path('assets/files/company_register/' . $company->tourism_image);
                    if (file_exists($tourism_old_image)) {
                        @unlink($tourism_old_image);
                    }
                    $tourism_image = $this->SaveImage($request->file('tourism_image'), public_path('assets/files/company_register'));
                    $company->update([
                        "tourism_image" => $tourism_image
                    ]);
                }
                if ($request->hasFile('room_certificate')) {
                    ##### Delete Old File
                    $room_old_certificate = public_path('assets/files/company_register/' . $company->room_certificate);
                    if (file_exists($room_old_certificate)) {
                        @unlink($room_old_certificate);
                    }
                    $room_certificate = $this->SaveImage($request->file('room_certificate'), public_path('assets/files/company_register'));
                    $company->update([
                        "room_certificate" => $room_certificate
                    ]);
                }
                $company->update([
                    "name" => $data['name'],
                    'company_number' => $data['company_number'],
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
                    'branch' => $data['branches'],
                    'tourism_expire_date' => $data['tourism_expire_date'],
                    'request_date' => $data['request_date'],
                    'request_type' => $data['request_type'],

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
        // $transactions = FinanialTransaction::where('company_id', $id)->get();
        $company = Companies::findOrFail($id);

        $user = Auth::user();
        if ($user->type == 'admin') {
            $transactions = FinanialTransaction::with('company_data', 'employe_data')->where('company_id', $id)->get();
        } elseif ($user->type == 'supervisor') {
            $query = FinanialTransaction::with('company_data', 'employe_data')->where('region', $user->regions)->where('company_id', $id);
            if ($user->branches !== null) {
                $query->where('branch', $user->branches);
            }
            $transactions = $query->get();
        } elseif ($user->type == 'money') {
            $transactions = FinanialTransaction::with('company_data', 'employe_data')->where('company_id', $id)->where('region', $user->regions)->where('branch', $user->branches)->get();
        }

        // تجميع البيانات حسب رقم الإيصال
        $transactions = $transactions->groupBy('trans_number')->map(function ($group) {
            $total_price = $group->sum('trans_price');
            $types = [
                'قيد جديد' => $group->where('trans_type', 'قيد جديد')->sum('trans_price'),
                'تجديد قيد' => $group->where('trans_type', 'تجديد قيد')->sum('trans_price'),
                'تصديق المستندات' => $group->where('trans_type', 'تصديق المستندات')->sum('trans_price'),
                'استخراج شهائد' => $group->where('trans_type', 'استخراج شهائد')->sum('trans_price'),
                'ايرادات اخري' => $group->where('trans_type', 'ايرادات اخري')->sum('trans_price'),
            ];

            return [
                'company_data' => $group->first()->company_data,
                'employe_data' => $group->first()->employe_data,
                'trans_number' => $group->first()->trans_number,
                'created_at' => $group->first()->created_at,
                'total_price' => $total_price,
                'types' => $types,
                'id' => $group->first()->id
            ];
        });

        $transactions_count = $transactions->count();
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
        if ($request->isMethod('post')) {
            try {

                $alldata = $request->all();
                $company_id = $alldata['company_id'];
                $company_data = Companies::findOrFail($company_id);
                // dd($company_data);
                $rules = [
                    'trans_number' => 'required|numeric', // التحقق من رقم الإيصال
                    'company_id' => 'required|exists:companies,id', // الشركة مطلوبة ويجب أن تكون موجودة في قاعدة البيانات
                    'trans_types' => 'required|array', // أنواع المعاملات يجب أن تكون مصفوفة
                    'trans_types.*' => 'required|string', // كل نوع من الأنواع داخل المصفوفة يجب أن يكون نصًا
                    'trans_prices' => 'required|array', // قيم المعاملات يجب أن تكون مصفوفة
                    'trans_prices.*' => 'required|numeric|min:0', // كل قيمة من القيم داخل المصفوفة يجب أن تكون رقمًا
                    'file' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048', // الملف اختياري ولكن يجب أن يكون بصيغة صحيحة وحجمه أقل من 2MB
                    'notes' => 'nullable|string|max:1000', // الملاحظات اختيارية ولكن يجب أن تكون نصًا محدود الطول
                ];
                $messages = [
                    'trans_number.required' => 'من فضلك أدخل رقم الإيصال.',
                    'trans_number.numeric' => 'رقم الإيصال يجب أن يكون رقماً صحيحاً.',
                    'company_id.required' => 'من فضلك حدد الشركة.',
                    'company_id.exists' => 'الشركة المحددة غير موجودة.',
                    'trans_types.required' => 'من فضلك حدد أنواع المعاملات.',
                    'trans_types.array' => 'أنواع المعاملات يجب أن تكون قائمة.',
                    'trans_types.*.required' => 'كل نوع معاملة مطلوب.',
                    'trans_types.*.string' => 'كل نوع معاملة يجب أن يكون نصاً.',
                    'trans_prices.required' => 'من فضلك أدخل قيم المعاملات.',
                    'trans_prices.array' => 'قيم المعاملات يجب أن تكون قائمة.',
                    'trans_prices.*.required' => 'كل قيمة معاملة مطلوبة.',
                    'trans_prices.*.numeric' => 'كل قيمة معاملة يجب أن تكون رقماً صحيحاً.',
                    'trans_prices.*.min' => 'كل قيمة معاملة يجب أن تكون على الأقل 0.',
                    'file.file' => 'الملف يجب أن يكون من نوع صحيح.',
                    'file.mimes' => 'الملف يجب أن يكون بصيغة: jpg, jpeg, png, pdf.',
                    'file.max' => 'حجم الملف يجب ألا يتجاوز 2 ميجابايت.',
                    'notes.string' => 'الملاحظات يجب أن تكون نصاً.',
                    'notes.max' => 'الملاحظات يجب ألا تتجاوز 1000 حرف.',
                ];
                $validator = Validator::make($alldata, $rules, $messages);
                if ($validator->fails()) {
                    return redirect()->back()->withInput()->withErrors($validator);
                }
                if ($request->hasFile('file')) {
                    $filename = $this->saveImage($request->file, public_path('assets/files/transaction_files'));
                }
                foreach ($request->trans_types as $index => $type) {
                    $transaction = new FinanialTransaction();
                    $transaction->trans_number = $alldata['trans_number'];
                    $transaction->trans_price = $request->trans_prices[$index];
                    $transaction->company_id = $alldata['company_id'];
                    $transaction->region = $company_data['region'];
                    $transaction->branch = $company_data['branch'];
                    $transaction->trans_type = $type;
                    $transaction->notes = $alldata['notes'];
                    $transaction->file = $filename ?? null;
                    $transaction->employe_id = Auth::user()->id;
                    $transaction->save();
                }
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
            } catch (\Exception $e) {
                return $this->exception_message($e);
            }
        }
        return view('admin.companies.money_confirm_company', compact('company'));
    }

    // UnConfirmed Companies With Market Team
    public function companies_unconfirmed()
    {
        $user = Auth::user();
        if ($user->type == 'admin') {
            $companies = Companies::with('subcategory', 'categorydata', 'companytype')->where('active_status', 1)->where('market_confirm', '0')->orderby('id', 'desc')->get();
            //  dd($companies);
        } elseif ($user->type == 'supervisor') {
            $query = Companies::where('region', $user->regions)->where('active_status', 1)->where('market_confirm', '0');
            // إذا كان لدى المشرف فرع معين، أضف شرط الفرع
            if ($user->branches !== null) {
                $query->where('branch', $user->branches);
            }
            $companies = $query->with('subcategory', 'categorydata', 'companytype')->get();
        } elseif ($user->type == 'market') {
            $companies = Companies::where('market_confirm', '0')->where('active_status', 1)->where('region', $user->regions)->where('branch', $user->branches)->orderby('id', 'desc')->get();
        }

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

        $company = Companies::with('subcategory', 'categorydata', 'companytype')->where('id', $id)->first()->toArray();
        $confirmationDate = $company['new_market_confirm_date'] ?? $company['first_market_confirm_date'];

        // Calculate the expiration date by adding `isadar_duration` to the confirmation date
        $duration = (int) $company['isadarـduration'];
        $expirationDate = Carbon::parse($confirmationDate)->addYears($duration);
        // dd($company);
        try {
            if ($request->isMethod('post')) {
                $data = $request->all();
                dd($data);
            }
        } catch (\Exception $e) {
            return $this->exception_message($e);
        }

        $company_type = CompanyType::where('id', $company['type'])->first();
        $type_name = $company_type['name'];

        //  dd($company);
        return view('admin.companies.certificate', compact('company', 'expirationDate', 'type_name'));
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
            $query = Companies::with('subcategory', 'categorydata', 'companytype');
        } elseif ($user->type == 'supervisor') {
            $query = Companies::with('subcategory', 'categorydata', 'companytype')->where('region', $user->regions);
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

        // $query->where('isdar_date', '<', now());
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

        if ($user->type == 'admin') {
            $query = Companies::with('subcategory', 'categorydata', 'companytype');
        } elseif ($user->type == 'supervisor') {
            $query = Companies::with('subcategory', 'categorydata', 'companytype')->where('region', $user->regions);
            if ($user->branches !== null) {
                $query->where('branch', $user->branches);
            }
        }

        // فلترة الشركات التي ستنتهي خلال 30 يوم
        $query->where(function ($subQuery) {
            $subQuery->where(function ($query) {
                $query->whereNull('new_market_confirm_date')
                    ->whereRaw('DATE_ADD(first_market_confirm_date, INTERVAL isadarـduration YEAR) BETWEEN NOW() AND DATE_ADD(NOW(), INTERVAL 30 DAY)');
            })
                ->orWhere(function ($query) {
                    $query->whereNotNull('new_market_confirm_date')
                        ->whereRaw('DATE_ADD(new_market_confirm_date, INTERVAL isadarـduration YEAR) BETWEEN NOW() AND DATE_ADD(NOW(), INTERVAL 30 DAY)');
                });
        });

        $companies = $query->orderBy('id', 'desc')->get();
        $expiringCount = $companies->count();

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
                // dd($data);
                $rules = [
                    'company_number' => 'nullable',
                    'name' => 'required|unique:companies,name',
                    'birthplace' => 'required',
                    'nationality' => 'required',
                    'id_number' => 'required|unique:companies,id_number',
                    'place' => 'required',
                    'personal_number' => 'required|unique:companies,personal_number',
                    'trade_name' => 'required|unique:companies,trade_name',
                    'category' => 'required',
                    'money_head' => 'required',
                    'bank_name' => 'required',
                    'licenseـnumber' => 'required|unique:companies,licenseـnumber',
                    'tax_number' => 'required|unique:companies,tax_number',
                    'address' => 'required',
                    'mobile' => 'required|unique:companies,mobile',
                    // 'email' => 'email',
                    'commercial_number' => 'required|unique:companies,commercial_number',
                    'jihad_isdar' => 'required',
                    //                    'active_circle' => 'required',
                    'isdar_date' => 'required',
                    'isadarـduration' => 'required',
                    'type' => 'required',
                    'status' => 'required',
                    'regions' => 'required',
                    'branches' => 'required',
                    'commercial_image' => 'nullable|mimes:jpg,png,jpeg,gif,svg,pdf,webp',
                    'commercial_record' => 'nullable|mimes:jpg,png,jpeg,gif,svg,pdf,webp',
                    'tourism_image' => 'nullable|mimes:jpg,png,jpeg,gif,svg,pdf,webp',
                    'room_certificate' => 'nullable|mimes:jpg,png,jpeg,gif,svg,pdf,webp',
                    'request_date' => 'required',
                    'request_type' => 'required',
                ];
                $messages = [
                    'commercial_image.mimes' => ' الملفات المسموح بها فقط تكون من نوع => jpg,png,jpeg,gif,svg,webp,pdf ',
                    'commercial_record.mimes' => ' الملفات المسموح بها فقط تكون من نوع => jpg,png,jpeg,gif,webp,svg,pdf ',
                    'tourism_image.mimes' => ' الملفات المسموح بها فقط تكون من نوع => jpg,png,jpeg,gif,webp,svg,pdf ',
                    'room_certificate.mimes' => ' الملفات المسموح بها فقط تكون من نوع => jpg,png,jpeg,gif,webp,svg,pdf ',
                    //  'company_number.required' => ' من فضلك ادخل رقم القيد ',
                    //  'company_number.unique' => ' رقم القيد متواجد من قبل  ',
                    'name.required' => ' من فضلك ادخل اسم الممثل القانوني  ',
                    'birthplace.required' => 'من فضلك ادخل مكان الميلاد',
                    'nationality.required' => 'من فضلك ادخل الجنسية ',
                    'id_number.required' => 'من فضلك ادخل الرقم الوطني ',
                    'id_number.unique' => ' الرقم الوطني موجود من قبل  ',
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
                    //'email.email' => ' من فضلك ادخل البريد بشكل صحيح  ',
                    'commercial_number.required' => 'من فضلك ادخل رقم السجل التجاري ',
                    'jihad_isdar.required' => 'من فضلك ادخل جهه الاصدار',
                    //                    'active_circle.required' => 'من فضلك ادخل دائرة النشاط ',
                    'isdar_date.required' => 'من فضلك حدد تاريخ الاصدار',
                    'isadarـduration.required' => 'من فضلك حدد الفترة الزمنية ',
                    'type.required' => 'من فضلك حدد التصنيف ',
                    'status.required' => 'من فضلك حدد حالة الشركة ',
                    'regions.required' => ' من فضلك حدد المنطقة  ',
                    'branches.required' => '  من فضلك حدد الفرع ',
                    'name.unique' => 'اسم الممثل القانوني مستخدم بالفعل.',
                    'personal_number.unique' => 'رقم إثبات الشخصية مستخدم بالفعل.',
                    'trade_name.unique' => 'الاسم التجاري مستخدم بالفعل.',
                    'licenseـnumber.unique' => 'رقم الترخيص مستخدم بالفعل.',
                    'tax_number.unique' => 'الرقم الضريبي مستخدم بالفعل.',
                    'mobile.unique' => 'رقم الهاتف مستخدم بالفعل.',
                    'commercial_number.unique' => 'رقم السجل التجاري مستخدم بالفعل.',
                    'request_date.required' => 'من فضلك حدد تاريخ تقديم الطلب',
                    'request_type.required' => 'من فضلك حدد نوع الطلب',
                ];

                $validator = Validator::make($data, $rules, $messages);
                if ($validator->fails()) {
                    return Redirect::back()->withInput()->withErrors($validator);
                }
                $commercial_image = '';
                if ($request->hasFile('commercial_image')) {
                    $commercial_image = $this->SaveImage($request->file('commercial_image'), public_path('assets/files/company_register'));
                }
                $commercial_record = '';
                if ($request->hasFile('commercial_record')) {
                    $commercial_record = $this->SaveImage($request->file('commercial_record'), public_path('assets/files/company_register'));
                }
                $tourism_image = '';
                if ($request->hasFile('tourism_image')) {
                    $tourism_image = $this->SaveImage($request->file('tourism_image'), public_path('assets/files/company_register'));
                }
                $room_certificate = '';
                if ($request->hasFile('room_certificate')) {
                    $room_certificate = $this->SaveImage($request->file('room_certificate'), public_path('assets/files/company_register'));
                }
                $company = new Companies();
                $company->company_number = $data['company_number'];
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
                $company->tourism_expire_date = $data['tourism_expire_date'];
                $company->commercial_image = $commercial_image;
                $company->commercial_record = $commercial_record;
                $company->tourism_image = $tourism_image;
                $company->room_certificate = $room_certificate;
                $company->active_status = 0;
                $company->request_date = $data['request_date'];
                $company->request_type = $data['request_type'];

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
            $companies = Companies::with('subcategory', 'categorydata', 'companytype')->orderby('id', 'desc')->where('active_status', 0)->get();
            //  dd($companies);
        } elseif ($user->type == 'supervisor') {
            $query = Companies::where('region', $user->regions);
            // إذا كان لدى المشرف فرع معين، أضف شرط الفرع
            if ($user->branches !== null) {
                $query->where('branch', $user->branches);
            }
            $companies = $query->with('subcategory', 'categorydata', 'companytype')->where('active_status', 0)->get();
        } elseif ($user->type == 'money') {
            $companies = Companies::with('subcategory', 'categorydata', 'companytype')->where('active_status', 0)->where('market_confirm', '1')->orderby('id', 'desc')->where('region', $user->regions)->where('branch', $user->branches)->get();
        } elseif ($user->type == 'market') {
            $companies = Companies::with('subcategory', 'categorydata', 'companytype')->where('active_status', 0)->orderby('id', 'desc')->where('region', $user->regions)->where('branch', $user->branches)->get();
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
