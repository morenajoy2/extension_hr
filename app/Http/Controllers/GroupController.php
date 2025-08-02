<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Group;
use Illuminate\Support\Facades\Auth;

class GroupController extends Controller
{
    public function index()
    {
        if (Auth::user()->role?->role !== 'Admin') {
            abort(403);
        }

        $groups = Group::all();
        return view('enum.groups', compact('groups'));
    }

    public function store(Request $request)
    {
        if (Auth::user()->role?->role !== 'Admin') {
            abort(403);
        }

        $request->validate([
            'group_no' => 'required|integer|unique:groups,group_no',
        ]);

        Group::create([
            'group_no' => $request->group_no,
        ]);

        return redirect()->route('groups.index')->with('success', 'Group created successfully.');
    }

    public function destroy($id)
    {
        if (Auth::user()->role?->role !== 'Admin') {
            abort(403);
        }

        Group::findOrFail($id)->delete();

        return redirect()->route('groups.index')->with('success', 'Group deleted successfully.');
    }

    public function switch(Request $request)
    {
        $request->validate([
            'group_id' => 'required|exists:groups,id',
        ]);

        $user = Auth::user();
        $user->group_id = $request->group_id;
        $user->save();

        $groupNo = Group::find($request->group_id)?->group_no;

        return back()->with('success', 'Switched to Group ' . $groupNo);
    }

}

