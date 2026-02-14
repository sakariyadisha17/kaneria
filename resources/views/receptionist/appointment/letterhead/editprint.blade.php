<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Letterhead</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #fff;
            margin: 0;
            padding: 0;
        }

        .letterhead {
            padding: 10px;
            border-radius: 5px;
        }

        h4, h5, h6 {
            margin: 5px 0;
        }

        .header img {
            max-height: 80px;
            width: auto;
        }

        .header .details {
            text-align: right;
            font-size: 12px;
        }

        .vitals-box {
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 10px;
            font-size: 12px;
        }

        .vital-item {
            display: flex;
            justify-content: space-between;
            padding: 2px 0;
        }

        .form-section {
            font-size: 12px;
            margin-top: 10px;
        }

        textarea {
            resize: none;
            width: 100%;
            border-radius: 5px;
            border: 1px solid #ccc;
            padding: 5px;
        }

        .row-flex {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
        }

        .col-flex {
            flex: 1;
            min-width: 200px;
        }

        .text-right {
            text-align: right;
        }

        /* Print-specific adjustments */
        @media print {
            body {
                margin: 0;
                padding: 0;
                font-size: 10px;
            }

            .letterhead {
                padding: 5px;
                border: none;
                box-shadow: none;
            }

            h4, h5, h6 {
                font-size: 14px;
            }

            .vitals-box, .form-section {
                font-size: 14px;
            }

            .row-flex {
                gap: 10px;
            }

            .header img {
                max-height: 60px;
            }
        }
    </style>
</head>
<body>
<div class="letterhead">
    <!-- Header Section -->
    <div class="header row mb-4">
        <div class="logo col-md-2">
            <img src="{{ url('logos/logo.jpg') }}" alt="Kaneria Hospital Logo">
        </div>
        <div class="details col-md-10">
            <h4>Dr. Maulik V. Kaneria</h4>
            <p>Consultant Physician</p>
            <p>M.D. Medicine</p>
            <p>Phone: +91 99133 40805</p>
        </div>
    </div>

    <!-- Patient Info -->
    <div class="row-flex">
        <div class="col-flex">
            <h5>Rx: {{ @$patient->fullname }} ({{ @$patient->gender }}) - {{ @$patient->address }}</h5>
        </div>
        <div class="col-flex text-right">
            <h5>Date: {{ \Carbon\Carbon::parse(@$patient->created_at)->format('d-m-Y') }}</h5>
        </div>
    </div>

    <!-- Main Content -->
    <div class="row-flex">
        <!-- Vitals Box -->
        <div class="col-flex">
            <div class="vitals-box">
                <h5>Vitals:</h5>
                <div class="vital-item"><label>BP:</label> {{ @$letterhead->bp ?? '' }}</div>
                <div class="vital-item"><label>P:</label> {{ @$letterhead->pulse ?? '' }}</div>
                <div class="vital-item"><label>SPO<sub>2</sub>:</label> {{ @$letterhead->spo2 ?? '' }}</div>
                <div class="vital-item"><label>T:</label> {{ @$letterhead->temp ?? '' }}</div>
                <div class="vital-item"><label>RS:</label> {{ @$letterhead->rs ?? '' }}</div>
                <div class="vital-item"><label>CVS:</label> {{ @$letterhead->cvs ?? '' }}</div>
                <div class="vital-item"><label>ECG:</label> {{ @$letterhead->ecg ?? '' }}</div>
                <div class="vital-item"><label>RBS:</label> {{ @$letterhead->rbs ?? '' }}</div>
            </div>

            <div class="form-section">
                <h5>Addition:</h5> <span>{{ @$letterhead->addition ?? '' }}</span>
            </div>

            <div class="form-section">
                <h5>C/O (Chief Complaint):</h5> <span>{{ @$letterhead->complaint ?? '' }}</span>
            </div>

            <div class="form-section">
                <h5>Past History:</h5> <span>{{ @$letterhead->history ?? '' }}</span>
            </div>

        </div>

        <!-- Diagnosis, Report, and Medicines -->
        <div class="col-flex">
            <div class="form-section">
                <h5>Diagnosis:</h5> <span>{{ @$letterhead->diagnosis ?? '' }}</span>
            </div>

            <div class="form-section">
                <h5>Add Report:</h5> <span>{{ @$letterhead->report ?? '' }}</span>
            </div>

           
            <div class="form-section">
                <h5>Diet Chart:</h5> <span>{{ @$letterhead->diat ?? '' }}</span>
            </div>

            <div class="form-section">
                <h6><b>સુચના:</b></h6> <span>{{ @$letterhead->note ?? '' }}</span>
            </div>

            <div class="form-section">
                <h6><b>ફરી બતાવવા આવો ત્યારે નીચે બતાવેલ રીપોર્ટ કરાવીને આવવું.</b></h6>
                <span>{{ @$letterhead->next_report ?? '' }}</span>
            </div>
        </div>
    </div>

    <!-- Additional Information -->
    <div id="medicinesTable">
        <h5>Medicines:</h5>
        
        <table class="table table-bordered mb-0">
            <thead>
                <tr>
                    <th>Medicine</th>
                    <th>Quantity</th>
                    <th>Frequency</th>                                                        
                    <th>Note</th>
                </tr>
            </thead>
            <tbody id="medicinesList">
            @foreach ($medicines as $medicine)
                <tr data-id="{{ $medicine->id }}">
                    <td>{{ $medicine->name }}</td>
                    <td>{{ $medicine->quantity }}</td>
                    <td>{{ $medicine->frequency }}</td>                                                        
                    <td>{{ $medicine->note }}</td>
                </tr>
            @endforeach
        </tbody>
        </table>
    </div>
    <div class="form-section">
        <h5>ફરી બતાવવા ની તારીખ:</h5> <span>{{ @$letterhead->next_date ?? '' }}</span>
    </div>
    <div class="text-right">
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
