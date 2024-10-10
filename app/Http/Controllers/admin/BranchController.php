<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\Message_Trait;
use App\Models\admin\Branch;
use App\Models\admin\Region;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BranchController extends Controller
{
    use Message_Trait;

    public function index($region_id)
    {
        $user = Auth::user();
        $branches = Branch::where('region_id', $region_id)->get();
        if ($user->type == 'supervisor') {
            if ($region_id !=$user->regions){
                abort(404);
            }
            $branches = Branch::where('region_id', $region_id)->where('region_id', $user->regions)->get();
        }
        $region = Region::findOrFail($region_id);
        return view('admin.branches.index', compact('branches', 'region'));
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
            $branch = new Branch();
            $branch->region_id = $alldata['region_id'];
            $branch->name = $alldata['name'];

            $branch->save();

            return $this->success_message('تم اضافة  الفرع  بنجاح ');
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
            $branch = Branch::where('id', $alldata['branch_id'])->first();
            $branch->update([
                "name" => $alldata['name'],
            ]);
            return $this->success_message('تم تعديل   الفرع بنجاح  ');
        } catch (\Exception $e) {
            return $this->exception_message($e);
        }
    }

    public function delete(Request $request, $id)
    {
        try {
            $branch = Branch::findOrFail($id);
            $branch->delete();
            return $this->success_message('تم حذف  الفرع  بنجاح ');
        } catch (\Exception $e) {
            return $this->exception_message($e);
        }
    }
}
