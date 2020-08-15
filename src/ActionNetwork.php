<?php

namespace TinyPixel\ActionNetwork;

use TinyPixel\ActionNetwork\Service\AbstractService;

/**
 * Action Network
 *
 * @package   TinyPixel\ActionNetwork
 * @copyright 2020, Tiny Pixel Collective LLC
 * @author    Kelly Mears <developers@tinypixel.dev>
 * @author    Jonathan Kissam <jonathankissam.com>
 * @license   MIT
 */
class ActionNetwork extends AbstractService
{
    /** @var object AN config*/
    private $config;

    /** @var object Client */
    protected $client;

    /**
     * Register service.
     */
    public function register()
    {
        // --
    }

    /**
     * Boot service.
     */
    public function boot()
    {
        $this->client = $this->app->get('client');
        $this->config = $this->app->get('config')->action_network;
        $this->collection = $this->app->get('collection');
    }

    /**
     * GET handler.
     *
     * @param string $reqUri
     * @return mixed
     */
    public function get(string $resource, $id = null)
    {
        $response = $this->makeRequest('GET', $this->endpoint($resource, $id));

        return $this->parseGetResponse([
            'id' => $id,
            'response' => $response,
            'resource' => $resource,
        ]);
    }

    /**
     * POST handler.
     *
     * @param string $reqUri
     * @return mixed
     */
    public function post(object $request, string $resource, $id = null)
    {
        $response = $this->makeRequest('POST', $this->endpoint($resource, $id), [
            'JSON' => json_encode($request),
            'Content-Length' => strlen(json_encode($request)),
        ],);

        return $this->parsePOSTResponse([
            'id' => $id,
            'response' => $response,
            'resource' => $resource,
        ]);
    }

    /**
     * Make request
     *
     * @param string method
     * @param string url
     * @param array  headers
     * @return mixed
     */
    public function makeRequest(
        string $method,
        string $url,
        array $headers = []
    ) {
        return $this->client->request($method, $url, [
            'headers' => array_merge([
                'Accept' => $this->config->type,
                'User-Agent' => $this->config->agent,
                'OSDI-API-Token' => $this->config->api_key,
            ], $headers)
        ]);
    }

    /**
     * Parse POST response.
     */
    public function parsePOSTResponse($data) {
        dd($data);
    }

    /**
     * Parse GET response.
     *
     * @param object $response
     * @return object
     */
    public function parseGetResponse($data)
    {
        $response = json_decode($data['response']->getBody()->getContents());

        /**
         * Resource
         */
        if ($data['resource'] == 'petitions') {
            $object = $this->normalizePetitionResponse(
                $response->_embedded->{'osdi:petitions'}
            );

            return $object;
        }

        if ($data['resource'] == 'people') {
            $object = $this->normalizePersonResponse(
                $response->_embedded->{'osdi:people'}
            );

            return $object;
        }

        return $response;
    }

    /**
     * Petition response.
     */
    protected function normalizePetitionResponse($raw)
    {
        $object = $this->collection::make($raw)
            ->map(function ($petition) {
                return $this->app->get('petition')->make($petition);
            });

        return $object;
    }

    /**
     * Petition response.
     */
    protected function normalizePersonResponse($raw)
    {
        $object = $this->collection::make($raw)
            ->map(function ($person) {
                return $this->app->get('person')->make($person);
            });

        return $object;
    }

    /**
     * Returns the full request URL for a given endpoint
     */
    protected function endpoint(string $uri)
    {
        return join('/', [
            $this->config->base,
            $this->config->version,
            $this->removeBase($uri),
        ]);
    }

    /**
     * Remove base URL from request string.
     */
    protected function removeBase(string $url)
    {
        return str_replace($this->config->base, '', $url);
    }
}
