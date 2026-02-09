<!DOCTYPE html>
<html>

<head>
    <style>
        body {
            font-family: DejaVu Sans;
            font-size: 12px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .total {
            font-weight: bold;
            font-size: 14px;
        }
    </style>
</head>

<body>

    {{-- 
   $pdf = PDF::loadView('invoices.pdf', compact('bill'));
return $pdf->download('invoice.pdf'); --}}


    <div class="header">
        <h2>Your Company Name</h2>
        <p>Invoice #{{ $bill->invoice_no }}</p>
    </div>

    <p><strong>Tenant:</strong> {{ $bill->tenant->name }}</p>
    <p><strong>Unit:</strong> {{ $bill->unit->unit_no }}</p>
    <p><strong>Month:</strong> {{ $bill->month }}</p>

    <hr>

    <p>Rent: {{ $bill->rent_amount }}</p>
    <p>Electricity: {{ $bill->electricity_amount }}</p>

    <hr>

    <p class="total">Total: {{ $bill->total_amount }}</p>

</body>

</html>
