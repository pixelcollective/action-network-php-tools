<?php

namespace TinyPixel\ActionNetwork\Resource;

use TinyPixel\ActionNetwork\Resource\AbstractResource;

/**
 * ActionNetwork\OSDI\Person
 *
 * Class definition for OSDI Person Objects
 *
 */
class Tag extends AbstractResource
{
    public $name;

    public function setup()
    {
      //
    }

    public function setName($name)
    {
        $this->name = $name;
    }
}
