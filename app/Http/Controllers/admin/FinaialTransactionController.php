<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\Message_Trait;
use App\Http\Traits\Upload_image;
use App\Models\admin\Companies;
use App\Models\admin\FinanialTransaction;
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
            $transactions =  FinanialTransaction::with('company_data', 'employe_data')->where('region',$user->regions)->where('branch',$user->branches)->get();
        }
        $transactions_count = $transactions->count();
        //dd($transactions);
        return view('admin.finanial_transaction.index', compact('transactions', 'transactions_count'));
    }

    public function store(Request $request)
    {
        $companies = Companies::all();
        try {
            if ($request->isMethod('post')) {
                $alldata = $request->all();
                $company_id = $alldata['company_id'];
                $company_data = Companies::findOrFail($company_id);
                $rules = [
                    'trans_number' => 'required|numeric',
                    'company_id' => 'required',
                    'trans_type' => 'required',
                    'trans_price' => 'required|numeric',
                    'file' => 'required'
                ];
                $messages = [
                    'trans_type.required' => 'من فضلك حدد نوع المعاملة ',
                    'trans_type.numeric' => ' رقم الايصال يجب ان يكون رقم صحيح  ',
                    'company_id.required' => 'من فضلك حدد الشركة ',
                    'trans_number.required' => 'من فضلك ادخل رقم الايصال ',
                    'trans_price.required' => 'من فضلك ادخل قيمة الايصال ',
                    'trans_price.numeric' => ' قيمة الايصال يجب ان يكون رقم صحيح  ',
                    'file.required' => 'من فضلك ادخل مرفقات الايصال '
                ];
                $validator = Validator::make($alldata, $rules, $messages);
                if ($validator->fails()) {
                    return redirect()->back()->withInput()->withErrors($validator);
                }
                if ($request->hasFile('file')) {
                    $filename = $this->saveImage($request->file, public_path('assets/files/transaction_files'));
                }
                $transaction = new FinanialTransaction();
                $transaction->trans_number = $alldata['trans_number'];
                $transaction->trans_price = $alldata['trans_price'];
                $transaction->company_id = $alldata['company_id'];
                $transaction->region = $company_data['region'];
                $transaction->branch = $company_data['branch'];
                $transaction->trans_type = $alldata['trans_type'];
                $transaction->notes = $alldata['notes'];
                $transaction->file = $filename;
                $transaction->employe_id = Auth::user()->id;
                $transaction->save();
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
        try {
            if ($request->isMethod('post')) {
                $alldata = $request->all();

                $rules = [
                    'trans_number' => 'required|numeric',
                    'company_id' => 'required',
                    'trans_type' => 'required',
                    'trans_price' => 'required|numeric'
                ];
                $messages = [
                    'trans_type.required' => 'من فضلك حدد نوع المعاملة ',
                    'trans_type.numeric' => ' رقم الايصال يجب ان يكون رقم صحيح  ',
                    'company_id.required' => 'من فضلك حدد الشركة ',
                    'trans_number.required' => 'من فضلك ادخل رقم الايصال ',
                    'trans_price.required' => 'من فضلك ادخل قيمة الايصال ',
                    'trans_price.numeric' => ' قيمة الايصال يجب ان يكون رقم صحيح  ',
                ];
                $validator = Validator::make($alldata, $rules, $messages);
                if ($validator->fails()) {
                    return redirect()->back()->withInput()->withErrors($validator);
                }
                if ($request->hasFile('file')) {
                    $filename = $this->saveImage($request->file, public_path('assets/files/transaction_files'));
                    // delete old file
                    if ($transaction['file'] != '') {
                        unlink(public_path('assets/files/transaction_files/' . $transaction['file']));
                    }
                    $transaction->update(["file" => $filename,]);
                }
                $transaction->update([
                    "trans_number" => $alldata['trans_number'],
                    "trans_price" => $alldata['trans_price'],
                    "company_id" => $alldata['company_id'],
                    "trans_type" => $alldata['trans_type'],
                    "notes" => $alldata['notes'],
                    "employe_id" => Auth::user()->id,
                ]);

                return $this->success_message('تم تعديل المعاملة بنجاح  ');

            }
        } catch (\Exception $e) {
            return $this->exception_message($e);
        }
        return view('admin.finanial_transaction.update', compact('transaction', 'companies'));
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
