<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\Message_Trait;
use App\Models\admin\CompanyType;
use App\Models\admin\Region;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RegionController extends Controller
{
    use Message_Trait;
    public function index()
    {
        $user = Auth::user();
        $regions = Region::all();
        if ($user->type == 'supervisor'){
            $regions = Region::where('id',$user->regions)->get();
        }
            return view('admin.regions.index',compact('regions'));
    }

    public function store(Request $request)
    {
        try {
            $alldata = $request->all();
            $rules = [
                'name' => 'required',
            ];
            $messages = [
                'name.required' => 'من فضلك ادخل الاسم ',
            ];
            $this->validate($request, $rules, $messages);
            $region = new Region();
            $region->name = $alldata['name'];

            $region->save();

            return $this->success_message('تم اضافة المنطقة بنجاح ');
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
            ];
            $messages = [
                'name.required' => 'من فضلك ادخل الاسم ',
            ];
            $this->validate($request, $rules, $messages);
            $region = Region::where('id', $alldata['region_id'])->first();
            $region->update([
                "name" => $alldata['name'],
            ]);
            return $this->success_message('تم تعديل  المنطقة بنجاح  ');
        } catch (\Exception $e) {
            return $this->exception_message($e);
        }
    }

    public function delete(Request $request,$id)
    {
        try {
            $category = Region::findOrFail($id);
            $category->delete();
            return $this->success_message('تم حذف  المنطقة بنجاح ');
        } catch (\Exception $e) {
            return $this->exception_message($e);
        }
    }
}
