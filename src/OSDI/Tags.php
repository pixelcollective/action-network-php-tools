<?php

namespace TinyPixel\ActionNetwork\OSDI;

use TinyPixel\ActionNetwork\OSDI\Endpoint;

/**
 * People
 *
 * @package TinyPixel\ActionNetwork
 * @license MIT
 */
class Tags extends Endpoint
{
    /** @var TinyPixel\ActionNetwork\OSDI\ActionNetwork */
    protected $api;

    protected $endpoint = 'tags';

    protected $model = 'tag';

    public function transformEmbedded($model, $obj)
    {
        $model->setName($obj->name);

        return $model;
    }
}
