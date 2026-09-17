<?php

namespace App\Http\Controllers\Api;

use App\Helpers\LogActivity;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'login_id' => 'required',
            'password' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 422);
        }

        $user = User::where('login_id', $request->login_id)->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'लॉगिन आईडी या पासवर्ड गलत है'], 401);
        }

        $token = $user->createToken($request->header('User-Agent', 'mobile'))->plainTextToken;
        // Login itself happens before auth:sanctum resolves a user for the
        // request, so LogActivity's user_id fallback would otherwise see none.
        $request->setUserResolver(fn () => $user);
        LogActivity::addToLog('User Login (Mobile)', 'User', $user->user_id);

        return response()->json([
            'token' => $token,
            'force_password_reset' => (bool) $user->force_password_reset,
            'user' => $this->formatUser($user),
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'लॉग आउट किया गया']);
    }

    public function me(Request $request)
    {
        return response()->json(['user' => $this->formatUser($request->user())]);
    }

    private function formatUser(User $user): array
    {
        $user->loadMissing('office', 'role');
        return [
            'user_id' => $user->user_id,
            'login_id' => $user->login_id,
            'name' => $user->name,
            'designation' => $user->designation,
            'landline' => $user->landline,
            'mobile' => $user->mobile,
            'email' => $user->email,
            'user_role_id' => $user->user_role_id,
            'user_role_name' => $user->role->role_name ?? '',
            'office_id' => $user->office_id,
            'office_name' => $user->office->office_name ?? '',
            'emp_id' => $user->emp_id,
            'force_password_reset' => (bool) $user->force_password_reset,
        ];
    }
}
