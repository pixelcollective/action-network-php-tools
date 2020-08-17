<?php

namespace TinyPixel\ActionNetwork\Resource;

use \ArrayAccess;
use TinyPixel\ActionNetwork\Traits\Container;

/**
 * Message
 *
 * Class definition for OSDI Message Objects
 */
abstract class AbstractResource implements ArrayAccess
{
    use Container;

    abstract function setup();

    /**
     * Boot.
     *
     * @return void
     */
    public function register(): void
    {
        // --
    }

    /**
     * Boot.
     *
     * @return void
     */
    public function boot($app): void
    {
        $this->collection = $app->get('collection');
    }

    /**
     * Make object
     *
     * @param Object raw OSDI response
     * @return \TinyPixel\ActionNetwork\OSDI\AbstractResource
     */
    public function make(): AbstractResource
    {
        $this->setup();
        return $this;
    }
}
