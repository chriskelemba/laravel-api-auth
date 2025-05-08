<?php

namespace App\Services\Auth;

use Auth;
use Mail;
use App\Models\User;
use App\Events\UserLoginFailed;
use App\Mail\SecurityTeamAlert;
use App\Mail\UserBlockedNotification;
use App\Interfaces\AuthRepositoryInterface;
use App\Interfaces\EmailRepositoryInterface;
use App\Exceptions\Custom\EmailNotVerifiedException;
use App\Exceptions\Custom\UserAuthorizationException;
use App\Exceptions\Custom\UserAuthenticationException;
use App\Exceptions\Custom\EmailAlreadyVerifiedException;

class AuthService
{
    protected $authRepository;
    protected $emailRepository;

    public function __construct(
        AuthRepositoryInterface $authRepository,
        EmailRepositoryInterface $emailRepository
    ) {
        $this->authRepository = $authRepository;
        $this->emailRepository = $emailRepository;
    }

    // Handle user registration
    public function register(array $data): array
    {
        $user = $this->authRepository->register($data);

        $this->emailRepository->sendVerificationEmail($user);
        $this->emailRepository->sendWelcomeEmail($user);

        $token = $this->authRepository->generateToken($user);

        $user->token = $token;

        return [
            'user' => $user,
            'token' => $token,
        ];
    }

    // Handle user login
    public function login(array $credentials): array
    {
        // First find the user by email
        $user = User::where('email', $credentials['email'])->first();

        // Immediately check if user exists and is blocked
        if ($user && $user->blocked === 'Y') {
            throw new UserAuthorizationException('Account is blocked. Please contact admin.');
        }

        // Verify credentials
        if (!Auth::attempt($credentials)) {
            if ($user) {
                // Increment attempts and check blocking in a transaction
                \DB::transaction(function () use ($user) {
                    $user->increment('login_attempts');
                    $user->refresh(); // Get fresh data

                    if ($user->login_attempts >= 5) {
                        $user->update(['blocked' => 'Y']);

                        // Send notifications
                        Mail::to($user->email)->queue(new UserBlockedNotification($user));
                        Mail::to('premtube822@gmail.com')->queue(new SecurityTeamAlert($user));
                    }
                });
            }
            throw new UserAuthenticationException('Invalid credentials');
        }

        // Reset attempts on successful login
        $user->update([
            'login_attempts' => 0,
            'blocked' => 'N'
        ]);

        if (!$user->hasVerifiedEmail()) {
            throw new EmailNotVerifiedException("Please verify your email address.");
        }

        $token = $this->authRepository->generateToken($user);

        return [
            'user' => $user,
            'token' => $token,
            'email_verified' => $user->hasVerifiedEmail(),
        ];
    }
    // Handle user logout
    public function logout(): void
    {
        $this->authRepository->logout();
    }

    // handle resend verification email

    public function resendVerificationEmail($user)
    {
        if ($user->hasVerifiedEmail()) {
            throw new EmailAlreadyVerifiedException();
        }

        return $this->emailRepository->resendVerificationEmail($user);
    }

    public function user()
    {
        $user = $this->authRepository->user();

        if (!$user) {
            throw new \Exception('No authenticated user');
        }

        return $user;
    }
}
