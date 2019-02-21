<?php

namespace TinyPixel\ActionNetwork;

use TinyPixel\ActionNetwork\ActionNetwork as ActionNetwork;
use TinyPixel\ActionNetwork\Collection as Collection;
use TinyPixel\ActionNetwork\Embed as Embed;

use function TinyPixel\ActionNetwork\checkAPI as checkAPI;

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
class WordPressAPI extends ActionNetwork
{
    public $request;
    public $responseObj;

    public function __construct()
    {
        $this->registerRoutes();
    }

    public function registerRoutes()
    {
        add_action('rest_api_init', function () {
            register_rest_route(
                'action-network/v2',
                '/resources/donationPages',
                [
                    'methods' => 'GET',
                    'callback' => [$this, 'donationFormsEndpoint'],
                ]
            );
            register_rest_route(
                'action-network/v2',
                '/resources/forms',
                [
                    'methods' => 'GET',
                    'callback' => [$this, 'formsEndpoint'],
                ]
            );
        });
    }

    public function donationFormsEndpoint()
    {
        foreach ((new Collection())->getList('fundraising_pages') as $donationPage) :
            $this->responseObj[] = (new Embed())->getEmbed('fundraising_pages', $donationPage['id']);
        endforeach;
        return new WP_REST_Response($this->responseObj, 200);
    }

    public function formsEndpoint()
    {
        foreach ((new Collection())->getList('forms') as $form) :
            $this->responseObj[] = (new Embed())->getEmbed('forms', $form['id']);
        endforeach;
        return new WP_REST_Response($this->responseObj, 200);
    }
}
