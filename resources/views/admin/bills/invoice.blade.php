<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Invoice {{ $bill->invoice_no }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #111;
        }

        .header {
            border-bottom: 2px solid #4f46e5;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .title {
            font-size: 22px;
            font-weight: bold;
            color: #4f46e5;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 8px;
            border: 1px solid #ddd;
        }

        th {
            background: #f3f4f6;
            text-align: left;
        }

        .total {
            font-weight: bold;
        }

        .right {
            text-align: right;
        }
    </style>
</head>

<body>

    <div class="header">
        <table>
            <tr>
                <td>
                    <div class="title">INVOICE</div>
                    <div>Invoice #: {{ $bill->invoice_no }}</div>
                    <div>Month: {{ $bill->month }}</div>
                </td>
                <td class="right">
                    <strong>{{ config('app.name') }}</strong><br>
                    {{ $bill->unit->property->name }}<br>
                    {{ $bill->unit->property->address }}
                </td>
            </tr>
        </table>
    </div>

    <table>
        <tr>
            <th>Tenant</th>
            <td>{{ $bill->tenant->name }}</td>
            <th>Unit</th>
            <td>{{ $bill->unit->unit_no }}</td>
        </tr>
    </table>

    <br>

    <table>
        <thead>
            <tr>
                <th>Description</th>
                <th class="right">Amount</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Monthly Rent</td>
                <td class="right">{{ number_format($bill->rent_amount, 2) }}</td>
            </tr>

            @if ($bill->electricity_amount > 0)
                <tr>
                    <td>Electricity Bill</td>
                    <td class="right">{{ number_format($bill->electricity_amount, 2) }}</td>
                </tr>
            @endif

            <tr class="total">
                <td>Total</td>
                <td class="right">{{ number_format($bill->total_amount, 2) }}</td>
            </tr>
        </tbody>
    </table>

    <br>

    <table>
        <tr>
            <th>Status</th>
            <td>{{ strtoupper($bill->status) }}</td>
            <th>Due Date</th>
            <td>{{ $bill->due_date }}</td>
        </tr>
    </table>

    <br>

    @if ($bill->payments->count())
        <h4>Payments</h4>
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Method</th>
                    <th class="right">Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($bill->payments as $payment)
                    <tr>
                        <td>{{ $payment->payment_date }}</td>
                        <td>{{ ucfirst($payment->method) }}</td>
                        <td class="right">{{ number_format($payment->amount, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <br><br>
    <p style="text-align:center;color:#555">
        Thank you for your payment.
    </p>

</body>

</html>
