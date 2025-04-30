<?php

namespace App\Services\Auth;

use App\Exceptions\Custom\EmailAlreadyVerifiedException;
use App\Interfaces\AuthRepositoryInterface;
use App\Interfaces\EmailRepositoryInterface;

class ResendVerificationEmailService
{
    public function handle(array $data)
    {
        //
    }
    protected $emailRepository;
    protected $authRepository;

    public function __construct(
        EmailRepositoryInterface $emailRepository,
        AuthRepositoryInterface $authRepository
    ) {
        $this->emailRepository = $emailRepository;
        $this->authRepository = $authRepository;
    }

    public function execute($user)
    {
        if ($user->hasVerifiedEmail()) {
            throw new EmailAlreadyVerifiedException();
        }

        return $this->emailRepository->resendVerificationEmail($user);
    }

}
