<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <style>
        :root {
            --primary: #4361ee;
            --error: #f72585;
            --success: #4cc9f0;
            --gray: #adb5bd;
            --light-gray: #f8f9fa;
        }
        
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: #f5f7fa;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }
        
        .reset-container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 480px;
            padding: 40px;
        }
        
        h1 {
            color: #2b2d42;
            margin-bottom: 24px;
            font-size: 28px;
            font-weight: 600;
            text-align: center;
        }
        
        .form-group {
            margin-bottom: 20px;
            position: relative;
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            color: #495057;
            font-size: 14px;
            font-weight: 500;
        }
        
        input {
            width: 100%;
            padding: 14px 16px;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            font-size: 15px;
            transition: all 0.3s;
        }
        
        input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
        }
        
        .password-strength {
            height: 4px;
            background: var(--light-gray);
            border-radius: 2px;
            margin-top: 8px;
            overflow: hidden;
        }
        
        .strength-meter {
            height: 100%;
            width: 0;
            background: var(--error);
            transition: width 0.3s;
        }
        
        button {
            width: 100%;
            padding: 14px;
            background-color: var(--primary);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            transition: background 0.3s;
            margin-top: 10px;
        }
        
        button:hover {
            background-color: #3a56d4;
        }
        
        .message {
            padding: 12px;
            border-radius: 8px;
            margin-top: 20px;
            font-size: 14px;
            text-align: center;
            display: none;
        }
        
        .success-message {
            background-color: rgba(76, 201, 240, 0.1);
            color: var(--success);
            border: 1px solid var(--success);
        }
        
        .error-message {
            background-color: rgba(247, 37, 133, 0.1);
            color: var(--error);
            border: 1px solid var(--error);
        }
        
        .password-toggle {
            position: absolute;
            right: 15px;
            top: 38px;
            cursor: pointer;
            color: var(--gray);
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="reset-container">
        <h1>Reset Your Password</h1>
        
        <form id="resetPasswordForm">
            <input type="hidden" name="token" value="{{ $token }}">
            
            <div class="form-group">
                <label for="current_password">Current Password</label>
                <input type="password" id="current_password" name="current_password" required>
                <span class="password-toggle" onclick="togglePassword('current_password')">Show</span>
            </div>
            
            <div class="form-group">
                <label for="new_password">New Password</label>
                <input type="password" id="new_password" name="password" required minlength="8">
                <span class="password-toggle" onclick="togglePassword('new_password')">Show</span>
                <div class="password-strength">
                    <div class="strength-meter" id="passwordStrength"></div>
                </div>
            </div>
            
            <div class="form-group">
                <label for="confirm_password">Confirm New Password</label>
                <input type="password" id="confirm_password" name="password_confirmation" required>
                <span class="password-toggle" onclick="togglePassword('confirm_password')">Show</span>
            </div>
            
            <button type="submit" id="submitBtn">Reset Password</button>
            
            <div class="message success-message" id="successMessage"></div>
            <div class="message error-message" id="errorMessage"></div>
        </form>
    </div>

    <script>
        // Toggle password visibility
        function togglePassword(id) {
            const input = document.getElementById(id);
            const toggle = input.nextElementSibling;
            
            if (input.type === 'password') {
                input.type = 'text';
                toggle.textContent = 'Hide';
            } else {
                input.type = 'password';
                toggle.textContent = 'Show';
            }
        }
        
        // Password strength indicator
        document.getElementById('new_password').addEventListener('input', function(e) {
            const strength = calculatePasswordStrength(e.target.value);
            const meter = document.getElementById('passwordStrength');
            
            meter.style.width = `${strength}%`;
            meter.style.backgroundColor = strength < 40 ? '#f72585' : 
                                         strength < 70 ? '#f8961e' : '#4cc9f0';
        });
        
        function calculatePasswordStrength(password) {
            let strength = 0;
            
            // Length contributes up to 40%
            strength += Math.min(40, (password.length / 12) * 40);
            
            // Character variety contributes up to 60%
            if (/[A-Z]/.test(password)) strength += 10;
            if (/[0-9]/.test(password)) strength += 10;
            if (/[^A-Za-z0-9]/.test(password)) strength += 10;
            
            // Deduct for common patterns
            if (password.length < 6) strength -= 10;
            if (password === password.toLowerCase()) strength -= 5;
            
            return Math.max(0, Math.min(100, strength));
        }
        
        // Form submission
        document.getElementById('resetPasswordForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            
            // Reset messages
            document.getElementById('successMessage').style.display = 'none';
            document.getElementById('errorMessage').style.display = 'none';
            
            // Get form data
            const formData = {
                token: e.target.token.value,
                current_password: e.target.current_password.value,
                password: e.target.password.value,
                password_confirmation: e.target.password_confirmation.value
            };
            
            // Client-side validation
            if (formData.password !== formData.password_confirmation) {
                showError('Passwords do not match');
                return;
            }
            
            if (formData.password.length < 8) {
                showError('Password must be at least 8 characters');
                return;
            }
            
            // Disable button during submission
            const submitBtn = document.getElementById('submitBtn');
            submitBtn.disabled = true;
            submitBtn.textContent = 'Processing...';
            
            try {
                const response = await fetch('/api/reset-password', {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(formData)
                });
                
                const result = await response.json();
                
                if (response.ok) {
                    showSuccess(result.message || 'Password reset successfully!');

                } else {
                    showError(result.message || 'Password reset failed');
                }
            } catch (error) {
                showError('Network error. Please try again.');
            } finally {
                submitBtn.disabled = false;
                submitBtn.textContent = 'Reset Password';
            }
        });
        
        function showSuccess(message) {
            const el = document.getElementById('successMessage');
            el.textContent = message;
            el.style.display = 'block';
        }
        
        function showError(message) {
            const el = document.getElementById('errorMessage');
            el.textContent = message;
            el.style.display = 'block';
        }
    </script>
</body>
</html>