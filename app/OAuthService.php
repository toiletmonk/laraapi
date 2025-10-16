<?php

namespace App;

use App\Models\User;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Contracts\User as ProviderUser;

class OAuthService
{
    protected $providers = ['google', 'facebook', 'github'];

    public function redirect($provider)
    {
        if (!in_array($provider, $this->providers)) {
            throw new \Exception('Invalid provider');
        }
        return Socialite::driver($provider)->stateless()->redirect();
    }

    public function callback($provider): array
    {
        if (!in_array($provider, $this->providers)) {
            throw new \Exception('Invalid provider');
        }
        $providerUser = Socialite::driver($provider)->stateless()->user();
        $user = $this->findOrCreateUser($providerUser, $provider);
        $token = $user->createToken('api-token')->plainTextToken;

        return [
            'token' => $token,
            'user' => $user,
        ];
    }

    protected function findOrCreateUser(ProviderUser $providerUser, string $provider)
    {
        $providerIDField = $provider . '_id';
        return User::updateOrCreate(
            ['email' => $providerUser->getEmail()],
            [
                'name' => $providerUser->getName(),
                $providerIDField => $providerUser->getId(),
                'avatar' => $providerUser->getAvatar(),
            ]
        );
    }
}
