<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\Message_Trait;
use App\Http\Traits\Upload_image;
use App\Models\admin\Companies;
use App\Models\admin\FinanialTransaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class FinaialTransactionController extends Controller
{
    use Message_Trait;
    use Upload_image;

    public function index()
    {
        $user = Auth::user();
        if ($user->type == 'admin') {
            $transactions = FinanialTransaction::with('company_data', 'employe_data')->get();
        } elseif ($user->type == 'supervisor') {
            $query = FinanialTransaction::with('company_data', 'employe_data')->where('region', $user->regions);
            if ($user->branches !== null) {
                $query->where('branch', $user->branches);
            }
            $transactions = $query->get();
        } elseif ($user->type == 'money') {
            $transactions = FinanialTransaction::with('company_data', 'employe_data')->where('region', $user->regions)->where('branch', $user->branches)->get();
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

        return view('admin.finanial_transaction.index', compact('transactions', 'transactions_count'));
    }


    public function store(Request $request)
    {
        $user = Auth::user();
      //  $companies = Companies::all();
      $companies = Companies::with('subcategory', 'categorydata', 'companytype')
      ->where('region', $user->regions)
          ->where('branch', $user->branches)
          ->orderby('id', 'desc')
          ->get();
        try {
            if ($request->isMethod('post')) {
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
                return $this->success_message('تم اضافة معاملة جديدة بنجاح ');
            }

        } catch (\Exception $e) {
            return $this->exception_message($e);
        }

        return view('admin.finanial_transaction.store', compact('companies'));
    }

    public function update(Request $request, $id)
    {
        $transaction = FinanialTransaction::findOrFail($id);
        $companies = Companies::all();
        $trans_number = $transaction->trans_number;

        $transactions = FinanialTransaction::where('trans_number', $trans_number)->get();

        if ($request->isMethod('post')) {
            try {
                $alldata = $request->all();
                $company_id = $alldata['company_id'];
                $company_data = Companies::findOrFail($company_id);
                $mainTransaction = FinanialTransaction::findOrFail($id);
                $trans_number = $mainTransaction->trans_number;
                $rules = [
                    'company_id' => 'required',
                ];

                $messages = [
                    'company_id.required' => 'من فضلك حدد الشركة ',
                ];

                $validator = Validator::make($request->all(), $rules, $messages);
                if ($validator->fails()) {
                    return redirect()->back()->withInput()->withErrors($validator);
                }

                // حفظ الملف
                $filename = $mainTransaction->file;
                if ($request->hasFile('file')) {
                    $filename = $this->saveImage($request->file('file'), public_path('assets/files/transaction_files'));
                    if ($mainTransaction->file && file_exists(public_path('assets/files/transaction_files/' . $mainTransaction->file))) {
                        unlink(public_path('assets/files/transaction_files/' . $mainTransaction->file));
                    }
                }
                FinanialTransaction::where('trans_number', $trans_number)->delete();
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

                return redirect()->route('company.transactions', ['id' => $company_id])->with('success', 'تم تعديل معاملات الشركة بنجاح');
            } catch (\Exception $e) {
                return back()->with('error', 'حدث خطأ: ' . $e->getMessage());
            }
        }

        return view('admin.finanial_transaction.update', compact('transaction', 'transactions', 'companies'));
    }

    public function destroy($id)
    {
        try {
            $transaction = FinanialTransaction::findOrFail($id);
            $transaction->delete();
            return $this->success_message('تم حذف المعاملة بنجاح ');
        } catch (\Exception $e) {
            return $this->exception_message($e);
        }
    }

    public function TransactionFilter(Request $request)
    {
        $query = FinanialTransaction::with('company_data', 'employe_data');

        // التحقق من وجود تواريخ محددة في الطلب
        if ($request->filled('from_date') && $request->filled('to_date')) {
            // فلترة المعاملات التي تم إنشاؤها بين التاريخين باستخدام created_at
            $query->whereBetween('created_at', [$request->from_date, $request->to_date]);
        } elseif ($request->filled('from_date')) {
            // إذا كان فقط "من تاريخ" موجودًا
            $query->whereDate('created_at', '>=', $request->from_date);
        } elseif ($request->filled('to_date')) {
            // إذا كان فقط "إلى تاريخ" موجودًا
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        // جلب المعاملات المفلترة
        $transactions = $query->orderBy('created_at', 'desc')->get();
        $transactions_count = $transactions->count();
        // إرسال النتائج إلى العرض
        return view('admin.finanial_transaction.index', compact('transactions', 'transactions_count'));
    }
}
