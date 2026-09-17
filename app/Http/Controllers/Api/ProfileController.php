<?php

namespace App\Http\Controllers\Api;

use App\Helpers\LogActivity;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function update(Request $request)
    {
        $request->validate(['name' => 'required']);
        $user = $request->user();
        $user->name = $request->name;
        $user->designation = $request->designation;
        $user->landline = $request->landline;
        $user->email = $request->email;
        $user->mobile = $request->mobile;
        $user->save();
        LogActivity::addToLog('User Change Profile (Mobile)', 'User', $user->user_id);
        return response()->json(['message' => 'Profile बदल दिया गया है']);
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required',
            'confirm_password' => 'required',
        ]);
        $user = $request->user();
        if (!Hash::check($request->old_password, $user->password)) {
            return response()->json(['message' => 'पुराना पासवर्ड गलत है !'], 422);
        }
        if ($request->new_password !== $request->confirm_password) {
            return response()->json(['message' => 'पासवर्ड मेल नहीं खा रहा है !'], 422);
        }
        $user->password = Hash::make($request->new_password);
        $user->force_password_reset = false;
        $user->save();
        LogActivity::addToLog('User Change Password (Mobile)', 'User', $user->user_id);
        return response()->json(['message' => 'पासवर्ड बदल दिया गया है']);
    }
}
