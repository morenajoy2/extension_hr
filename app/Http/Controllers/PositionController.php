<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Position;

class PositionController extends Controller
{
    public function index()
    {
        $positions = Position::all();
        return view('enum.positions', compact('positions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'position_name' => 'required|unique:positions,position_name|max:255',
        ]);

        Position::create($request->only('position_name'));

        return redirect()->route('positions.index')->with('success', 'Position created successfully.');
    }

    public function update(Request $request, Position $position)
    {
        $request->validate([
            'position_name' => 'required|unique:positions,position_name,' . $position->id,
        ]);

        $position->update($request->only('position_name'));

        return redirect()->route('positions.index')->with('success', 'Position updated successfully.');
    }

    public function destroy(Position $position)
    {
        $position->delete();

        return redirect()->route('positions.index')->with('success', 'Position deleted successfully.');
    }
}
