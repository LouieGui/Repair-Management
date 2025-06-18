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

            if ($technicians->isEmpty()) {
                $message = "No technicians found.";
            } else {
                $message = null;
            }

            return view('techProgress', compact('technicians', 'message'));

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Something went wrong while fetching technicians.']);
        }
    }

    // public function store() 
    // {
    //     try {
    //         $request->validate([
    //             'technician_name' => 'required|string|max:255',
    //             'progress' => 'required|numeric|min:0|max:100',
    //         ]);
    //     } catch (\Exception $e) {
    //         return back()->withErrors(['error' => 'Something went wrong while storing progress.']);
    //     }
    // }
}
