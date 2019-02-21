<?php

namespace TinyPixel\ActionNetwork;

use function TinyPixel\ActionNetwork\getErrorDefinitions as getErrorDefinitions;
use function TinyPixel\ActionNetwork\getResourceId as getResourceId;
use function TinyPixel\ActionNetwork\getResourceTitle as getResourceTitle;

use \GuzzleHttp\Guzzle;
use \GuzzleHttp\Stream\Stream;

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
    private static $instance;
    protected static $api_key;
    protected $guzzle;

    protected $api_version  = '2';
    protected $api_base_url = 'https://actionnetwork.org/api/v2/';
    protected $errors;

    public function __construct($api_key = null)
    {
        $this->errors = getErrorDefinitions();
        $this->guzzle = new \GuzzleHttp\Client();
    }

    /**
     * getInstance
     *
     * Share singular Action Network
     * connection to all subpackages
     *
     * @param [type] $api_key
     * @return void
     */
    public static function getInstance($api_key = null)
    {
        if (!isset(self::$instance)) :
            self::$api_key = checkAPI($api_key);
            self::$instance = new ActionNetwork($api_key);
        endif;

        return self::$instance;
    }

    /**
     * Call API
     *
     * Generic API Call
     *
     * @param string endpoint
     * @param string method
     * @param string req
     *
     * @return json    $response JSON Response from AN
     */
    public function call($endpoint, $method = 'GET', $req = null)
    {
        /**
         * Remove base URL from endpoint string, if there is a match.
         *
         * ActionNetwork returns full URLs from requests, but our initial
         * request utilizes a relative URL for convenience.
         */
        $endpoint = str_replace($this->api_base_url, '', $endpoint);

        if ($req) :
            $response = $this->guzzle->request(
                'POST',
                $endpoint,
                [
                    'headers' => [
                        'User-Agent'     => 'testing/1.0',
                        'Accept'         => 'application/json',
                        'OSDI-API-Token' => $this::$api_key,
                        'Content-Length' => strlen(json_encode($req)),
                        'JSON'           => json_encode($req),
                    ],
                ]
            );
        else :
            $response = $this->guzzle->request(
                'GET',
                $this->api_base_url.$endpoint,
                [
                    'headers' => [
                        'User-Agent'     => 'testing/1.0',
                        'Accept'         => 'application/json',
                        'OSDI-API-Token' => $this::$api_key,
                        'JSON'           => $req,
                    ],
                ]
            );
        endif;

        return json_decode($response->getBody()->getContents());
    }
}
