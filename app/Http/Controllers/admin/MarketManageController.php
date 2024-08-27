<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\Message_Trait;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class MarketManageController extends Controller
{
    use Message_Trait;
    public function index()
    {
        $users = User::where('type','market')->get();
        return view('admin.users.market_manage.index',compact('users'));
    }
    public function store(Request $request)
    {
        $alldata = $request->all();
        try {
            $rules = [
                'name' => 'required|regex:/^[\pL\s\-]+$/u',
                'email' => 'required|email|unique:users',
                'phone' => 'required|numeric|unique:users|digits_between:8,11',
                'password' => 'required|min:8'
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
                'password.min' => 'كلمه المرور يجب ان تكون اكبر من 8 احرف '
            ];
            $this->validate($request, $rules, $customeMessage);
            $user = new User();
            $user->type = 'market';
            $user->name = $alldata['name'];
            $user->email = $alldata['email'];
            $user->phone = $alldata['phone'];
            $user->password = Hash::make($alldata['password']);
            $user->status = $alldata['status'];
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
                'password.min' => 'كلمه المرور يجب ان تكون اكبر من 8 احرف '
            ];
            $this->validate($request, $rules, $customeMessage);
            $user->update([
                "name" => $alldata['name'],
                "email" => $alldata['email'],
                "phone" => $alldata['phone'],
                "status" => $alldata['status'],
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
