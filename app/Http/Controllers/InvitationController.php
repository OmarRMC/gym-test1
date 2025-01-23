<?php

namespace App\Http\Controllers;

use App\Models\Invitation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;

class InvitationController extends Controller
{

    public function __construct()
    {
        $this->middleware('can:isAdmin,App\Models\User')->only(['sendEmailInvitation']);
    }
    public function sendEmailInvitation(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:invitations,email|unique:users,email',
            'roles' => 'required|array',
            'roles.*' => 'required|string|exists:roles,code',
        ]);

        $token = Str::random(32);

        $invitation = new Invitation();
        $invitation->email = $request->email;
        $invitation->token = $token;
        $invitation->roles = json_encode($request->roles);
        $invitation->save();

        Mail::to($request->email)->send(new \App\Mail\InvitationMail($invitation));

        return response()->json(['message' => 'Invitation sent successfully.']);
    }

    /**
     * Registers a user using the invitation token
     * @param Request $request
     */
    public function registerUser(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'firstname' => 'required|string',
            'password' => 'required|min:8',
        ]);

        $invitation = Invitation::where('token', $request->token)->where("email", $request->email)->where('used', false)->first();

        if (!$invitation) {
            return response()->json(['error' => 'Invalid or already used invitation.'], 400);
        }

        $user = new User();
        $user->email = $invitation->email;
        $user->firstname = $request->firstname;
        $user->password =  $request->password;
        $user->save();

        $roles = json_decode($invitation->roles, true);

        $user->roles()->sync($roles);
        $invitation->update(['used' => true]);

        return response()->json(['message' => 'User registered successfully.']);
    }
}
