<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Progress;

class ProgressController extends Controller
{
    public function index()
    {
        try {
            $technicians = Progress::all();

            $message = $technicians->isEmpty() ? "No technicians found." : null;

            return view('techProgress', compact('technicians', 'message'));

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Something went wrong while fetching technicians.']);
        }
    }

    public function addTech(Request $request) 
    {
        try {
            $data = $request->validate([
                'tech_name' => 'required|string|max:255',
                'company' => 'required|string|max:255',
            ]);

            Progress::create($data);

            return redirect()->route('techProgress')->with('success', 'Technician added successfully.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to add technician.']);
        }
    }

    public function deleteTech($id)
    {
        try {
            $tech = Progress::findOrFail($id);
            $tech->delete();

            return redirect()->route('techProgress')->with('success', 'Technician deleted successfully.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to delete technician.']);
        }
    }
}
