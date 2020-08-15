<?php

namespace TinyPixel\ActionNetwork\Container;

use TinyPixel\ActionNetwork\Container\AbstractContainer;
use TinyPixel\ActionNetwork\Container\ContainerInterface;

/**
 * Container
 *
 * @package Roots\Bud
 * @author  Kelly Mears
 */
class Container extends AbstractContainer implements ContainerInterface
{
    /**
     * Get
     *
     * @param  string $id
     * @return mixed
     */
    public function get($id)
    {
        return $this->offsetGet($id);
    }

    /**
     * Set
     *
     * @param  string $id
     * @return void
     */
    public function set($id, $value)
    {
        $this->offsetSet($id, $value);
    }

    /**
     * Has
     *
     * @param  string $id
     * @return bool
     */
    public function has($id)
    {
        return $this->offsetExists($id);
    }
}
