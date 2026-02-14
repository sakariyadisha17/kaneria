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
    <h2>Patients Report ({{ $selectedDate }})</h2>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Patient Name</th>
                <th>Address</th>
                <th>Phone</th>
                <th>Referred By</th>
                <th>Admit Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($patients as $index => $patient)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $patient->fullname }}</td>
                    <td>{{ $patient->address }}</td>
                    <td>{{ $patient->phone }}</td>
                    <td>{{ $patient->referred_by_name ?? 'N/A' }}</td>
                    <td>{{ $patient->formatted_created_at }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="footer">
        &copy; {{ date('Y') }} Patients Report. All rights reserved.
    </div>
</body>
</html>
