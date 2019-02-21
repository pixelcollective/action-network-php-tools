<?php

namespace TinyPixel\ActionNetwork;

use TinyPixel\ActionNetwork\ActionNetwork as ActionNetwork;

/**
 * Event
 *
 * Class definition for OSDI Event Objects
 *
 * @package   TinyPixel\ActionNetwork\Event
 * @copyright 2019, Tiny Pixel Collective LLC
 * @author    Kelly Mears     <developers@tinypixel.io>
 * @author    Jonathan Kissam <jonathankissam.com>
 * @license   MIT
 * @link      https://github.com/pixelcollective/action-network-toolkit
 * @see       https://actionnetwork.org/docs
 **/
class Event extends ActionNetwork
{
    public $id;
    public $title;
    public $location;
    public $description;
    public $embed;

    /**
     * recordAttendance
     *
     * @param mixed $activist
     * @param mixed $event_id
     * @param mixed $tags
     *
     * @return void
     **/
    public function recordAttendance($activist, $event_id, $tags = null)
    {
        return $this->call(
            'events/'.$event_id.'/attendances',
            'POST',
            $this->processActivist($activist)
        );
    }
}
