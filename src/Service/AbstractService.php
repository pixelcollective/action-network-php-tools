<?php

namespace TinyPixel\ActionNetwork\Service;

use TinyPixel\ActionNetwork\Container\Container;

abstract class AbstractService
{
    /** @var Container */
    public $app;

    /**
     * Class constructor.
     */
    public function __construct(Container $app) {
        $this->app = $app;
    }
}
