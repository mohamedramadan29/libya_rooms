<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\Message_Trait;
use App\Models\admin\Branch;
use App\Models\admin\Region;
use App\Models\admin\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SupervisorControllers extends Controller
{
    use Message_Trait;

    public function index()
    {
        $users = User::with('region','branch')->where('type','supervisor')->get();
        $regions = Region::all();
        return view('admin.users.supervisors.index',compact('users','regions'));
    }
    public function getBranches($region_id)
    {
        $branches = Branch::where('region_id', $region_id)->get();
        return response()->json($branches);
    }
    public function store(Request $request)
    {
        $alldata = $request->all();
        try {
            $rules = [
                'name' => 'required|regex:/^[\pL\s\-]+$/u',
                'email' => 'required|email|unique:users',
                'phone' => 'required|numeric|unique:users|digits_between:8,11',
                'password' => 'required|min:8',
                'regions'=>'required'
            ];
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
            ];
            $this->validate($request, $rules, $customeMessage);
            $user = new User();
            $user->type = 'supervisor';
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

    public function update(Request $request)
    {
        $alldata = $request->all();
        $user_id = $alldata['user_id'];
        $user = User::findOrFail($user_id);
        try {
            $rules = [
                'name' => 'required|regex:/^[\pL\s\-]+$/u',
                'email' => 'required|email|unique:users,email,' . $user_id,
                'phone' => 'required|numeric|digits_between:8,11|unique:users,phone,' . $user_id,
                'regions'=>'required',
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