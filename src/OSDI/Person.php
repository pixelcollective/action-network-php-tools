<?php

namespace TinyPixel\ActionNetwork\OSDI;

use TinyPixel\ActionNetwork\OSDI\OSDIObject;

/**
 * ActionNetwork\OSDI\Person
 *
 * Class definition for OSDI Person Objects
 *
 */
class Person extends OSDIObject
{
    /**
     * Make Person object
     *
     * @param Object $raw
     * @return \TinyPixel\ActionNetwork\OSDI\Person
     */
    public function make(object $raw): Person
    {
        $this->set('first', $raw->given_name);
        $this->set('last', $raw->family_name);
        $this->set('emails', $this->collection::make($raw->email_addresses));
        $this->set('raw', $raw);

        return $this;
    }
}
