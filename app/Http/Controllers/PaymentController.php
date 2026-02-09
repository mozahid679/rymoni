<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
   public function index()
   {
      return view('admin.payments', [
         'payments' => Payment::with('bill.tenant', 'bill.unit')->latest()->get(),
         'bills' => Bill::with('tenant', 'unit')
            ->whereIn('status', ['unpaid', 'partial'])
            ->get(),
      ]);
   }

   public function store(Request $request)
   {
      $data = $request->validate([
         'bill_id' => 'required|exists:bills,id',
         'amount' => 'required|numeric|min:1',
         'method' => 'required|in:cash,bank,bkash,nagad',
         'payment_date' => 'required|date',
         'received_by' => 'nullable|string|max:255',
      ]);

      Payment::create($data);

      $bill = Bill::with('payments')->findOrFail($data['bill_id']);
      $paid = $bill->payments->sum('amount');

      if ($paid >= $bill->total_amount) {
         $bill->update(['status' => 'paid']);
      } elseif ($paid > 0) {
         $bill->update(['status' => 'partial']);
      }

      return redirect()
         ->route('payments.index')
         ->with('success', 'Payment recorded successfully.');
   }
}
