<?php

namespace TinyPixel\ActionNetwork;

use TinyPixel\ActionNetwork\ActionNetwork as ActionNetwork;

/**
 * Embed
 *
 * Action Network Embed Code Fetching, Processing, Collecting
 *
 * @package   TinyPixel\ActionNetwork\Embed
 * @copyright 2019, Tiny Pixel Collective LLC
 * @author    Kelly Mears     <developers@tinypixel.io>
 * @author    Jonathan Kissam <jonathankissam.com>
 * @license   MIT
 * @link      https://github.com/pixelcollective/action-network-toolkit
 * @see       https://actionnetwork.org/docs
 **/
class Embed extends ActionNetwork
{
    public $id;
    public $title;
    public $embed;
    public $resource;

    /**
     * getEmbed
     *
     * get embeds for a petition, event, fundraising page, advocacy campaign or form
     *
     * @param mixed $type
     * @param mixed $id
     * @param mixed $size
     * @param mixed $style
     *
     * @return void
     **/
    public function getEmbed($type, $id, $size = 'standard', $style = 'default')
    {
        (!in_array(
            $type,
            array(
                'petitions',
                'events',
                'fundraising_pages',
                'advocacy_campaigns',
                'forms'
            )
        )) ? trigger_error($this->errors->embed->invalid_type, E_USER_ERROR) : null;

        (!in_array(
            $size,
            array(
                'standard',
                'full'
            )
        )) ? trigger_error($this->errors->embed->invalid_size, E_USER_ERROR) : null;

        (!in_array($style, ['default', 'layout_only', 'no']))
            ? trigger_error($this->errors->embed->invalid_layout, E_USER_ERROR)
            : null;

        $embeds = $this->call($type.'/'.$id.'/embed');
        $selector = 'embed_'.$size.'_'.$style.'_styles';
        return $embeds->$selector;
    }
}
