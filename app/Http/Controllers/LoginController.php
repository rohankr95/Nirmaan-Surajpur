<?php

namespace App\Http\Controllers;

use App\Helpers\LogActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    public function index()
    {
        return view('login.index');
    }

    public function auth(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'login_id' => ['required' , function (string $attribute, mixed $value, \Closure $fail) use ($request) {
                $user = User::where('login_id', $request->login_id)->first();
                if (empty($user)) {
                    $fail("User with this $attribute not found.");
                }
            }],
            'password' => ['required' , function (string $attribute, mixed $value, \Closure $fail) use ($request) {
                $user = User::where('login_id', $request->login_id)->first();
                if($user)
                {
                    if (!Hash::check($request->password, $user->password))  {
                        $fail("Invalid $attribute");
                    }
                }
            }],
        ]);
        if ($validator->fails())
        {
            return back()->withErrors($validator)->withInput();
        }

        $user = User::where('login_id', $request->login_id)->first();
        if ($user) {
            if (Hash::check($request->password, $user->password)) {
                $request->session()->put('user_id', $user->user_id);
                $request->session()->put('login_id', $user->login_id);
                $request->session()->put('name', $user->name);
                $request->session()->put('mobile', $user->mobile);
                $request->session()->put('email', $user->email);
                $request->session()->put('user_role_id', $user->user_role_id);
                $request->session()->put('user_role', $user->role->role_name);
                $request->session()->put('office_id', $user->office_id);
                $request->session()->put('emp_id', $user->emp_id);
                $request->session()->put('office', $user->office->office_name??'');
                $request->session()->put('force_password_reset', (bool) $user->force_password_reset);
                LogActivity::addToLog('User Login','User',$user->user_id);

                if ($user->force_password_reset) {
                    return redirect()->route('change-password')
                        ->with('error', 'जारी रखने के लिए कृपया अपना अस्थायी पासवर्ड बदलें।');
                }

                return redirect('dashboard')->with('success',"सफलतापूर्वक लॉगिन किया गया");
            } else {
                return back()->withInput()->with('error', 'असफल : पासवर्ड गलत है !');
            }
        } else {
            return back()->withInput()->with('error', 'असफल: लॉगिन आईडी गलत है!');

        }
    }
    public function logout(Request $request)
    {
        if (session()->has('login_id')) {
            LogActivity::addToLog('User Logout', 'User', session()->get('user_id'));
            session()->flush();
        }
        return redirect()->route('login.index');
    }

    public function form_change_password()
    {
        return view('login.forget');
    }

    public function change_password(Request $request, $id)
    {
        // dd($request->all());
        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required',
            'confirm_password' => 'required',
        ]);
        $user = User::find($id);
        if(Hash::check($request->old_password, $user->password))
        {

            if( $request->new_password == $request->confirm_password)
            {
                $user->password = Hash::make($request->new_password);
                $user->force_password_reset = false;
                $user->save();
                LogActivity::addToLog('User Change Password','User',$user->user_id);
                session()->put('force_password_reset', false);
                return redirect('dashboard')->with('success','पासवर्ड बदल दिया गया है');
            }
            else
            {
                return back()->with('error','पासवर्ड मेल नहीं खा रहा है !');
            }

        }
        else
        {

            return back()->with('error','पुराना पासवर्ड गलत है !');
        }

    }

    public function update_profile(Request $request, $id)
    {
        // dd($request->all());
        $request->validate([
            'name' => 'required',
        ]);
        $user = User::find($id);
        $user->name = $request->name;
        $user->designation = $request->designation;
        $user->landline = $request->landline;
        $user->email = $request->email;
        $user->mobile = $request->mobile    ;
        $user->save();
        LogActivity::addToLog('User Change Profile','User',$user->user_id);
        return redirect()->back()->with('success','Profile बदल दिया गया है');

    }

    public function profile()
    {
        $user = User::where('user_id',session()->get('user_id'))->first();
        return view('login.profile',compact('user'));
    }
    public function change_pwd()
    {
        $user = User::where('user_id',session()->get('user_id'))->first();
        return view('login.change_password',compact('user'));
    }
}