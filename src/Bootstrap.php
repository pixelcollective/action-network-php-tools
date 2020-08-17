<?php

namespace TinyPixel\ActionNetwork;

use Dotenv\Dotenv;
use GuzzleHttp\Client;
use Illuminate\Support\Collection;
use TinyPixel\ActionNetwork\OSDI\ActionNetwork;
use TinyPixel\ActionNetwork\OSDI\Request;
use TinyPixel\ActionNetwork\OSDI\People;
use TinyPixel\ActionNetwork\OSDI\Tags;
use TinyPixel\ActionNetwork\OSDI\Submissions;
use TinyPixel\ActionNetwork\Container\Container;
use TinyPixel\ActionNetwork\Resource\Form;
use TinyPixel\ActionNetwork\Resource\Person;
use TinyPixel\ActionNetwork\Resource\Petition;
use TinyPixel\ActionNetwork\Resource\Submission;
use TinyPixel\ActionNetwork\Resource\Tag;

/**
 * Action Network Bootstrap
 *
 * @author  Kelly Mears <kelly@roots.io>
 */
class Bootstrap
{
    /** @var object Container */
    protected $app;

    /** @var string app baseDir */
    protected $dir;

    /** @var object app raw config */
    protected $config;

    /** @var array bindings */
    protected $bindings = [
        'container' => Container::class,
        'collection' => Collection::class,
    ];

    protected $instances = [
        'api.client' => Client::class,
    ];

    /** @var array services */
    protected $services = [
        'form' => Form::class,
        'api' => ActionNetwork::class,
        'api.request' => Request::class,
        'api.people' => People::class,
        'api.submissions' => Submissions::class,
        'api.tags' => Tags::class,
        'model.person' => Person::class,
        'model.petition' => Petition::class,
        'model.submission' => Submission::class,
        'model.tag' => Tag::class,
    ];

    /**
     * Bootstrap constructor.
     *
     * @param string $dir app base directory
     */
    public function __construct(string $dir)
    {
        $this->dir = $dir;

        $dotenv = Dotenv::createImmutable($this->dir);
        $dotenv->load();

        $this->config = (object) [
            'app' => (object) include realpath(
                join('/', [$this->dir, 'config/app.php'])
            ),
            'action_network' => (object) include realpath(
                join('/', [$this->dir, 'config/action-network.php'])
            ),
        ];

        $this->app = new Container;

        /**
         * Bind config.
         */
        $this->app->set('config', $this->config);

        /**
         * Do bindings.
         */
        Collection::make($this->bindings)->each(function ($binding, $name) {
            $this->app->set($name, $binding);
        });

        /**
         * Do instances.
         */
        Collection::make($this->instances)->each(function ($binding, $name) {
            $this->app->set($name, new $binding);
        });

        /**
         * Do services.
         */
        Collection::make($this->services)->each(function ($service, $name) {
            $this->app->set($name, function () use ($service) {
                $instance = new $service($this->app);
                if (method_exists($instance, 'register')) {
                    $instance->register($this->app);
                }

                if (method_exists($instance, 'boot')) {
                    $instance->boot($this->app);
                }

                return $instance;
            });
        });
    }

    /**
     * App
     */
    public function app()
    {
        return $this->app;
    }
}
