<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\Message_Trait;
use App\Models\admin\Branch;
use App\Models\admin\Region;
use App\Models\admin\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class MarketManageController extends Controller
{
    use Message_Trait;
    public function index()
    {
        if (Auth::user()->type == 'admin') {
            $regions = Region::all();
            $users = User::where('type','market')->get();
        } elseif (Auth::user()->type == 'supervisor') {
            if(Auth::user()->branches !=null){
                $users = User::where('type','market')->where('regions',Auth::user()->regions)->where('branches',Auth::user()->branches)->get();
            }else{
                $users = User::where('type','market')->where('regions',Auth::user()->regions)->get();
            }
            $regions = Region::where('id', Auth::user()->regions)->get();
        } else {
            $regions = null;
        }
        //$regions = Region::all();
        return view('admin.users.market_manage.index',compact('users','regions'));
    }
    public function getBranches($region_id)
    {
        $branches = Branch::with('region','branch')->where('region_id', $region_id)->get();

        return response()->json($branches);
    }
    public function store(Request $request)
    {
        $alldata = $request->all();
        try {
            $rules = [
                'name' => 'required|regex:/^[\pL\s\-]+$/u',
                'email' => 'email|unique:users',
                'phone' => 'required|numeric|digits_between:8,11|unique:users,phone',
                'password' => 'required|min:8',
                'regions'=>'required',
                'branches'=>'required',
            ];
            $customeMessage = [
                'name.required' => 'من فضلك ادخل الأسم',
                'name.regex' => 'من فضلك ادخل الأسم بشكل صحيح ',
                'email.email' => 'من فضلك ادخل البريد الألكتروني بشكل صحيح',
                'email.unique' => 'هذا البريد الألكتروني موجود من قبل من فضلك ادخل بريد الكتروني جديد',
                'phone.required' => 'من فضلك ادخل رقم الهاتف',
                'phone.unique' => 'رقم الهاتف متواجد من قبلك من فضلك ادخل رقم هاتف اخر ',
                'phone.digits_between' => 'رقم الهاتف يجب ان يكون من 8 الي 11 رقم',
                'password.required' => 'من فضلك ادخل كلمه المرور ',
                'password.min' => 'كلمه المرور يجب ان تكون اكبر من 8 احرف ',
                'regions.required'=>' من فضلك حدد المنطقة  ',
                'branches.required'=>' من فضلك حدد الفرع  ',
            ];
            $this->validate($request, $rules, $customeMessage);
            $user = new User();
            $user->type = 'market';
            $user->name = $alldata['name'];
            $user->email = $alldata['email'];
            $user->phone = $alldata['phone'];
            $user->password = Hash::make($alldata['password']);
            $user->status = $alldata['status'];
            $user->regions = $alldata['regions'];
            $user->branches = $alldata['branches'];
            $user->save();
            return $this->success_message('تم اضافه المستخدم بنجاح');
        } catch (\Exception $e) {
            return $this->exception_message($e);
        }
    }

    public function update(Request $request,$id)
    {
        if (Auth::user()->type == 'admin') {
            $regions = Region::all();
            $users = User::where('type','market')->get();
        } elseif (Auth::user()->type == 'supervisor') {
            if(Auth::user()->branches !=null){
                $users = User::where('type','market')->where('regions',Auth::user()->regions)->where('branches',Auth::user()->branches)->get();
            }else{
                $users = User::where('type','market')->where('regions',Auth::user()->regions)->get();
            }
            $regions = Region::where('id', Auth::user()->regions)->get();
        } else {
            $regions = null;
        }

        $alldata = $request->all();
        $user = User::findOrFail($id);
        $user_id = $user['id'];
        if ($request->isMethod('post')){
            try {
                $rules = [
                    'name' => 'required|regex:/^[\pL\s\-]+$/u',
                    'email' => 'email|unique:users,email,' . $user_id,
                    'phone' => 'required|numeric|digits_between:8,11|unique:users,phone,' . $user_id,
                    'regions'=>'required',
                    'branches'=>'required'
                ];
                if ($alldata['password'] != '') {
                    $rules['password'] = 'required|min:8';
                }
                $customeMessage = [
                    'name.required' => 'من فضلك ادخل الأسم',
                    'name.regex' => 'من فضلك ادخل الأسم بشكل صحيح ',
                    'email.required' => 'من فضلك ادخل البريد الألكتروني',
                    'email.email' => 'من فضلك ادخل البريد الألكتروني بشكل صحيح',
                    'email.unique' => 'هذا البريد الألكتروني موجود من قبل من فضلك ادخل بريد الكتروني جديد',
                    'phone.required' => 'من فضلك ادخل رقم الهاتف',
                    'phone.unique' => 'رقم الهاتف متواجد من قبلك من فضلك ادخل رقم هاتف اخر ',
                    'phone.digits_between' => 'رقم الهاتف يجب ان يكون من 8 الي 11 رقم',
                    'password.required' => 'من فضلك ادخل كلمه المرور ',
                    'password.min' => 'كلمه المرور يجب ان تكون اكبر من 8 احرف ',
                    'regions.required'=>' من فضلك حدد المنطقة  ',
                    'branches.required'=>' من فضلك حدد الفرع  ',
                ];
                $this->validate($request, $rules, $customeMessage);
                $user->update([
                    "name" => $alldata['name'],
                    "email" => $alldata['email'],
                    "phone" => $alldata['phone'],
                    "status" => $alldata['status'],
                    'regions'=>$alldata['regions'],
                    'branches'=>$alldata['branches']
                ]);
                if ($alldata['password'] != '') {
                    $user->update([
                        'password' => Hash::make($alldata['password']),
                    ]);
                }

                return $this->success_message('تم تعديل المستخدم بنجاح');
            } catch (\Exception $e) {
                return $this->exception_message($e);
            }
        }
        return view('admin.users.market_manage.edit',compact('user','regions'));
    }

    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);

            $user->delete();
            return $this->success_message(' تم حذف المستخدم بنجاح  ');

        } catch (\Exception $e) {
            return $this->exception_message($e);
        }
    }
}
