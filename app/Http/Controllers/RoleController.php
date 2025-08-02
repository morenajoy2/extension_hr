<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleController extends Controller
{
    public function index()
    {
        $users = User::with(['role', 'department', 'departmentTeam', 'position'])->get();
        return view('roles.index', compact('users'));
    }

    public function switch(Request $request)
    {
        $request->validate([
            'role_id' => 'required|exists:roles,id',
        ]);

        $user = Auth::user();

        if ($user->role_id == $request->role_id) {
            return back()->with('message', 'You are already using this role.');
        }

        $user->update(['role_id' => $request->role_id]);

        return redirect()->route('dashboard')->with('message', 'Role switched to ' . $user->role->role);
    }
}
