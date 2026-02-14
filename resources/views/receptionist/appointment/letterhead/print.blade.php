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
            background-color: #f1f3f5;
        }

        h5 {
            font-weight: 500;
        }

        .text-right {
            text-align: right;
        }

        .letterhead {
            background-color: #fff;
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.1);
        }

        .header img {
            max-height: 100px;
            margin-right: 20px;
        }

        .details h4 {
            font-size: 22px;
            margin: 0;
            font-weight: bold;
        }

        .details p {
            margin: 5px 0;
            font-size: 14px;
        }

        .rx-date h5 {
            font-size: 16px;
        }


        .vital-item {
            display: flex;
            line-height: 25px;
            justify-content: space-between; 
        }


        .form-section {
            margin-top: 20px;
        }

        .form-section h5 {
            margin-bottom: 10px;
        }

        textarea {
            resize: none;
            border-radius: 5px;
            border: 1px solid #ccc;
            padding: 10px;
            width: 100%;
        }

        .table-bordered th, .table-bordered td {
            border: 1px solid #ddd;
            text-align: center;
        }

        .row-flex {
            display: flex;
            flex-wrap: wrap;
        }

        .col-flex {
            flex: 1;
            min-width: 150px;
        }

        .text-right {
            text-align: right;
        }
    </style>
</head>
<body>
<div class="letterhead p-4">
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

    <!-- Main Content -->
    <div class="row-flex">
       <!-- Vitals Box -->
    <div class="col-flex">
        <h4>Vitals:</h4>
        <div class="vital-item"><label>BP:</label> <span></span></div>
        <div class="vital-item"><label>P:</label> <span></span></div>
        <div class="vital-item"><label>SPO<sub>2</sub>:</label> <span></span></div>
        <div class="vital-item"><label>T:</label> <span></span></div>
        <div class="vital-item"><label>RS:</label> <span></div>
        <div class="vital-item"><label>CVS:</label> <span></span></div>
        <div class="vital-item"><label>ECG:</label> <span></span></div>
        <div class="vital-item"><label>RBS:</label> <span></span></div>
    </div>


        <!-- Diagnosis, Report, and Medicines -->
        <div class="col-flex">
            <div class="form-section">
                <h5>Diagnosis:</h5>
            </div>

            <div class="form-section">
                <h5>Add Report:</h5>
            </div>

            <div class="form-section">
                <h5>Addition:</h5>
            </div>

            <div class="form-section">
                <h5>C/O (Chief Complaint):</h5>
            </div>

            <div class="form-section">
                <h5>Past History:</h5>
            </div>
            <div class="form-section">
                <h5>Diet Chart:</h5>
            </div>

            <div class="form-section">
                <h6><b>સુચના:</b></h6>
            </div>
            <div class="form-section">
                <h6><b>ફરી બતાવવા આવો ત્યારે નીચે બતાવેલ રીપોર્ટ કરાવીને આવવું.</b></h6>
            </div>
        </div>
    </div>

    <!-- Additional Information -->
    <h4> Medicine List :</h4>
               
    
  
    <div class="form-section text-right">
        <h5>ફરી બતાવવા ની તારીખ:
        </h5>
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
