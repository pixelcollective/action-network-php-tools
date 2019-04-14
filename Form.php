<?php

namespace TinyPixel\ActionNetwork;

use TinyPixel\ActionNetwork\ActionNetwork as ActionNetwork;

/**
 * Form
 *
 * Class definition for OSDI Form Objects
 *
 * @package   TinyPixel\ActionNetwork\Form
 * @copyright 2019, Tiny Pixel Collective LLC
 * @author    Kelly Mears     <developers@tinypixel.io>
 * @author    Jonathan Kissam <jonathankissam.com>
 * @license   MIT
 * @link      https://github.com/pixelcollective/action-network-toolkit
 * @see       https://actionnetwork.org/docs
 **/
class Form extends ActionNetwork
{
    /**
     * Record Form submission
     *
     * @param mixed $person
     * @param mixed $form_id
     * @param mixed $tags
     *
     * @return void
     **/
    public function recordSubmission($activist, $form_id, $tags = null)
    {
        return $this->call("forms/{$form_id}/submissions", "POST", $this->processActivist($activist));
    }
}
