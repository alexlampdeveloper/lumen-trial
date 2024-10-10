<?php

namespace App\Services;

use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\PasswordRecoveryEmail;

class UserService
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function register(array $data)
    {
        $data['password'] = Hash::make($data['password']);
        return $this->userRepository->create($data);
    }

    public function recoverPassword(string $email)
    {
        $user = $this->userRepository->findByEmail($email);

        if (!$user) {
            return;
        }

        // Generate a unique token for password recovery
        $token = bin2hex(random_bytes(32));

        // Save the token in the database
        $user->reset_token = $token;
        $user->save();

        // Send recovery email
        $this->sendRecoveryEmail($user->email, $token);
    }

    public function sendRecoveryEmail(string $email, string $token)
    {
        Mail::to($email)->send(new PasswordRecoveryEmail($token));
    }

    public function updatePassword(string $resetToken, string $password)
    {
        $user = $this->userRepository->findByResetToken($resetToken);

        if (!$user) {
            throw new \Exception('Invalid or expired reset token');
        }

        $user->password = Hash::make($password);
        $user->reset_token = null;
        $user->save();
    }
}
