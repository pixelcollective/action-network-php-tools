<?php

namespace TinyPixel\ActionNetwork;

use TinyPixel\ActionNetwork\ActionNetwork as ActionNetwork;

use \WP_Rest_Response;

/**
 * ActionNetwork\WordPressAPI
 *
 * Make Action Network resources available via the
 * WordPress REST API
 *
 * @package    TinyPixel\ActionNetwork\WordPressAPI
 * @copyright  2019, Tiny Pixel Collective LLC
 * @author     Kelly Mears     <developers@tinypixel.io>
 * @author     Jonathan Kissam <jonathankissam.com>
 * @license    MIT
 * @link       https://github.com/pixelcollective/action-network-toolkit
 * @see        https://actionnetwork.org/docs
 **/
class WordPressAPI
{
    protected $service;

    public function __construct($api_key)
    {
        $this->service = new ActionNetwork($api_key);
        $this->registerRoutes();
    }

    public function registerRoutes()
    {
        add_action('rest_api_init', function () {
            register_rest_route(
                'action-network/v2',
                '/resources/donation/',
                [
                    'methods' => 'GET',
                    'callback' => [$this, 'donationFormsEndpoint'],
                ]
            );
            register_rest_route(
                'action-network/v2',
                '/resources/forms/',
                [
                    'methods' => 'GET',
                    'callback' => [$this, 'formsEndpoint'],
                ]
            );
        });
    }

    public function donationFormsEndpoint()
    {
        $data = $this->service->getAllFundraisingPages();
        $responseObj = [];
        foreach ($data as $donationPage) :
            $responseObj[] = $this->service->getEmbed('fundraising_pages', $donationPage['id']);
        endforeach;
        return new WP_REST_Response($responseObj, 200);
    }

    public function formsEndpoint()
    {
        $data = $this->service->getAllForms();
        $responseObj = [];
        foreach ($data as $form) :
            $responseObj[] = $this->service->getEmbed('forms', $form['id']);
        endforeach;
        return new WP_REST_Response($responseObj, 200);
    }
}
