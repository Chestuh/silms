<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        if ($user) {
            if ($user->role === 'student') {
                return redirect()->route('student.dashboard');
            }
            if ($user->role === 'instructor') {
                return redirect()->route('instructor.dashboard');
            }
            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }
            if ($user->role === 'cashier') {
                return redirect()->route('cashier.dashboard');
            }
        }
        return view('home');
    }
}
