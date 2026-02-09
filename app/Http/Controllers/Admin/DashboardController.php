<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Unit;
use App\Models\Tenant;
use App\Models\Tenancy;
use App\Models\Bill;
use App\Models\Payment;
use Carbon\Carbon;

class DashboardController extends Controller
{
   public function index()
   {
      $currentMonth = now()->format('Y-m');

      $totalUnits = Unit::count();

      $occupiedUnits = Tenancy::whereNull('end_date')->count();

      $vacantUnits = $totalUnits - $occupiedUnits;

      $totalTenants = Tenant::count();

      $monthlyBills = Bill::where('month', $currentMonth)->count();

      $totalBilledAmount = Bill::where('month', $currentMonth)->sum('total_amount');

      $totalPaidAmount = Payment::whereHas('bill', function ($q) use ($currentMonth) {
         $q->where('month', $currentMonth);
      })->sum('amount');

      $totalDueAmount = $totalBilledAmount - $totalPaidAmount;

      return view('admin.dashboard.index', compact(
         'totalUnits',
         'occupiedUnits',
         'vacantUnits',
         'totalTenants',
         'monthlyBills',
         'totalBilledAmount',
         'totalPaidAmount',
         'totalDueAmount'
      ));
   }
}
