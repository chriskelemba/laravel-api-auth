<!DOCTYPE html>
<html>
<head>
    <title>Welcome to {{ config('app.name') }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #2680DF;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }
        .content {
            padding: 30px;
            background-color: #f9f9f9;
            border-radius: 0 0 5px 5px;
        }
        .button {
            display: inline-block;
            padding: 12px 24px;
            background-color: #2680DF;
            color: white !important;
            text-decoration: none;
            border-radius: 4px;
            font-weight: bold;
            margin: 20px 0;
        }
        .features {
            margin: 20px 0;
        }
        .feature {
            margin-bottom: 15px;
            padding-left: 20px;
            position: relative;
        }
        .feature:before {
            content: "✓";
            color: #2680DF;
            position: absolute;
            left: 0;
        }
        .footer {
            margin-top: 30px;
            font-size: 12px;
            color: #777;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Welcome to {{ config('app.name') }}</h1>
    </div>
    
    <div class="content">
        <p>Hello {{ $user->name }},</p>
        
        <p>Thank you for registering with us. We're excited to have you on board!</p>
        
        <div class="features">
            <div class="feature">Complete your profile</div>
            <div class="feature">Explore our features</div>
            <div class="feature">Get started with your first project</div>
        </div>
        
        <p>If you have any questions, feel free to contact our support team.</p>
        
        <div class="footer">
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>