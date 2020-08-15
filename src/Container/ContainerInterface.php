<?php

namespace TinyPixel\ActionNetwork\Container;

use Psr\Container\ContainerInterface as PsrContainerInterface;

/**
 * Container Interface.
 *
 * @package   TinyPixel\ActionNetwork
 * @copyright 2019, Tiny Pixel Collective LLC
 * @author    Kelly Mears     <developers@tinypixel.io>
 * @author    Jonathan Kissam <jonathankissam.com>
 * @license   MIT
 * @link      https://github.com/pixelcollective/action-network-toolkit
 * @see       https://actionnetwork.org/docs
 */
interface ContainerInterface extends PsrContainerInterface
{
    /**
     * Get
     *
     * @param  string $id
     * @return mixed
     */
    public function get($id);

    /**
     * Set
     *
     * @param  string $id
     * @return mixed
     */
    public function set($id, $value);

    /**
     * Has
     *
     * @param  string $id
     * @return Boolean
     */
    public function has($id);
}
