<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\Message_Trait;
use App\Http\Traits\Upload_image;
use App\Models\admin\CompanyType;
use App\Models\admin\Region;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RegionController extends Controller
{
    use Message_Trait;
    use Upload_image;
    public function index()
    {
        $user = Auth::user();
        $regions = Region::all();
        if ($user->type == 'supervisor') {
            $regions = Region::where('id', $user->regions)->get();
        }
        return view('admin.regions.index', compact('regions'));
    }

    public function store(Request $request)
    {
        try {
            $alldata = $request->all();
            $rules = [
                'name' => 'required',
                'logo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ];
            $messages = [
                'name.required' => 'من فضلك ادخل الاسم ',
                'logo.required' => 'من فضلك ادخل الشعار ',
                'logo.image' => 'من فضلك ادخل صورة ',
                'logo.mimes' => 'من فضلك ادخل صورة بامتداد jpeg,png,jpg,gif,svg ',
                'logo.max' => 'من فضلك ادخل صورة اقل من 2 ميجا ',
            ];
            $this->validate($request, $rules, $messages);
            if ($request->hasFile('logo')) {
                $filename = $this->saveImage($request->file('logo'), public_path('assets/files/region_logo/'));
            }
            $region = new Region();
            $region->name = $alldata['name'];
            $region->logo = $filename;

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
            if($request->hasFile('logo')) {
                $rules['logo'] = 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048';
            }
            $messages = [
                'name.required' => 'من فضلك ادخل الاسم ',
                'logo.required' => 'من فضلك ادخل الشعار ',
                'logo.image' => 'من فضلك ادخل صورة ',
                'logo.mimes' => 'من فضلك ادخل صورة بامتداد jpeg,png,jpg,gif,svg ',
                'logo.max' => 'من فضلك ادخل صورة اقل من 2 ميجا ',
            ];
            $this->validate($request, $rules, $messages);
            $region = Region::where('id', $alldata['region_id'])->first();
            if ($request->hasFile('logo')) {
                ####### Delete Old Image ########
                if (file_exists(public_path('assets/files/region_logo/' . $region->logo))) {
                    @unlink(public_path('assets/files/region_logo/' . $region->logo));
                }
                $filename = $this->saveImage($request->file('logo'), public_path('assets/files/region_logo/'));
                $region->update([
                    "logo" => $filename,
                ]);
            }
            $region->update([
                "name" => $alldata['name'],
            ]);
            return $this->success_message('تم تعديل  المنطقة بنجاح  ');
        } catch (\Exception $e) {
            return $this->exception_message($e);
        }
    }

    public function delete(Request $request, $id)
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
