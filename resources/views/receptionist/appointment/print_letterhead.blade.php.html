<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Print Letterhead</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        *{
            margin: 0;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 40px;
            max-width: 800px;
            margin: auto;
            color: #333;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #ccc;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .logo img {
            width: 68%;
        }

        .details {
            text-align: right;
            font-size: 14px;
        }

        .details h2 {
            font-size: 20px;
            color: #134d68;
            margin-bottom:5px;
        }

        h5 {
            color: #134d68;
            margin-bottom:5px;
        }

        .flex-between {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
        }

        .table-bordered {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .table-bordered th, .table-bordered td {
            border: 1px solid #ccc;
            
            text-align: left;
            font-size: 14px;
        }

        .diagnosis-list {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            font-size: 14px;
        }
        .diagnosis-list p {
            margin: 0;
            padding: 5px 10px;
            background-color: #e7f3ff;
            border-radius: 5px;
        }


        .footer {
            text-align: center;
            margin-top: 50px;
            font-size: 14px;
        }

        .flex-container {
            display: flex;
            justify-content: space-between;
            gap: 20px;
            flex-wrap: wrap;
        }

        .left-column .section,
        .right-column .section {
            margin-bottom: 20px;
        }


        @media print {
            button.print-btn {
                display: none;
            }
        }
        p{
            font-size: 14px;
        }

        .left-column {
            flex: 1 1 9%;
        }

        .right-column {
            flex: 1 1 62%;
        }

    </style>
</head>
<body>

    <div class="header">
        <div class="logo">
            <img src="{{ url('logos/logo.jpg') }}" alt="Kaneria Hospital Logo">
        </div>
        <div class="details">
            <h2>Dr. Maulik V. Kaneria</h2>
            <p>Consultant Physician</p>
            <p>M.D. Medicine</p>
            <p>Phone: +91 99133 40805</p>
        </div>
    </div>

    <div class="section flex-between" style="margin-bottom: 15px;">
        <p><strong>Rx:</strong> {{ $patient->fullname }} ({{ $patient->gender }}) - Age: {{ $patient->age }} - {{ $patient->address }}</p>
        <p><strong>Date:</strong> {{ \Carbon\Carbon::parse($patient->created_at)->format('d-m-Y') }}</p>
    </div>

    <div class="flex-container">

    {{-- LEFT COLUMN --}}
    <div class="left-column">
        <div class="section">
            <h5>Vitals</h5>
<table border="1" cellpadding="5" cellspacing="0">
    <tr>
        <td><strong>BP:</strong></td>
        <td>{{ $letterhead->bp ?? 'N/A' }}</td>
    </tr>
    <tr>
        <td><strong>P:</strong></td>
        <td>{{ $letterhead->pulse ?? 'N/A' }}</td>
    </tr>
    <tr>
        <td><strong>SPO2:</strong></td>
        <td>{{ $letterhead->spo2 ?? 'N/A' }}</td>
    </tr>
    <tr>
        <td><strong>T:</strong></td>
        <td>{{ $letterhead->temp ?? 'N/A' }}</td>
    </tr>
    <tr>
        <td><strong>RS:</strong></td>
        <td>{{ $letterhead->rs ?? 'N/A' }}</td>
    </tr>
    <tr>
        <td><strong>CVS:</strong></td>
        <td>{{ $letterhead->cvs ?? 'N/A' }}</td>
    </tr>
    <tr>
        <td><strong>ECG:</strong></td>
        <td>{{ $letterhead->ecg ?? 'N/A' }}</td>
    </tr>
    <tr>
        <td><strong>RBS:</strong></td>
        <td>{{ $letterhead->rbs ?? 'N/A' }}</td>
    </tr>
</table>

        
        </div>
        <div class="section"><h5>Addition</h5><p>{{ $letterhead->addition ?? 'No addition.' }}</p></div>
        <div class="section"><h5>Complaint</h5><p>{{ $letterhead->complaint ?? 'No complaint.' }}</p></div>
        <div class="section"><h5>Past History</h5><p>{{ $letterhead->past_history ?? 'No Past History.' }}</p></div>
        <div class="section"><h5>Next Report</h5><p>{{ $letterhead->next_report ?? 'N/A' }}</p></div>
        <div class="section"><h5>Next Appointment Date</h5><p>{{ $letterhead->next_date ?? 'N/A' }}</p></div>
    </div>

    {{-- RIGHT COLUMN --}}
    <div class="right-column">
            <div class="section">
            <h5>Diagnosis</h5>
            <div class="diagnosis-list">
                @foreach(json_decode($letterhead->diagnosis ?? '[]') as $name)
                    <p>{{ $name }}</p>
                @endforeach
            </div>
        </div>

        @php
            $reportItems = $letterhead->report ?? 'No report data available.';
        @endphp
        <div class="section">
            <h5>Report</h5>
            <div class="diagnosis-list">
                {{ $reportItems ? implode(', ', preg_split('/\r\n|\r|\n/', $reportItems)) : 'No report data available.' }}
            </div>
        </div>
        <div class="section">
            <h5>Medicines</h5>
            <table class="table-bordered">
                <thead>
                    <tr>
                        <th>Medicine</th>
                        <th>Qty</th>
                        <th>Freq</th>
                        <th>Note</th>
                        <th>Extra Note</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($medicines as $medicine)
                        <tr>
                            <td>{{ $medicine->name }}</td>
                            <td>{{ $medicine->quantity }}</td>
                            <td>{{ $medicine->frequency }}</td>
                            <td>{{ $medicine->note }}</td>
                            <td>{{ $medicine->extra_note }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
        <div class="section" style="width: 100%; line-height: 1.5;"><h5>Diet Chart</h5><p>{{ $letterhead->diat ?? 'N/A' }}</p></div>
        <div class="section" style="width: 100%; line-height: 1.5;"><h5>Note</h5><p>{{ $letterhead->note ?? 'No note.' }}</p></div>

    <div class="footer">
        <h4>Dr. Maulik Kaneria</h4>
        <p>M.D. Medicine | Reg No: G-47959</p>
    </div>

<script>
    window.print();
    window.onafterprint = function () {
        window.history.back();
    };
</script>


</body>
</html>
