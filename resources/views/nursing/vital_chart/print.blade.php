<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Vital</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f1f3f5;
            padding: 20px;
        }

        .letterhead {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.1);
        }
        h5 {
            font-weight: 500;
        }

        .text-right {
            text-align: right;
        }

        .header img {
            max-height: 100px;
            margin-right: 20px;
        }

        .details h4 {
            font-size: 22px;
            margin: 0;
            font-weight: bold;
            color: #333;
        }

        .details p {
            margin: 5px 0;
            font-size: 14px;
            color: #666;
        }

        .rx-date h5 {
            font-size: 16px;
        }


        .signature {
            margin-top: 20px;
            text-align: right;
            font-weight: bold;
        }
        .table-container {
            margin-top: 20px;
            display: flex;
            justify-content: center; /* Centers the table */
        }

        table {
            width: 100%;
            max-width: 95%; /* Ensures equal spacing on left and right */
            border-collapse: collapse;
            background: white;
            border-radius: 10px;
            overflow: hidden;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: center;
            font-size: 14px;
        }

@media print {
    body {
        background-color: white;
    }

    .letterhead {
        padding: 10px;
        box-shadow: none;
    }

    .table-container {
        display: flex;
        justify-content: center;
    }

    table {
        width: 100%;
        max-width: 90%;
    }

    th, td {
        font-size: 12px;
        padding: 8px;
    }
}

      
    </style>
</head>
<body>
<div class="letterhead">
    <!-- Header Section -->
    <div class="header row mb-4">
        <div class="logo col-md-2">
            <img src="{{ url('logos/logo.jpg') }}" alt="Kaneria Hospital Logo" class="img-fluid" height="60px" width="200px">
        </div>
        <div class="details col-md-10 text-right">
            <h4>Dr. Maulik V. Kaneria</h4>
            <p>Consultant Physician</p>
            <p>M.D. Medicine</p>
            <p>Phone: +91 99133 40805</p>
        </div>
    </div>

    <!-- Patient Info -->
    <div class="row-flex">
        <div class="col-flex">
            <h5>Rx : {{ @$patient->fullname }} ({{ @$patient->gender }}) - {{@$patient->address}}</h5>
        </div>
        <div class="col-flex text-right">
            <h5>Date: {{ \Carbon\Carbon::parse(@$patient->created_at)->format('d-m-Y') }}</h5>
        </div>
    </div>

    <!-- Vitals Table -->
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>DateTime</th>
                    <th>Temp</th>
                    <th>Pulse</th>
                    <th>BP</th>
                    <th>Spo2</th>
                    <th>Input</th>
                    <th>Output</th>
                    <th>RBS</th>
                    <th>RT</th>
                    <th>Remark</th>
                </tr>
            </thead>
            <tbody>
                @foreach($vitals as $vital)
                <tr>
                    <td>{{$vital->datetime}}</td>
                    <td>{{$vital->temp}}</td>
                    <td>{{$vital->pulse}}</td>
                    <td>{{$vital->bp}}</td>
                    <td>{{$vital->spo2}}</td>
                    <td>{{$vital->input}}</td>
                    <td>{{$vital->output}}</td>
                    <td>{{$vital->rbs}}</td>
                    <td>{{$vital->rt}}</td>
                    <td>{{$vital->remark}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Signature -->
    <div class="signature">
        <h5><b>DR. MAULIK KANERIA</b></h5>
        <h5>M/D MEDICINE</h5>
        <h5>G-47959</h5>
    </div>
</div>

<script>
    window.onload = function () {
        window.print();
    };
</script>
</body>
</html>
