<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\Message_Trait;
use App\Models\admin\CompanyType;
use Illuminate\Http\Request;

class CompanyTypeController extends Controller
{

    use Message_Trait;
    public function index()
    {
        $company_typies = CompanyType::all();
        return view('admin.company_type.index',compact('company_typies'));
    }

    public function store(Request $request)
    {
        try {
            $alldata = $request->all();
            $rules = [
                'name' => 'required',
                'status' => 'required',
            ];
            $messages = [
                'name.required' => 'من فضلك ادخل الاسم ',
                'status.required' => ' من فضلك حدد الحالة ',
            ];
            $this->validate($request, $rules, $messages);
            $type = new CompanyType();
            $type->name = $alldata['name'];
            $type->status = $alldata['status'];

            $type->save();

            return $this->success_message('تم اضافة نوع جديد بنجاح ');
        } catch (\Exception $e) {
            return $this->exception_message($e);
        }
    }

    public function update(Request $request)
    {
        try {
            $alldata = $request->all();
            $rules = [
                'name' => 'required',
                'status' => 'required',
            ];
            $messages = [
                'name.required' => 'من فضلك ادخل الاسم ',
                'status.required' => ' من فضلك حدد الحالة ',
            ];
            $this->validate($request, $rules, $messages);
            $type = CompanyType::where('id', $alldata['type_id'])->first();
            $type->update([
                "name" => $alldata['name'],
                "status" => $alldata['status'],
            ]);
            return $this->success_message('تم تعديل النوع بنجاح  ');
        } catch (\Exception $e) {
            return $this->exception_message($e);
        }
    }

    public function destroy(Request $request,$id)
    {
        try {
            $category = CompanyType::findOrFail($id);
            $category->delete();
            return $this->success_message('تم حذف النوع بنجاح ');
        } catch (\Exception $e) {
            return $this->exception_message($e);
        }
    }
}
