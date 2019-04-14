<?php

namespace TinyPixel\ActionNetwork;

/**
 * function TinyPixel\ActionNetwork\getErrorDefinitions
 *
 * @return object error_defintions
 */
function getErrorDefinitions()
{
    return (object) [
        'php'   => (object) [
            'cURL' => 'ActionNetwork requires PHP cURL',
        ],

        'api'   => (object) [
            'no_api_key' => 'An API key is required'
        ],

        'embed' => (object) [
            'invalid_type'   => 'getEmbed must be passed a type of petitions, events,
                                fundraising_pages, advocacy_campaigns or forms',
            'invalid_size'   => 'getEmbed must be passed a size of standard or full',
            'invalid_layout' => 'getEmbed must be passed a style of default, layout_only or no',
        ],
    ];
}

/**
 * function TinyPixel\ActionNetwork\getResourceId
 *
 * helper functions for collections
 *
 * @param mixed $resource
 *
 * @return void
 */
function getResourceId($resource)
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
 * function TinyPixel\ActionNetwork\getResourceTitle
 *
 * @param mixed $resource
 *
 * @return void
 */
function getResourceTitle($resource)
{
    if (isset($resource->title)) :
        return $resource->title;
    endif;

    if (isset($resource->name)) :
        return $resource->name;
    endif;

    if (isset($resource->email_addresses)
        && is_array($resource->email_addresses)
            && count($resource->email_addresses)) {
                return $resource->email_addresses[0]->address;
    }
}
