<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role_id' => 'required|integer|exists:roles,id|'.Rule::in(['1','2','3','4']),
        ]);
        $request['password'] = bcrypt($request['password']);
        $user = User::create($request->all());
        return response(['data' => $user], 201);
    }
}
