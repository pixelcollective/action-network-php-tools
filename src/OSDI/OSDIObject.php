<?php

namespace TinyPixel\ActionNetwork\OSDI;

use \ArrayAccess;
use TinyPixel\ActionNetwork\Service\AbstractService;
use TinyPixel\ActionNetwork\Traits\Container;

/**
 * Message
 *
 * Class definition for OSDI Message Objects
 */
abstract class OSDIObject implements ArrayAccess
{
    use Container;

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
        $this->osdiPrefix = $app->get('config')->action_network->osdi_prefix;
    }

    /**
     * Remove ID prefix.
     */
    protected function removeOSDIPrefix(string $id): string
    {
        return str_replace("{$this->osdiPrefix}:", '', $id);
    }
}
