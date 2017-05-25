<?php

namespace OldTimeGuitarGuy\Plaid\Contracts\Http;

interface Request
{
    const SANDBOX_URI = 'https://sandbox.plaid.com';
    const DEVELOPMENT_URI = 'https://development.plaid.com/';
    const PRODUCTION_URI  = 'https://production.plaid.com/';

    /**
     * Make a post request to Plaid
     *
     * @param  string $endpoint
     * @param  array  $parameters
     * 
     * @return \OldTimeGuitarGuy\Plaid\Contracts\Http\Response
     */
    public function post($endpoint, array $parameters = []);

    /**
     * Make a patch request to Plaid
     *
     * @param  string $endpoint
     * @param  array  $parameters
     * 
     * @return \OldTimeGuitarGuy\Plaid\Contracts\Http\Response
     */
    public function patch($endpoint, array $parameters = []);

    /**
     * Make a delete request to Plaid
     *
     * @param  string $endpoint
     * @param  array  $parameters
     * 
     * @return \OldTimeGuitarGuy\Plaid\Contracts\Http\Response
     */
    public function delete($endpoint, array $parameters = []);

    /**
     * Make a get request to Plaid
     *
     * @param  string $endpoint
     * @param  array  $parameters
     * 
     * @return \OldTimeGuitarGuy\Plaid\Contracts\Http\Response
     */
    public function get($endpoint, array $parameters = []);
}
