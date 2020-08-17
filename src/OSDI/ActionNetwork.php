<?php

namespace TinyPixel\ActionNetwork\OSDI;

use Illuminate\Support\Collection;
use TinyPixel\ActionNetwork\Service\AbstractService;

/**
 * Action Network
 *
 * @package   TinyPixel\ActionNetwork
 * @license   MIT
 */
class ActionNetwork extends AbstractService
{
    /** @var object AN config*/
    private $config;

    /** @var object Client */
    protected $client;

    /**
     * Boot service.
     */
    public function boot()
    {
        $this->client = $this->app->get('api.client');
        $this->config = $this->app->get('config')->action_network;
        $this->collection = $this->app->get('collection');
    }

    /**
     * GET handler.
     *
     * @param string $reqUri
     * @return mixed
     */
    public function get(string $request, $page = false)
    {
        if (! $data = $this->makeRequest('GET', $this->requestUrl($request, $page))) {
            return;
        }

        return $data;
    }

    /**
     * Make request
     *
     * @param string method
     * @param string url
     * @param array  headers
     * @return mixed
     */
    public function makeRequest($method, $url, $headers = []) {
        $headers = array_merge($headers, [
            'Accept' => $this->config->type,
            'User-Agent' => $this->config->agent,
            'OSDI-API-Token' => $this->config->api_key,
        ]);

        return json_decode(
            $this->client->request($method, $url, [
                'headers' => $headers,
            ])
            ->getBody()
            ->getContents()
        );
    }

    /**
     * Returns the full request URL for a given entrypoint
     */
    protected function requestUrl(
        string $request,
        $page = false
    ) {
        $entrypoint = $this->formatUrl($request);

        if ($page) {
            $entrypoint = join('?', [$entrypoint, "page={$page}"]);
        }

        return join('/', [
            $this->config->base_url,
            $this->config->version,
            $entrypoint,
        ]);
    }

    /**
     * Remove base URL from request string.
     */
    public function formatUrl(string $url)
    {
        $url = str_replace($this->config->base_url . '/', '', $url);
        $url = str_replace($this->config->version . '/', '', $url);

        return $url;
    }

    /**
     * Paging
     */
    protected function pageProperties($body)
    {
        return [
            'page' => $body->page,
            'prev' => property_exists($body->_links, 'prev') ? explode("page=", $body->_links->prev->href)[1] : false,
            'next' => property_exists($body->_links, 'next') ? explode("page=", $body->_links->next->href)[1] : false,
        ];
    }
}
