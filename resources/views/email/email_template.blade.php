<!-- filepath: /C:/laragon/www/app_to_do/resources/views/email/email_template.blade.php -->
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .header {
            text-align: center;
            padding: 10px 0;
        }

        .content {
            padding: 20px;
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            margin: 20px 0;
            background-color: #007bff;
            color: #ffffff;
            text-decoration: none;
            border-radius: 5px;
        }

        .footer {
            text-align: center;
            padding: 10px 0;
            font-size: 12px;
            color: #777777;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>Email Verification</h1>
        </div>
        <div class="content">
            <p>Hi there,</p>
            <p>Thank you for registering with us. Please click the button below to verify your email address.</p>
            <a href="{{ route('verification.email') }}?token={{ $token }}&email={{ $email }}" class="btn"
                target="_blank">Verify Email</a>
            <p>If you did not create an account, no further action is required.</p>
            <p>Thank you!</p>
        </div>
        <div class="footer">
            <p>Company Inc, 7-11 Commercial Ct, Belfast BT1 2NB</p>
            <p>If you did not request this email, please ignore it.</p>
        </div>
    </div>
</body>

</html>
