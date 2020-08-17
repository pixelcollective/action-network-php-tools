<?php

namespace TinyPixel\ActionNetwork\OSDI;

use TinyPixel\ActionNetwork\Service\AbstractService;

class Request extends AbstractService
{
    /**
     * Boot service.
     */
    public function boot()
    {
        $this->api = $this->app->get('api');
        $this->collection = $this->app->get('collection');
        $this->config = $this->app->get('config')->action_network;
    }

    /**
     * Request
     */
    public function request($request = null, $page = false)
    {
        $data = $this->collection::make(
            $this->api->get($this->getRequestUrl($request), $page)
        )->mapWithKeys(function ($item, $key) {
            $value = is_string($item) ? $this->removeBaseUrl($item) : $item;

            return [$this->removeIdPrefixes($key) => $value];
        });

        if ($data->has('_links')) {
            $data->put('links', $this->transformLinks($data->get('_links')));
            $data->forget('_links');
        }

        if ($data->has('_embedded')) {
            $data->put('embedded', $this->collection::make($data->get('_embedded'))->first());
            $data->forget('_embedded');
        }

        return (object) $data->toArray();
    }

    public function transformLinks($links = [])
    {
        if (! empty($links)) {
            $links = $this->collection::make($links)->mapWithKeys(
                function ($link, $key) {
                    if (is_array($link)) {
                        $link = $this->collection::make($link)->mapWithKeys(
                            function ($link, $key) {
                                return [$this->removeIdPrefixes($key) => $this->removeBaseUrl($link->href)];
                            }
                        );
                    }

                    if (property_exists($link, 'href')) {
                        $link = $this->removeBaseUrl($link->href);
                    }

                    return [$this->removeIdPrefixes($key) => $link];
                }
            );
         }

         return $links;
    }

    public function getRequestUrl($request)
    {
        return $this->removeBaseUrl($request);
    }

    /**
     * Remove ID prefix.
     *
     * @param string $id
     * @return string
     */
    public function removeIdPrefixes(string $id): string
    {
        return $this->collection::make(explode(":", $id))->last();
    }

    /**
     * Remove base URL from request string.
     *
     * @param string $url
     * @return string
     */
    public function removeBaseUrl(string $url)
    {
        $url = str_replace("{$this->config->base_url}/", '', $url);
        $url = str_replace("{$this->config->version}/", '', $url);

        return $url;
    }
}