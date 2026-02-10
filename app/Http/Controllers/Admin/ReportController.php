<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Models\Unit;
use App\Models\Tenancy;
use App\Models\Bill;
use App\Models\Payment;
use App\Models\MeterReading;
use Carbon\Carbon;

class ReportController extends Controller
{
   public function index()
   {
      $month = request('month', now()->format('Y-m'));

      $start = Carbon::parse($month . '-01')->startOfMonth();
      $end   = Carbon::parse($month . '-01')->endOfMonth();

      /* ================= SUMMARY ================= */

      $totalProperties = Property::count();
      $totalUnits      = Unit::count();
      $activeTenancies = Tenancy::whereNull('end_date')->count();

      $bills = Bill::where('month', $month)->get();
      $totalBilled = $bills->sum('total_amount');

      $totalPaid   = Payment::whereBetween('payment_date', [$start, $end])->sum('amount');
      $totalDue    = $totalBilled - $totalPaid;

      /* ================= ELECTRICITY ================= */

      $meterReadings = MeterReading::with(['meter.unit'])
         ->where('month', $month)
         ->get();

      $totalUnitsConsumed = $meterReadings->sum('unit_used');

      return view('admin.reports.index', compact(
         'month',
         'totalProperties',
         'totalUnits',
         'activeTenancies',
         'bills',
         'totalBilled',
         'totalPaid',
         'totalDue',
         'meterReadings',
         'totalUnitsConsumed'
      ));
   }
}
