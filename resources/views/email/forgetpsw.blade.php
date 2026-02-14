<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password Email</title>
    <style>
        /* Basic styles for email readability */
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
        }
        h1 {
            color: #333;
        }
        .btn {
            display: inline-block;
            font-size: 16px;
            font-weight: bold;
            color: #ffffff;
            background-color: #007bff;
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            text-decoration: none;
            text-align: center;
        }
        .btn:hover {
            background-color: #0056b3;
        }
        .btn-block {
            display: block;
            width: 100%;
        }
        .email-content {
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
            padding: 30px;
            border: 1px solid #dddddd;
            border-radius: 5px;
            box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.1);
        }
        .email-content p {
            margin-bottom: 20px;
        }
        .email-content a {
            color: #007bff;
        }
    </style>
</head>
<body>
    <div class="email-content">
        <h1>Forgot Password Email</h1>
        
        <p>You can reset your password by clicking the button below:</p>

        <p>
            <a href="{{ route('reset.password.get', $remember_token) }}">
                <button class="btn btn-block btn-info" >Reset Password </button></a>
        </p>
        
        <p>If you did not request a password reset, please ignore this email.</p>
    </div>
</body>
</html>
