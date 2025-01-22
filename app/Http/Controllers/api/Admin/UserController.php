<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{

    public function __construct()
    {
        $this->middleware('can:isAdmin,App\Models\User')->only('index');
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $companyIds = $user->companies()->pluck('companies.id');

        $query = User::query()->whereHas('companies', function ($query) use ($companyIds) {
            $query->whereIn('companies.id', $companyIds);
        });

        $users = $query->whereKeyNot($user->id)->paginate(5);

        // $users = User::whereHas('companies', function ($query) use ($companyIds) {
        //     $query->whereIn('companies.id', $companyIds);
        // })->paginate(4);
        // Log::info($user->roles);
        return UserResource::collection($users);
        //return response()->json($users);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request,  User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
