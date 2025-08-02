<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DepartmentTeam;

class DepartmentTeamController extends Controller
{
    public function index()
    {
        $department_teams = DepartmentTeam::all();
        return view('enum.department-teams', compact('department_teams'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'team_name' => 'required|unique:department_teams,team_name|max:255',
        ]);

        DepartmentTeam::create($request->only('team_name'));

        return redirect()->route('department-teams.index')->with('success', 'Department Team created successfully.');
    }

    public function update(Request $request, DepartmentTeam $departmentTeam)
    {
        $request->validate([
            'team_name' => 'required|unique:department_teams,team_name,' . $departmentTeam->id,
        ]);

        $departmentTeam->update($request->only('team_name'));

        return redirect()->route('department-teams.index')->with('success', 'Department Team updated successfully.');
    }

    public function destroy(DepartmentTeam $departmentTeam)
    {
        $departmentTeam->delete();

        return redirect()->route('department-teams.index')->with('success', 'Department Team deleted successfully.');
    }
}
