<?php

namespace TinyPixel\ActionNetwork;

use TinyPixel\ActionNetwork\ActionNetwork as ActionNetwork;
use TinyPixel\ActionNetwork\Collection as Collection;
use TinyPixel\ActionNetwork\Embed as Embed;

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
                '/resources/donation-forms',
                ['methods' => 'GET', 'callback' => [$this, 'fundraisingPages']]
            );

            register_rest_route(
                'action-network/v2',
                '/resources/forms',
                ['methods' => 'GET', 'callback' => [$this, 'forms']]
            );

            register_rest_route(
                'action-network/v2',
                '/resources/petitions',
                ['methods' => 'GET', 'callback' => [$this, 'petitions']]
            );
        });
    }

    /**
     * Rest response route for fundraising pages
     *
     * @return void
     */
    public function fundraisingPages()
    {
        foreach ((new Collection())->getList('fundraising_pages') as $donationPage) {
            $this->responseObj[] = [
                'id'    => $donationPage['id'],
                'name'  => $donationPage['title'],
                'embed' => (new Embed())->getEmbed('fundraising_pages', $donationPage['id']),
            ];
        }

        return new WP_REST_Response($this->responseObj, 200);
    }


    /**
     * Rest response route for forms
     *
     * @return void
     */
    public function forms()
    {
        foreach ((new Collection())->getList('forms') as $form) {
            $this->responseObj[] = [
                'id'    => $form['id'],
                'name'  => $form['title'],
                'embed' => (new Embed())->getEmbed('forms', $form['id']),
            ];
        }

        return new WP_REST_Response($this->responseObj, 200);
    }


    /**
     * REST response route for petitions
     *
     * @return void
     */
    public function petitions()
    {
        foreach ((new Collection())->getList('petitions') as $petition) {
            $this->responseObj[] = [
                'id'    => $petition['id'],
                'name'  => $petition['title'],
                'embed' => (new Embed())->getEmbed('petitions', $petition['id']),
            ];
        }

        return new WP_REST_Response($this->responseObj, 200);
    }
}
