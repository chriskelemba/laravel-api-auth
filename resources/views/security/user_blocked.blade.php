<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Account Blocked</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f8fafc; padding: 20px;">
    <div style="max-width: 600px; margin: auto; background-color: #ffffff; border: 1px solid #e2e8f0; border-radius: 8px; padding: 20px;">
        <h2 style="color: #f59e0b; border-bottom: 2px solid #f59e0b; padding-bottom: 10px;">⚠️ Account Blocked</h2>

        <p style="font-size: 16px; color: #334155;">Hello {{ $user->name }},</p>

        <p style="font-size: 15px; color: #475569;">
            We’ve noticed multiple failed login attempts to your account and, for your protection, it has been temporarily blocked.
        </p>

        <p style="font-size: 15px; color: #475569;">
            If you did not attempt to login, please contact our support team immediately.
        </p>

        <div style="margin-top: 30px;">
            <a href="mailto:support@yourdomain.com"
               style="background-color: #3b82f6; color: white; padding: 10px 20px; text-decoration: none; border-radius: 4px;">
                Contact Support
            </a>
        </div>

        <p style="margin-top: 40px; font-size: 12px; color: #64748b;">Thank you,<br>The Security Team</p>
    </div>
</body>
</html>
