<?php

namespace App\Http\Controllers;

use App\Models\Strand;
use Illuminate\Http\Request;

class StrandController extends Controller
{
    public function index()
    {
        $strands = Strand::all();
        return view('enum.strands', compact('strands'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'strand_name' => 'required|unique:strands,strand_name|max:255',
        ]);

        Strand::create($request->only('strand_name'));

        return redirect()->route('strands.index')->with('success', 'Strand created successfully.');
    }

    public function update(Request $request, Strand $strand)
    {
        $request->validate([
            'strand_name' => 'required|unique:strands,strand_name,' . $strand->id,
        ]);

        $strand->update($request->only('strand_name'));

        return redirect()->route('strands.index')->with('success', 'Strand updated successfully.');
    }

    public function destroy(Strand $strand)
    {
        $strand->delete();

        return redirect()->route('strands.index')->with('success', 'Strand deleted successfully.');
    }
}