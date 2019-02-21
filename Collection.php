<?php

namespace TinyPixel\ActionNetwork;

use TinyPixel\ActionNetwork\ActionNetwork as ActionNetwork;

use function TinyPixel\ActionNetwork\getResourceId as getResourceId;
use function TinyPixel\ActionNetwork\getResourceTitle as getResourceTitle;

/**
 * Collection
 *
 * Handling for collections of Action Network Resources
 *
 * @package   TinyPixel\ActionNetwork\Collection
 * @copyright 2019, Tiny Pixel Collective LLC
 * @author    Kelly Mears     <developers@tinypixel.io>
 * @author    Jonathan Kissam <jonathankissam.com>
 * @license   MIT
 * @link      https://github.com/pixelcollective/action-network-toolkit
 * @see       https://actionnetwork.org/docs
 **/
class Collection extends ActionNetwork
{
    public $collection;

    /**
     * getList
     *
     * @param string resourceType
     *
     * @return object resource [id, name]
     **/
    public function getList($resourceType)
    {
        $this->collection = $this->getFullSimpleCollection($resourceType);
        return $this->collection;
    }

    /**
     * getEmbedCodes
     *
     * @param mixed $resource
     * @param mixed $array
     *
     * @return void
     */
    public function getEmbedCodes($resource, $array = false)
    {
        $embed_endpoint = isset($resource->_links->{'action_network:embed'}->href) ?
            $resource->_links->{'action_network:embed'}->href : null;

        if (!$embed_endpoint) :
            return (is_array($array)) ? array() : null;
        endif;

        $embed_codes = $this->call($embed_endpoint);
        return $array ? (array) $embed_codes : $embed_codes;
    }

    /**
     * getCollection
     *
     * @param mixed $endpoint
     * @param mixed $page
     * @param mixed $per_page
     *
     * @return void
     */
    public function getCollection($endpoint, $page = 1, $per_page = null)
    {
        if ($page > 1) :
            $endpoint = '?page='.$page;
        endif;
        if ($per_page) :
            $endpoint .= ($page > 1) ? '&' : '?' . 'per_page=' . $per_page;
        endif;
        return $this->call($endpoint);
    }

    /**
     * getSimpleCollection
     *
     * @param mixed $endpoint
     * @param mixed $page
     * @param mixed $per_page
     *
     * @return void
     */
    public function getSimpleCollection($endpoint, $page = 1, $per_page = null)
    {
        $collection = $this->getCollection($endpoint, $page, $per_page);
        return $this->simplifyCollection($collection, $endpoint);
    }

    /**
     * getFullSimpleCollection
     *
     * @param mixed $endpoint
     *
     * @return void
     */
    public function getFullSimpleCollection($endpoint)
    {
        $response = $this->call($endpoint);
        if (isset($response->total_pages)) :
            if ($response->total_pages > 1) :
                $full_simple_collection = $this->simplifyCollection($response, $endpoint);
                for ($page=2; $page<=$response->total_pages; $page++) :
                    $response = $this->getCollection($endpoint, $page);
                    $full_simple_collection = array_merge(
                        $full_simple_collection,
                        $this->simplifyCollection($response, $endpoint)
                    );
                endfor;
                return $full_simple_collection;
            else :
                return $this->simplifyCollection($response, $endpoint);
            endif;
        else :
            $full_simple_collection = $this->simplifyCollection($response, $endpoint);
            $next_page = $this->getNextPage($response);
            while ($next_page) :
                $response = $this->getCollection($next_page);
                $full_simple_collection = array_merge(
                    $full_simple_collection,
                    $this->simplifyCollection($response, $endpoint)
                );
                $next_page = $this->getNextPage($response);
            endwhile;
            return $full_simple_collection;
        endif;
    }

    /**
     * getNextPage
     *
     * @param  mixed $response
     * @return void
     */
    public function getNextPage($response)
    {
        return isset($response->_links) &&
               isset($response->_links->next) &&
               isset($response->_links->next->href) ?
                    $response->_links->next->href : false;
    }

    /**
     * simplifyCollection
     *
     * @param mixed $response
     * @param mixed $endpoint
     * @param array $collection
     *
     * @return array $collection
     */
    public function simplifyCollection($response, $endpoint, $collection = array())
    {
        $osdi = 'osdi:'.$endpoint;
        $collection = [];
        if (isset($response->_embedded->$osdi)) :
            $collection_full = $response->_embedded->$osdi;
            foreach ($collection_full as $item) :
                $item_id = getResourceId($item);
                $item_title = getResourceTitle($item);
                $collection[] = [
                    'id' => $item_id,
                    'title' => $item_title
                ];
            endforeach;
        endif;
        return $collection;
    }
}
