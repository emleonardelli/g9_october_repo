<?php

namespace PlanetaDelEste\JWTAuth\Http\Requests;

class LoginRequest extends Request
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'login' => $this->getLoginRules(),
            'password' => 'required|between:4,255'
        ];
    }

    /**
     * Get the login validation rules
     *
     * @return string
     */
    protected function getLoginRules()
    {
        return 'required|email|between:6,255';
    }

    /**
     * Get credentials from request
     *
     * @return array
     */
    public function getCredentials()
    {
        $username = 'email';
        return [
            $username => $this->get('login'),
            'password' => $this->get('password')
        ];
    }
}
