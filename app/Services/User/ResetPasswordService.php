<?php

namespace App\Services\User;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Exceptions\Custom\NotFoundException;
use App\Exceptions\Custom\UnauthorizedException;

class ResetPasswordService
{
    public function handle(array $data)
    {
        //
    }

    public function execute(int $id, string $oldPassword, string $newPassword): void
    {
        $user = User::find($id);

        if (!$user) {
            throw new NotFoundException('User');
        }

        if (!Hash::check($oldPassword, $user->password)) {
            throw new UnauthorizedException('Old password is incorrect.');
        }

        $user->password = Hash::make($newPassword);
        $user->save();
    }
}
