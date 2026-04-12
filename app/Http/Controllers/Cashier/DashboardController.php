<?php

namespace App\Http\Controllers\Cashier;

use App\Http\Controllers\Controller;
use App\Models\Fee;
use App\Models\CredentialRequest;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $pendingFeesCount = Fee::where('status', 'pending')->count();
        $pendingFeesAmount = Fee::where('status', 'pending')->sum('amount');
        $paidToday = Fee::where('status', 'paid')
            ->whereDate('paid_at', today())
            ->sum('amount');
        $credentialsAwaitingClearance = CredentialRequest::whereIn('status', ['pending', 'processing'])
            ->whereNull('payment_cleared_at')
            ->count();
        $credentialsCleared = CredentialRequest::whereNotNull('payment_cleared_at')->count();

        $kpis = [
            'pending_fees_count' => $pendingFeesCount,
            'pending_fees_amount' => $pendingFeesAmount,
            'paid_today' => $paidToday,
            'credentials_awaiting_clearance' => $credentialsAwaitingClearance,
            'credentials_cleared' => $credentialsCleared,
        ];

        return view('cashier.dashboard', compact('kpis'));
    }
}
