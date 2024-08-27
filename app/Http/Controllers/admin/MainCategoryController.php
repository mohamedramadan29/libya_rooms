<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\Message_Trait;
use App\Models\admin\CompanyCategories;
use Illuminate\Http\Request;

class MainCategoryController extends Controller
{
    use Message_Trait;

    public function index()
    {
        $main_categories = CompanyCategories::with('getparent')->get();
        // dd($main_categories);
        return view('admin.company_category.main_category.index', compact('main_categories'));
    }

    public function add(Request $request)
    {
        try {
            $alldata = $request->all();
            $rules = [
                'name' => 'required',
                'status' => 'required',
                'parent_id' => 'required',
            ];
            $messages = [
                'name.required' => 'من فضلك ادخل الاسم ',
                'status.required' => ' من فضلك حدد الحالة ',
                'parent_id.required' => 'من فضلك حدد نوع التصنيف'
            ];
            $this->validate($request, $rules, $messages);
            $main_category = new CompanyCategories();
            $main_category->number = $alldata['number'];
            $main_category->parent_id = $alldata['parent_id'];
            $main_category->name = $alldata['name'];
            $main_category->status = $alldata['status'];

            $main_category->save();

            return $this->success_message('تم اضافة التصنيف بنجاح ');
        } catch (\Exception $e) {
            return $this->exception_message($e);
        }
    }

    public function edit(Request $request)
    {
        try {
            $alldata = $request->all();
            $rules = [
                'name' => 'required',
                'status' => 'required',
                'parent_id' => 'required',
            ];
            $messages = [
                'name.required' => 'من فضلك ادخل الاسم ',
                'status.required' => ' من فضلك حدد الحالة ',
                'parent_id.required' => 'من فضلك حدد نوع التصنيف'
            ];
            $this->validate($request, $rules, $messages);
            $main_category = CompanyCategories::where('id', $alldata['cat_id'])->first();
            $main_category->update([
                "number" => $alldata['number'],
                "parent_id" => $alldata['parent_id'],
                "name" => $alldata['name'],
                "status" => $alldata['status'],
            ]);
            return $this->success_message('تم اضافة التصنيف بنجاح ');
        } catch (\Exception $e) {
            return $this->exception_message($e);
        }
    }

    public function delete(Request $request, $id)
    {
        try {
            $category = CompanyCategories::findOrFail($id);
            $category->delete();
            return $this->success_message('تم حذف التصنيف بنجاح ');
        } catch (\Exception $e) {
            return $this->exception_message($e);
        }

    }
}
