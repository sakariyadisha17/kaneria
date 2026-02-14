<!DOCTYPE html>
<html>
<head>
    <title>Survey Report</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Noto+Sans+Gujarati:wght@100..900&display=swap');
        body{
            font-family: 'Noto Sans Gujarati',"Nirmala UI", sans-serif;
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
    <h2>Survey Report</h2>
    @if($start && $end)
        <p>From: {{ $start }} To: {{ $end }}</p>
    @endif

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Mobile</th>
                <th>Doctor Rating</th>
                <th>Staff Rating</th>
                <th>Reception Rating</th>
                <th>Medical Store Staff</th>
                <th>Lab Services</th>
                <th>Suggestions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($surveys as $index => $survey)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $survey->mobile }}</td>
                    <td>{{ $survey->doctor_rating }}</td>
                    <td>{{ $survey->staff_rating }}</td>
                    <td>{{ $survey->recep_rating }}</td>
                    <td>{{ $survey->medical_store_staff }}</td>
                    <td>{{ $survey->lab_services }}</td>
                    <td>{{ $survey->suggestions }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" style="text-align: center;">No records found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
