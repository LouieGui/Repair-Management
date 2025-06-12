<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function customer()
    {
        return view('customer');
    }

    public function technician()
    {
        return view('technician');
    }
}
