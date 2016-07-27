<?php

namespace OldTimeGuitarGuy\Plaid\Services\Base;

use OldTimeGuitarGuy\Plaid\Contracts\User;
use OldTimeGuitarGuy\Plaid\Contracts\Credentials;
use OldTimeGuitarGuy\Plaid\Contracts\Http\Request;

/**
 * Manage Plaid Users for a given endpoint.
 */
class UserManager extends Endpoint
{
    /**
     * Plaid Request object
     *
     * @var \OldTimeGuitarGuy\Plaid\Contracts\Http\Request
     */
    protected $request;

    /**
     * The service endpoint for the user we're managing
     *
     * @var string
     */
    protected $endpoint;

    /**
     * Create an new instance of UserManager
     *
     * @param Request $request
     * @param string  $endpoint
     */
    public function __construct(Request $request, $endpoint)
    {
        $this->request = $request;
        $this->endpoint = $endpoint;
    }

    ////////////////////
    // PUBLIC METHODS //
    ////////////////////

    /**
     * Add a User
     *
     * @param string      $type        The institution code that you want to access.
     * @param Credentials $credentials
     * @param array       $options
     */
    public function add($type, Credentials $credentials, $options = [])
    {
        return $this->request->post($this->endpoint(), [
            'type' => $type,
            'username' => $credentials->username(),
            'password' => $credentials->password(),
            'pin' => $credentials->pin(),
            'options' => $options,
        ]);
    }

    /**
     * Submit MFA response
     *
     * @param  User   $user
     * @param  string $answer
     * @param  array  $options
     *
     * @return \OldTimeGuitarGuy\Plaid\Contracts\Http\Response
     */
    public function step(User $user, $answer, $options = [])
    {
        return $this->request->post($this->endpoint('step'), [
            'mfa' => $answer,
            'access_token' => $user->accessToken(),
            'options' => $options,
        ]);
    }

    /**
     * Update a User
     *
     * @param  User        $user
     * @param  Credentials $credentials
     *
     * @return \OldTimeGuitarGuy\Plaid\Contracts\Http\Response
     */
    public function update(User $user, Credentials $credentials)
    {
        return $this->request->patch($this->endpoint(), [
            'username' => $credentials->username(),
            'password' => $credentials->password(),
            'pin' => $credentials->pin(),
            'access_token' => $user->accessToken(),
        ]);
    }

    /**
     * Delete a User
     *
     * @param  User   $user
     *
     * @return \OldTimeGuitarGuy\Plaid\Contracts\Http\Response
     */
    public function delete(User $user)
    {
        return $this->request->delete($this->endpoint(), [
            'access_token' => $user->accessToken(),
        ]);
    }

    ///////////////////////
    // PROTECTED METHODS //
    ///////////////////////

    /**
     * Get the main endpoint for this service
     *
     * @param  string|null $path
     * 
     * @return string
     */
    protected function endpoint($path = null)
    {
        return $this->path($this->endpoint, $path);
    }
}
