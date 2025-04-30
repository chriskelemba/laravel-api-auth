<!DOCTYPE html>
<html>
<head>
    <title>Password Reset Request</title>
    <style>
        /* Base styles */
        body {
            font-family: 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
            color: #333333;
            background-color: #f7fafc;
            margin: 0;
            padding: 0;
        }
        
        /* Container */
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        
        /* Header */
        .header {
            background-color: #2680DF;
            padding: 30px 20px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }
        
        .header h1 {
            color: #ffffff;
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }
        
        /* Content */
        .content {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 0 0 8px 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }
        
        .content p {
            margin-bottom: 20px;
            font-size: 16px;
            color: #4a5568;
        }
        
        /* Button */
        .button {
            display: inline-block;
            padding: 12px 24px;
            background-color: #2680DF;
            color: #ffffff !important;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            font-size: 16px;
            margin: 25px 0;
            text-align: center;
        }
        
        /* Footer */
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e2e8f0;
            font-size: 14px;
            color: #718096;
            text-align: center;
        }
        
        /* Expiration notice */
        .expiration-notice {
            background-color: #fffaf0;
            padding: 15px;
            border-left: 4px solid #ed8936;
            margin: 20px 0;
            font-size: 14px;
        }
        
        /* Responsive */
        @media only screen and (max-width: 600px) {
            .email-container {
                width: 100%;
                padding: 10px;
            }
            
            .content {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h1>Password Reset Request</h1>
        </div>
        
        <div class="content">
            <p>Hello,</p>
            
            <p>You're receiving this email because we received a password reset request for your account.</p>
            
            <div style="text-align: center;">
                <a href="{{ $resetUrl }}" class="button">Reset Password</a>
            </div>
            
            <div class="expiration-notice">
                <strong>Important:</strong> This password reset link will expire in 2 hours.
            </div>
            
            <p>If you didn't request a password reset, you can safely ignore this email. Your account remains secure.</p>
            
            <div class="footer">
                <p>Thanks,<br>
                The {{ config('app.name') }} Team</p>
                
                <p style="margin-top: 10px; font-size: 12px; color: #a0aec0;">
                    If you're having trouble clicking the button, copy and paste this URL into your browser:<br>
                    <span style="word-break: break-all;">{{ $resetUrl }}</span>
                </p>
            </div>
        </div>
    </div>
</body>
</html>