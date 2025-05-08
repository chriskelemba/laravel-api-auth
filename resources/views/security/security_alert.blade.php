<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Security Alert</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f8fafc; padding: 20px;">
    <div style="max-width: 600px; margin: auto; background-color: #ffffff; border: 1px solid #e2e8f0; border-radius: 8px; padding: 20px;">
        <h2 style="color: #dc2626; border-bottom: 2px solid #dc2626; padding-bottom: 10px;">🚨 Security Alert: User Blocked</h2>

        <p style="font-size: 16px; color: #334155;">A user account has been automatically blocked due to multiple failed login attempts.</p>

        <table style="width: 100%; font-size: 14px; color: #334155; margin-top: 20px;">
            <tr>
                <td><strong>Name:</strong></td>
                <td>{{ $user->name }}</td>
            </tr>
            <tr>
                <td><strong>Email:</strong></td>
                <td>{{ $user->email }}</td>
            </tr>
            <tr>
                <td><strong>IP Address:</strong></td>
                <td>{{ request()->ip() }}</td>
            </tr>
            <tr>
                <td><strong>Time:</strong></td>
                <td>{{ now()->toDayDateTimeString() }}</td>
            </tr>
        </table>

        <p style="margin-top: 30px;">Please investigate this incident through the admin dashboard.</p>

        <p style="margin-top: 40px; font-size: 12px; color: #64748b;">This is an automated message from the security system.</p>
    </div>
</body>
</html>
