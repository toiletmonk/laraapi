<?php

namespace App\Services;

use App\Actions\ChangePasswordAction;
use App\Actions\LoginUserAction;
use App\Actions\LogoutUserAction;
use App\Actions\RegisterUserAction;
use App\Models\User;

class AuthService
{
    protected RegisterUserAction $registerAction;
    protected LoginUserAction $loginAction;
    protected LogoutUserAction $logoutAction;
    protected ChangePasswordAction $changePasswordAction;

    public function __construct(
        RegisterUserAction $registerAction,
        LoginUserAction $loginAction,
        LogoutUserAction $logoutAction,
        ChangePasswordAction $changePasswordAction,
    ) {
        $this->registerAction = $registerAction;
        $this->loginAction = $loginAction;
        $this->logoutAction = $logoutAction;
        $this->changePasswordAction = $changePasswordAction;
    }
    public function register(array $data): User
    {
        return $this->registerAction->execute($data);
    }
    public function login(array $data): String
    {
        return $this->loginAction->execute($data);
    }
    public function logout(): void
    {
        $this->logoutAction->execute();
    }

    public function changePassword(array $data)
    {
        return $this->changePasswordAction->execute($data);
    }
}
