<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Files</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #ffffff; /* Set the overall page background to white */
        }
        .container {
            max-width: 900px;
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }
        .header {
            text-align: center;
        }
        .logo {
            width: 250px;
            height: auto;
        }
        .patient-info {
            flex-wrap: wrap;
            gap: 20px;
            background-color: #f3f3f3; /* Gray background only for Patient Details section */
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
        }
        .patient-info div {
            text-align: left;
            font-size: 16px;
            color: #333;
        }
        .patient-info strong {
            display: block;
            font-weight: bold;
        }
        h2 {
            color: #4CAF50;
            font-size: 22px;
            border-bottom: 2px solid #ddd;
            text-align: center;
            padding-bottom: 8px;
        }
        .file-container {
            padding: 15px;
            border-radius: 8px;
        }
        .file-container p {
            font-size: 16px;
            color: #555;
            margin-bottom: 10px;
        }
        .file-image {
            display: block;
            margin: 10px auto;
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            border: 1px solid #ddd;
        }
        
    </style>
</head>
<body>
    <div class="container">
        <!-- Header Section -->
        <div class="header">
            <img src="{{ public_path('logos/logo.jpg') }}" class="logo" alt="Hospital Logo">
        </div><br>

        <!-- Patient Info Section -->
        <div class="patient-info">
            <div>
                <strong>Patient Name: <span>{{ $patientName }}</span></strong>
                
            </div>
            <div>
                <strong>Age: <span>{{ $patientAge }}</span></strong>
                
            </div>
            <div>
                <strong>Phone Number: <span>{{ $patientPhone }}</span></strong>
                
            </div>
            <div>
                <strong>Address:  <span>{{ $patientAddress }}</span></strong>
               
            </div>
        </div>

        <!-- Title -->
        <h2>Patient Files</h2>

        <!-- Patient Files Section -->
        @foreach($patientFiles as $file)
            <div class="file-container">
                <p><strong>File Name:</strong> {{ $file->file_name }}</p>
                <img src="{{ public_path('storage/' . $file->file_path) }}" alt="{{ $file->file_name }}" class="file-image">
            </div>
        @endforeach
    </div>
</body>
</html>
