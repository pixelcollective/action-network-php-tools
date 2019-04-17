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
    /**
     * ID
     *
     * @var undefined
     */
    public $id;

    /**
     * Title
     *
     * @var undefined
     */
    public $title;

    /**
     * Embed
     *
     * @var string
     */
    public $embed;

    /**
     * Record petition signature
     *
     * @param mixed $person
     * @param mixed $petition_id
     * @param mixed $comment
     * @param mixed $tags
     *
     * @return void
     **/
    public function addPerson($person, $petition_id)
    {
        return $this->call(
            "petitions/{$petition_id}/signatures",
            "POST",
            $this->processActivist($person),
        );
    }
}
