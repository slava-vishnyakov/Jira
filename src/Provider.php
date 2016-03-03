<?php

namespace SocialiteProviders\Jira;

use Laravel\Socialite\One\AbstractProvider;
use Laravel\Socialite\One\User;

class Provider extends AbstractProvider
{
    /**
     * {@inheritDoc}
     */
    public function user()
    {
        if (!$this->hasNecessaryVerifier()) {
            throw new \InvalidArgumentException('Invalid request. Missing OAuth verifier.');
        }

        $user = $this->server->getUserDetails($token = $this->getToken());

        $userObject = (new User());

        if (isset($user['extra'])) {
            $userObject = $userObject->setRaw($user['extra']);
        }

        return $userObject->map([
            'id' => array_get($user, 'key'),
            'nickname' => array_get($user, 'nickname', array_get($user, 'name')),
            'name' => array_get($user, 'displayName', array_get($user, 'name')),
            'email' => array_get($user, 'emailAddress', array_get($user, 'email')),
            'avatar' => array_get($user, 'avatarUrls.48x48', array_get($user, 'avatar')),
            'active' => array_get($user, 'active'),
            'timezone' => array_get($user, 'timeZone'),
            'locale' => array_get($user, 'locale'),
        ])->setToken($token->getIdentifier(), $token->getSecret());
    }
}
