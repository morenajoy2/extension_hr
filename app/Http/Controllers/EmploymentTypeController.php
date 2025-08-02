<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EmploymentType;

class EmploymentTypeController extends Controller
{
    public function index()
    {
        $employmentTypes = EmploymentType::all(); 
        return view('enum.employment-types', compact('employmentTypes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type_name' => 'required|unique:employment_types,type_name|max:255',
        ]);

        EmploymentType::create($validated);

        return redirect()->route('employment-types.index')->with('success', 'Employment Type created successfully.');
    }

    public function update(Request $request, EmploymentType $employmentType)
    {
        $validated = $request->validate([
            'type_name' => 'required|max:255|unique:employment_types,type_name,' . $employmentType->id,
        ]);

        $employmentType->update($validated);

        return redirect()->route('employment-types.index')->with('success', 'Employment Type updated successfully.');
    }

    public function destroy(EmploymentType $employmentType)
    {
        $employmentType->delete();

        return redirect()->route('employment-types.index')->with('success', 'Employment Type deleted successfully.');
    }
}

