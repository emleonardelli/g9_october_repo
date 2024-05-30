<?php namespace PlanetaDelEste\JWTAuth\Classes;

use Lovata\Buddies\Classes\AuthHelperManager;

/**
 * {@inheritDoc}
 */
class AuthManager extends AuthHelperManager
{
    /**
     * {@inheritDoc}
     */
    protected $userModel = \PlanetaDelEste\JWTAuth\Models\User::class;
}
