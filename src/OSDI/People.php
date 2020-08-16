<?php

namespace TinyPixel\ActionNetwork\OSDI;

use TinyPixel\ActionNetwork\OSDI\Endpoint;

/**
 * People
 *
 * @package TinyPixel\ActionNetwork
 * @license MIT
 */
class People extends Endpoint
{
    /** @var TinyPixel\ActionNetwork\OSDI\ActionNetwork */
    protected $api;

    protected $endpoint = 'people';

    protected $model = 'person';

    public function transformEmbedded($model, $data)
    {
        $model->setFirstName($data->given_name);
        $model->setLastName($data->family_name);
        $model->setAddresses($data->postal_addresses);
        $model->setEmails($data->email_addresses);
        $model->setSubmissions($this->app->get('submissions')->request($data->links->get('submissions')));

        return $model;
    }
}
