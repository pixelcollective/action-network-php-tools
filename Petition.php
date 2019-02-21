<?php

namespace TinyPixel\ActionNetwork;

use TinyPixel\ActionNetwork\ActionNetwork as ActionNetwork;

/**
 * Petition
 *
 * Class definition for OSDI Petition Objects
 *
 * @package   TinyPixel\ActionNetwork\Petition
 * @copyright 2019, Tiny Pixel Collective LLC
 * @author    Kelly Mears     <developers@tinypixel.io>
 * @author    Jonathan Kissam <jonathankissam.com>
 * @license   MIT
 * @link      https://github.com/pixelcollective/action-network-toolkit
 * @see       https://actionnetwork.org/docs
 **/
class Petition extends ActionNetwork
{
    public $id;
    public $title;
    public $embed;

    /**
     * recordSignature
     *
     * @param mixed $activist
     * @param mixed $petition_id
     * @param mixed $comment
     * @param mixed $tags
     *
     * @return void
     **/
    public function recordSignature($activist, $petition_id, $comment = null, $tags = null)
    {
        return $this->call(
            'petitions/'.$petition_id.'/signatures',
            'POST',
            $this->processActivist($activist)
        );
    }
}
