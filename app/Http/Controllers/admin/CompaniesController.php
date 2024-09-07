<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\Message_Trait;
use App\Models\admin\Companies;
use App\Models\admin\CompanyCategories;
use App\Models\admin\CompanyType;
use App\Models\admin\FinanialTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class CompaniesController extends Controller
{
    use Message_Trait;

    public function index()
    {
        $companies = Companies::orderby('id','desc')->get();
        $categories = CompanyCategories::where('status', '1')->get();
        $types = CompanyType::where('status', '1')->get();
        return view('admin.companies.index', compact('companies', 'categories', 'types'));
    }

    public function store(Request $request)
    {
        $categories = CompanyCategories::where('status', '1')->get();
        $types = CompanyType::where('status', '1')->get();
        if ($request->isMethod('post')) {
            try {
                $data = $request->all();
                $rules = [
                    'name' => 'required',
                    'birthdate' => 'required',
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
                    'active_circle' => 'required',
                    'isdar_date' => 'required',
                    'isadarـduration' => 'required',
                    'type' => 'required',
                    'status' => 'required',
                ];
                $messages = [
                    'name.required' => ' من فضلك ادخل اسم الممثل القانوني  ',
                    'birthdate.required' => 'من فضلك ادخل تاريخ الميلاد ',
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
                    'active_circle.required' => 'من فضلك ادخل دائرة النشاط ',
                    'isdar_date.required' => 'من فضلك حدد تاريخ الاصدار',
                    'isadarـduration.required' => 'من فضلك حدد الفترة الزمنية ',
                    'type.required' => 'من فضلك حدد التصنيف ',
                    'status.required' => 'من فضلك حدد حالة الشركة ',
                ];

                $validator = Validator::make($data, $rules, $messages);
                if ($validator->fails()) {
                    return Redirect::back()->withInput()->withErrors($validator);
                }

                $company = new Companies();
                $company->name = $data['name'];
                $company->birthdate = $data['birthdate'];
                $company->birthplace = $data['birthplace'];
                $company->nationality = $data['nationality'];
                $company->id_number = $data['id_number'];
                $company->place = $data['place'];
                $company->personal_number = $data['personal_number'];
                $company->trade_name = $data['trade_name'];
                $company->category = $data['category'];
                $company->money_head = $data['money_head'];
                $company->bank_name = $data['bank_name'];
                $company->licenseـnumber = $data['licenseـnumber'];
                $company->tax_number = $data['tax_number'];
                $company->address = $data['address'];
                $company->mobile = $data['mobile'];
                $company->email = $data['email'];
                $company->commercial_number = $data['commercial_number'];
                $company->jihad_isdar = $data['jihad_isdar'];
                $company->active_circle = $data['active_circle'];
                $company->isdar_date = $data['isdar_date'];
                $company->isadarـduration = $data['isadarـduration'];
                $company->type = $data['type'];
                $company->status = $data['status'];

                $company->save();
                return $this->success_message('تم اضافة شركة جديدة بنجاح ');

            } catch (\Exception $e) {
                return $this->exception_message($e);
            }
        }

        return view('admin.companies.store', compact('categories', 'types'));

    }

    public function update(Request $request, $id)
    {
        try {
            $company = Companies::findOrFail($id);
            $categories = CompanyCategories::where('status', '1')->get();
            $types = CompanyType::where('status', '1')->get();

            if ($request->isMethod('post')) {
                $data = $request->all();
                $rules = [
                    'name' => 'required', 'birthdate' => 'required', 'birthplace' => 'required', 'nationality' => 'required',
                    'id_number' => 'required', 'place' => 'required', 'personal_number' => 'required', 'trade_name' => 'required',
                    'category' => 'required', 'money_head' => 'required', 'bank_name' => 'required',
                    'licenseـnumber' => 'required', 'tax_number' => 'required', 'address' => 'required', 'mobile' => 'required', 'email' => 'required',
                    'commercial_number' => 'required', 'jihad_isdar' => 'required', 'active_circle' => 'required',
                    'isdar_date' => 'required', 'isadarـduration' => 'required', 'type' => 'required', 'status' => 'required',
                ];
                $messages = [
                    'name.required' => ' من فضلك ادخل اسم الممثل القانوني  ',
                    'birthdate.required' => 'من فضلك ادخل تاريخ الميلاد ',
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
                    'active_circle.required' => 'من فضلك ادخل دائرة النشاط ',
                    'isdar_date.required' => 'من فضلك حدد تاريخ الاصدار',
                    'isadarـduration.required' => 'من فضلك حدد الفترة الزمنية ',
                    'type.required' => 'من فضلك حدد التصنيف ',
                    'status.required' => 'من فضلك حدد حالة الشركة ',
                ];

                $validator = Validator::make($data, $rules, $messages);
                if ($validator->fails()) {
                    return Redirect::back()->withInput()->withErrors($validator);
                }
                $company->update([
                    "name" => $data['name'],
                    "birthdate" => $data['birthdate'],
                    "birthplace" => $data['birthplace'],
                    "nationality" => $data['nationality'],
                    "id_number" => $data['id_number'],
                    "place" => $data['place'],
                    "personal_number" => $data['personal_number'],
                    "trade_name" => $data['trade_name'],
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
                    "active_circle" => $data['active_circle'],
                    "isdar_date" => $data['isdar_date'],
                    "isadarـduration" => $data['isadarـduration'],
                    "type" => $data['type'],
                    "status" => $data['status'],
                ]);
                return $this->success_message(' تم تعديل الشركة بنجاح  ');
            }
        } catch (\Exception $e) {
            return $this->exception_message($e);
        }
        return view('admin.companies.update', compact('company', 'categories', 'types'));
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
    public function market_confirm(Request $request,$id)
    {
        $alldata = $request->all();
        $company = Companies::findOrFail($id);
        $company->update(['market_confirm' => 1]);
        return $this->success_message(' تم توثيق الشركه بنجاح  ');
        //$companies = explode(',', $alldata['companies']);
        // dd($companies);

//        if (!empty($alldata['companies'])) { // التحقق مما إذا كانت القائمة غير فارغة
//            foreach ($companies as $company) {
//                // التحقق مما إذا كانت الشحنة موجودة قبل تحديثها
//                $company = Companies::find($company);
//                if ($company) {
//                    // استخدم الدالة update وقم بتمرير مصفوفة بالعمود وقيمته الجديدة
//                    $company->update(['market_confirm' => 1]);
//                }
//            }
//            return $this->success_message(' تم التاكيد علي الشركات بنجاح  ');
//        } else {
//            return $this->Error_message('من فضلك حدد الشحنات أولاً');
//        }
    }

    // Money Confirmed
    public function money_confirm(Request $request,$id)
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
                    'trans_type.numeric' => ' رقم الايصال يجب ان يكون رقم صحيح  ',
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
                $transaction->notes = $alldata['notes'];
                $transaction->file = $filename;
                $transaction->employe_id = Auth::user()->id;
                $transaction->save();
                ///// Update Company Status
                ///
              $company->update([
                  'money_confirm'=>1
              ]);
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
        $companies = Companies::where('market_confirm', '0')->orderby('id','desc')->get();
        $categories = CompanyCategories::where('status', '1')->get();
        $types = CompanyType::where('status', '1')->get();
        return view('admin.companies.index', compact('companies', 'categories', 'types'));

    }

    public function money_unconfirmed()
    {
        $companies = Companies::where('money_confirm', '0')->get();
        $categories = CompanyCategories::where('status', '1')->get();
        $types = CompanyType::where('status', '1')->get();
        return view('admin.companies.index', compact('companies', 'categories', 'types'));
    }

    public function certificate(Request $request, $id)
    {

        $company = Companies::with('category')->where('id',$id)->first()->toArray();
       // dd($company);
        try {
            if ($request->isMethod('post')) {
                $data = $request->all();
                dd($data);
            }
        } catch (\Exception $e) {
            return $this->exception_message($e);
        }

        return view('admin.companies.certificate',compact('company'));
    }


}
