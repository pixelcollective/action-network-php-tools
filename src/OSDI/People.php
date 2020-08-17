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
        if (!isset($data) || !is_object($data)) {
            return $data;
        }

        property_exists($data, 'given_name')
            && $model->setFirstName($data->given_name);

        property_exists($data, 'last_name')
            && $model->setLastName($data->family_name);

        property_exists($data, 'postal_addresses')
            && $model->setAddresses($data->postal_addresses);

        property_exists($data, 'email_addresses')
            && $model->setEmails($this->collection::make($data->email_addresses));

        property_exists($data, 'links')
            && $model->setLinks($data->links->all());

        return $model;
    }
}
