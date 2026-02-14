<!DOCTYPE html>
<html>
<head>
    <title>Patients Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f9f9f9;
            color: #333;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        h1 {
            text-align: center;
            color: #4CAF50;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        thead {
            background-color: #4CAF50;
            color: white;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }
        th {
            font-size: 16px;
        }
        td {
            font-size: 14px;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        tfoot {
            background-color: #f9f9f9;
        }
        .totals {
            text-align: right;
            margin-top: 20px;
        }
        .totals p {
            font-size: 14px;
            margin: 5px 0;
        }
        .footer {
            text-align: center;
            font-size: 12px;
            color: #666;
            margin-top: auto;
            padding-top: 10px;
            border-top: 1px solid #ddd;
        }
    </style>
</head>
<body>
    <h1>Patients Report - {{ \Carbon\Carbon::now()->format('d-m-Y') }}</h1>
    <table>
        <thead>
            <tr>
                <th>Patient ID</th>
                <th>Time</th>
                <th>Full Name</th>
                <th>Age</th>
                <th>Phone</th>
                <th>Payment Type</th>
                <th>Amount</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($patients as $patient)
                <tr>
                    <td>{{ $patient->patient_id }}</td>
                    <td>{{ $patient->booking_time_formatted }}</td>
                    <td>{{ $patient->fullname }}</td>
                    <td>{{ $patient->age }}</td>
                    <td>{{ $patient->phone }}</td>
                    <td>{{ $patient->payment_type }}</td>
                    <td>{{ $patient->amount }}</td>
                    <td>{{ $patient->status }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="totals">
        <p><strong>Cash Payment Total:</strong> {{ $cashTotal }}</p>
        <p><strong>Online Payment Total:</strong> {{ $onlineTotal }}</p>
        <p><strong>Total amount:</strong> {{ $totalAmount }}</p>
    </div>

    <div class="footer">
        &copy; {{ date('Y') }} Patients Report. All rights reserved.
    </div>
</body>
</html>
