<?php

namespace OldTimeGuitarGuy\Plaid\Http;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\BadResponseException;
use OldTimeGuitarGuy\Plaid\Exceptions\PlaidRequestException;
use OldTimeGuitarGuy\Plaid\Contracts\Http\Request as RequestContract;

class Request implements RequestContract
{
    /**
     * The http client
     *
     * @var \GuzzleHttp\ClientInterface
     */
    protected $http;

    /**
     * The client_id & secret in an assoc array
     *
     * @var array
     */
    protected $credentials;

    /**
     * Determines whether or not we need to use production uri
     *
     * @var boolean
     */
    protected $use_production;

    /**
     * Create a new instance of Plaid Request
     *
     * @param \GuzzleHttp\ClientInterface $http
     * @param string $client_id
     * @param string $secret
     * @param string $use_production
     */
    public function __construct(ClientInterface $http, $client_id, $secret, $use_production = 'sandbox')
    {
        $this->http = $http;
        $this->credentials = compact('client_id', 'secret');
        $this->use_production = $use_production;
    }

    /**
     * Make a post request to Plaid
     *
     * @param  string $endpoint
     * @param  array  $parameters
     * 
     * @return \OldTimeGuitarGuy\Plaid\Contracts\Http\Response
     */
    public function post($endpoint, array $parameters = [])
    {
        if ( isset($parameters['options']) ) {
            $parameters['options'] = (object)($parameters['options']);
        }

        $options = [
            'json' => array_merge(
                $this->credentials,
                $parameters
            ),
        ];

        return $this->respond('POST', $endpoint, $options);
    }

    /**
     * Make a patch request to Plaid
     *
     * @param  string $endpoint
     * @param  array  $parameters
     * 
     * @return \OldTimeGuitarGuy\Plaid\Contracts\Http\Response
     */
    public function patch($endpoint, array $parameters = [])
    {
        $options = [
            'json' => array_merge(
                $this->credentials,
                $parameters
            ),
        ];

        return $this->respond('PATCH', $endpoint, $options);
    }

    /**
     * Make a delete request to Plaid
     *
     * @param  string $endpoint
     * @param  array  $parameters
     * 
     * @return \OldTimeGuitarGuy\Plaid\Contracts\Http\Response
     */
    public function delete($endpoint, array $parameters = [])
    {
        $options = [
            'json' => array_merge(
                $this->credentials,
                $parameters
            ),
        ];

        return $this->respond('DELETE', $endpoint, $options);
    }

    /**
     * Make a get request to Plaid
     *
     * @param  string $endpoint
     * @param  array  $parameters
     * 
     * @return \OldTimeGuitarGuy\Plaid\Contracts\Http\Response
     */
    public function get($endpoint, array $parameters = [])
    {
        $options = [
            'query' => $parameters,
        ];

        return $this->respond('GET', $endpoint, $options);
    }

    ///////////////////////
    // PROTECTED METHODS //
    ///////////////////////

    /**
     * Make the request to plaid & return the response
     *
     * @param  string $method
     * @param  string $endpoint
     * @param  array $options
     * 
     * @return \OldTimeGuitarGuy\Plaid\Http\Response
     * @throws \OldTimeGuitarGuy\Plaid\Exceptions\PlaidRequestException
     */
    protected function respond($method, $endpoint, $options)
    {
        try {
            return new Response(
                $this->http->request($method, $this->uri($endpoint), $options)
            );
        }
        catch (BadResponseException $e) {
            throw new PlaidRequestException(new Response($e->getResponse()));
        }
    }

    /**
     * Build & return the uri
     *
     * @param  string $endpoint
     * 
     * @return string
     */
    protected function uri($endpoint)
    {
        return $this->base() . $endpoint;
    }

    /**
     * Get the base uri for the request
     *
     * @return string
     */
    protected function base()
    {
        switch (strtolower($this->use_production)) {
            case 'sandbox':
                return self::SANDBOX_URI;
                break;
            case 'development':
                return self::DEVELOPMENT_URI;
                break;
            case 'production':
                return self::PRODUCTION_URI;
                break;
            default:
                return self::SANDBOX_URI;
                break;
        }
    }
}
