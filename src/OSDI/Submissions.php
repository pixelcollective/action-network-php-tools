<?php

namespace TinyPixel\ActionNetwork\OSDI;

use TinyPixel\ActionNetwork\OSDI\Endpoint;

/**
 * People
 *
 * @package TinyPixel\ActionNetwork
 * @license MIT
 */
class Submissions extends Endpoint
{
    /** @var TinyPixel\ActionNetwork\OSDI\ActionNetwork */
    protected $api;

    protected $endpoint = 'submissions';

    protected $model = 'submission';

    public function transformEmbedded($model, $data)
    {
        $model->setCreated($data->created_date);
        $model->setModified($data->modified_date);
        $model->setReferrer($data->{"action_network:referrer_data"});
        $model->setLinks($data->links);

        return $model;
    }
}
