<?php

namespace TinyPixel\ActionNetwork;

use \GuzzleHttp\Client;
use \TinyPixel\ActionNetwork\WordPressAPI;

/**
 * Action Network
 *
 * API wrapper for Action Network.
 * Extension of original work by Jonathan Kissam.
 *
 * @package   TinyPixel\ActionNetwork
 * @copyright 2019, Tiny Pixel Collective LLC
 * @author    Kelly Mears     <developers@tinypixel.io>
 * @author    Jonathan Kissam <jonathankissam.com>
 * @license   MIT
 * @link      https://github.com/pixelcollective/action-network-toolkit
 * @see       https://actionnetwork.org/docs
 **/
class ActionNetwork
{
    /**
     * Instance
     *
     * @var object
     */
    private static $instance;

    /**
     * API Key
     *
     * @var string
     */
    protected static $api_key;

    /**
     * Guzzle Client
     *
     * @var object
     */
    protected $guzzle;

    /**
     * API Version
     *
     * @var string
     */
    protected $api_version  = '2';

    /**
     * Action Network API Base URL
     *
     * @var string
     */
    protected $api_base_url = 'https://actionnetwork.org/api/v2/';

    /**
     * Error references
     *
     * @var array
     */
    protected $errors;

    /**
     * __construct
     *
     * @param mixed $api_key
     * @return void
     */
    public function __construct($api_key = null)
    {
        $this->guzzle = new Client();
        $this->wordpress_api = new WordPressAPI();
    }

    /**
     * getInstance
     *
     * Share singular Action Network
     * connection to all subpackages
     *
     * @param string $api_key
     * @return void
     */
    public static function getInstance($api_key = null)
    {
        if (!isset(self::$instance)) :
            self::$api_key =  $api_key ?? '';
            self::$instance = new ActionNetwork($api_key);
        endif;

        return self::$api_key ? self::error('api key not set') : self::$instance;
    }

    /**
     *
     * Generic API Call
     *
     * @param string endpoint
     * @param string method
     * @param string req
     *
     * @return object $response JSON response from Action Network
     */
    public function call($endpoint, $req = null)
    {
        /**
         * Remove base URL from endpoint string, if there is a match.
         *
         * ActionNetwork returns full URLs from requests, but our initial
         * request utilizes a relative URL for convenience.
         */
        $endpoint = str_replace($this->api_base_url, '', $endpoint);

        /**
         * For calls without a $req parameter we'll use this
         */
        $request_url = $this->api_base_url.$endpoint;

        if ($req) {
            $response = $this->guzzle->request('POST', $endpoint, [
                'headers' => [
                    'User-Agent'     => 'testing/1.0',
                    'Accept'         => 'application/json',
                    'OSDI-API-Token' => $this::$api_key,
                    'Content-Length' => strlen(json_encode($req)),
                    'JSON'           => json_encode($req),
                ],
            ]);
        } else {
            $response = $this->guzzle->request('GET', $request_url, [
                'headers' => [
                    'User-Agent'     => 'testing/1.0',
                    'Accept'         => 'application/json',
                    'OSDI-API-Token' => $this::$api_key,
                    'JSON'           => $req,
                ],
            ]);
        }

        return json_decode($response->getBody()->getContents());
    }

    /**
     * Parse OSDI resource identifier from given resource
     *
     * @param  array $resource
     *
     * @return void
     */
    public static function getResourceId($resource)
    {
        if (isset($resource->identifiers) ||
            is_array($resource->identifiers)) :
            foreach ($resource->identifiers as $identifier) {
                if (substr($identifier, 0, 15) == 'action_network:') {
                    return substr($identifier, 15);
                }
            }
        endif;
    }

    /**
     * Parse usable title from given resource
     *
     * @param mixed $resource
     *
     * @return void
     */
    public static function getResourceTitle($resource)
    {
        if (isset($resource->title)) {
            return $resource->title;
        }

        if (isset($resource->name)) {
            return $resource->name;
        }

        if (isset($resource->email_addresses)
                && is_array($resource->email_addresses)
                && count($resource->email_addresses)) {
                    return $resource->email_addresses[0]->address;
        }
    }

    public static function error($error)
    {
        trigger_error($error, E_USER_ERROR);
    }
}
