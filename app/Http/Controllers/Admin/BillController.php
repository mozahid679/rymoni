<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bill;
use App\Models\Tenancy;
use App\Services\ElectricityCalculator;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Artisan;

class BillController extends Controller
{
   public function index(Request $request)
   {
      $query = Bill::with(['tenant'])->latest();

      // Check if user filtered by month (Format: YYYY-MM)
      if ($request->filled('filter_month')) {
         $query->where('month', $request->filter_month);
         // Note: If your DB stores full dates like 2026-01-15, use:
         // $query->where('month', 'like', $request->filter_month . '%');
      }

      return view('admin.bills', [
         // Change get() to paginate()
         'bills'     => $query->paginate(10)->withQueryString(),
         'tenancies' => Tenancy::with(['tenant', 'unit.property'])
            ->whereNull('end_date')
            ->get(),
         'editBill'  => null,
      ]);
   }

   public function showGenerateForm()
   {
      return view('admin.bills-generate');
   }

   public function store(Request $request)
   {
      $data = $request->validate([
         'tenancy_id' => 'required|exists:tenancies,id',
         'month' => 'required|string',
         'rent_amount' => 'required|numeric|min:0',
         'current_unit' => 'required|gt:previous_unit',
         'electricity_amount' => 'nullable|numeric|min:0',
         'issue_date' => 'required|date',
         'due_date' => 'required|date|after_or_equal:issue_date',
      ]);

      $tenancy = Tenancy::with(['tenant', 'unit'])->findOrFail($data['tenancy_id']);

      $electricity = $data['electricity_amount'] ?? 0;

      Bill::create([
         'invoice_no' => 'INV-' . strtoupper(Str::random(8)),
         'tenant_id' => $tenancy->tenant_id,
         'unit_id' => $tenancy->unit_id,
         'month' => $data['month'],
         'rent_amount' => $data['rent_amount'],
         'electricity_amount' => $electricity,
         'total_amount' => $data['rent_amount'] + $electricity,
         'issue_date' => $data['issue_date'],
         'due_date' => $data['due_date'],
      ]);

      return redirect()
         ->route('bills.index')
         ->with('success', 'Bill created successfully.');
   }

   public function runGenerate(Request $request)
   {
      $month = $request->input('month', now()->format('Y-m'));

      Artisan::call('bills:generate', [
         'month' => $month
      ]);

      $output = Artisan::output();

      return redirect()->route('bills.index')
         ->with('success', "Bills for {$month} generated successfully.")->with('output', $output);
   }

   public function invoice(Bill $bill)
   {
      $bill->load([
         'tenant',
         'unit.property',
         'payments',
      ]);

      $pdf = Pdf::loadView('admin.bills.invoice', compact('bill'))
         ->setPaper('a4');

      return $pdf->stream('invoice-' . $bill->invoice_no . '.pdf');
   }

   public function edit(Bill $bill)
   {
      return view('admin.bills', [
         'bills' => Bill::with(['tenant', 'unit'])->latest()->get(),
         'tenancies' => Tenancy::with(['tenant', 'unit.property'])
            ->whereNull('end_date')
            ->get(),
         'editBill' => $bill,
      ]);
   }

   public function update(Request $request, Bill $bill)
   {
      $data = $request->validate([
         'rent_amount' => 'required|numeric|min:0',
         'electricity_amount' => 'nullable|numeric|min:0',
         'status' => 'required|in:unpaid,partial,paid',
      ]);

      $electricity = $data['electricity_amount'] ?? 0;

      $bill->update([
         'rent_amount' => $data['rent_amount'],
         'electricity_amount' => $electricity,
         'total_amount' => $data['rent_amount'] + $electricity,
         'status' => $data['status'],
      ]);

      return redirect()
         ->route('bills.index')
         ->with('success', 'Bill updated successfully.');
   }
}
