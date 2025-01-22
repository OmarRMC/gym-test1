<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\Company;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{

    /**
     * Register a new user and send email verification.
     * Create a new company with the same email as the user.
     * Assign the admin role to the user.
     * return the new user and an access token.
     * @param Request $request
     */
    function signUp(Request $request)
    {
        $validator = $request->validate([
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8',
        ]);

        $user = new User();
        $user->email = $validator['email'];
        $user->password = $validator['password'];
        $user->sendEmailValidation();
        $token = $user->getAuthToken();
        Company::createAndAttachCompany($user);
        Role::attachRole($user, Role::CODE_ADM_ROLE);
        return response()->json([
            'user' => new UserResource($user),
            'token' => $token,
        ]);
    }

    /*
    * It allows the validation of the user's registered email address
    * @param Request $request
    */
    public function validateEmail(Request $request)
    {
        $validate = $request->validate([
            'email' => 'required|string|email|max:255',
            'token' => 'required|string',
        ]);

        $user = User::where('email', $validate['email'])->where('email_verification_token', $validate['token'])->first();
        if (!$user) {
            return response()->json([
                'error' => 'Unauthorized'
            ], 401);
        }
        $now = Carbon::now();
        $user->email_verification_token = null;
        $user->email_verified_at = $now;
        $user->save();
        return redirect("/");
    }

    /*
    * Allows the user to log in.
    * If the credentials are valid, it returns an access token and the user's information.
    * Otherwise, it returns a 401 (Unauthorized) error.
    * @param Request $request
    */
    function login(Request $request)
    {
        $validator = $request->validate([
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:8',
        ]);
        if (!Auth::attempt($validator)) {
            return response()->json([
                'error' => 'Unauthorized'
            ], 401);
        }
        $user = Auth::user();
        $token = $user->getAuthToken();
        return response()->json([
            'user' => new UserResource($user),
            'token' => $token
        ], 200);
    }
}
