<?php

namespace TinyPixel\ActionNetwork\OSDI;

use TinyPixel\ActionNetwork\Service\AbstractService;

abstract class Endpoint extends AbstractService
{
    abstract function transformEmbedded($model, $data);

    /**
     * Boot service.
     */
    public function boot()
    {
        $this->api = $this->app->get('osdi');
        $this->collection = $this->app->get('collection');
        $this->config = $this->app->get('config')->action_network;
        $this->key = "osdi:{$this->endpoint}";
    }

    /**
     * Register service.
     */
    public function register()
    {
        // --
    }

    public function request($request = null, $page = false)
    {
        $data = (object) [];

        $raw = $this->collection::make($this->api->get($request ?? $this->endpoint, $page));

        $data = $this->makeFromCollection(
            $this->collection::make($raw->get('_embedded')),
            $this->key
        )->mapWithKeys(function ($item, $key) {
            return [$this->removeIdPrefixes($key) => $item];
        })->map(function ($entry) {
            if (is_object($entry) && property_exists($entry, '_links')) {
                $entry->links = $this->transformLinks($entry->_links);
            }

            return $entry;
        })->map(function ($entry) {
            return $this->transformEmbedded($this->app->get($this->model)->make(), $entry);
        });

        return $data;
    }

    public function transformLinks($links = [])
    {
        if (! empty($links)) {
            $links = $this->collection::make($links)->mapWithKeys(function ($link, $key) {
                 if (is_array($link)) {
                     $link = $this->collection::make($link)->mapWithKeys(function ($link, $key) {
                         return [
                             $this->removeIdPrefixes($key) => $this->removeBaseUrl($link->href)
                        ];
                     });
                 }

                 if (property_exists($link, 'href')) {
                     $link = $this->removeBaseUrl($link->href);
                 }

                 return [$this->removeIdPrefixes($key) => $link];
             });
         }

         return $links;
    }

    public function makeFromCollection($obj, $property)
    {
        if (! $obj->has($property)) {
            return $obj;
        }

        $item = $obj->get($property);

        if (is_array($item) || is_object($item)) {
            return $this->collection::make($item)->mapWithKeys(
                function ($value, $key) {
                    return [$this->removeIdPrefixes($key) => $value];
                }
            );
        }

        return null;
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