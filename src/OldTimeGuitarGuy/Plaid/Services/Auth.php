<?php

namespace OldTimeGuitarGuy\Plaid\Services;

/**
 * The /auth endpoint allows you to collect a user's bank account and
 * routing number, along with basic account data and balances. The
 * product performs two crucial functions—it translates bank access
 * credentials (username & password) into an accurate account and
 * routing number. No input of account or routing number is necessary.
 * Secondly it validates that this is the owner of this account number,
 * in a NACHA compliant manner. No need for micro-deposits or any other
 * secondary authentication.
 *
 * https://plaid.com/docs/api/#auth
 */
class Auth extends Base\Service
{
    /**
     * The base endpoint for all requests
     *
     * @var string
     */
    protected $endpoint = '/auth';

    /**
     * Add Auth User
     *
     * https://plaid.com/docs/api/#add-auth-user
     *
     * @param string $type     The institution code that you want to access.
     * @param string $username Username associated with the user's financial institution.
     * @param string $password Password associated with the user's financial institution.
     * @param mixed  $pin      Pin number required only for USAA accounts.
     * @param array  $options
     */
    public function addUser($type, $username, $password, $pin = null, $options = [])
    {
        // Allow pin to optionally be set as options
        if ( is_array($pin) ) {
            $options = $pin;
            $pin = null;
        }
        
        return $this->request->post($this->endpoint, get_defined_vars());
    }

    /**
     * Auth MFA
     *
     * https://plaid.com/docs/api/#auth-mfa
     *
     * @param  string $access_token The access_token is returned when the user is added.
     * @param  string $mfa          The extra information needed in the format: {mfa:'xxxxx'}.
     * @param  array  $options
     * 
     * @return \OldTimeGuitarGuy\Plaid\Contracts\Http\Response
     */
    public function mfa($access_token, $mfa, $options = [])
    {
        return $this->request->post("{$this->endpoint}/step", get_defined_vars());
    }

    /**
     * Get Auth Data
     *
     * @param  string $access_token
     *
     * @return \OldTimeGuitarGuy\Plaid\Contracts\Http\Response
     */
    public function getData($access_token)
    {
        return $this->request->post("{$this->endpoint}/get", compact('access_token'));
    }

    /**
     * Update Auth User
     *
     * https://plaid.com/docs/api/#update-connect-user
     *
     * @param  string $access_token The ACCESS_TOKEN of the user you wish to update.
     * @param  string $username     Username associated with the user's financial institution.
     * @param  string $password     Password associated with the user's financial institution.
     * @param  mixed  $pin          Pin number associated with the user's financial institution. (usaa only)
     * 
     * @return \OldTimeGuitarGuy\Plaid\Contracts\Http\Response
     */
    public function updateUser($access_token, $username, $password, $pin = null)
    {
        return $this->request->patch($this->endpoint, get_defined_vars());
    }

    /**
     * Delete Auth User
     *
     * https://plaid.com/docs/api/#delete-connect-user
     *
     * @param  string $access_token The ACCESS_TOKEN that you wish to be removed from your account.
     * 
     * @return \OldTimeGuitarGuy\Plaid\Contracts\Http\Response
     */
    public function deleteUser($access_token)
    {
        return $this->request->delete($this->endpoint, compact('access_token'));
    }
}
