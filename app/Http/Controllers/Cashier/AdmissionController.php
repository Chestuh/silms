<?php

namespace App\Http\Controllers\Cashier;

use App\Http\Controllers\Controller;
use App\Models\AdmissionRecord;
use Illuminate\Http\Request;

class AdmissionController extends Controller
{
    public function index(Request $request)
    {
        $records = AdmissionRecord::with('student.user')
            ->orderByDesc('date_processed')
            ->paginate(20);
        return view('cashier.admission.index', compact('records'));
    }
}
