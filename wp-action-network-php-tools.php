<?php

/**
 * Plugin Name:  action-network-tools
 * Plugin URI:   https://tinypixel.dev
 * Description:  Action Network tools
 * Author:       Wiley C. <wiley@gmail.com>
 * License:      MIT
 * Text Domain:  acme-co
 */

namespace TinyPixel;

use TinyPixel\ActionNetwork\Bootstrap;

(new class {
    /** @var string */
    public $autoload;

    /** @var Psr\Container\ContainerInterface */
    public $tools;

    /**
     * Class constructor.
     */
    public function __construct()
    {
        $this->dir = __DIR__;

        if (! $this->autoload = realpath("{$this->dir}/vendor/autoload.php")) {
            return add_action('admin_notices', [$this, 'composerError']);
        }

        require_once $this->autoload;
    }

    /**
     * Class invocation.
     */
    public function __invoke()
    {
        add_action('init', new Bootstrap($this->dir));
    }

    /**
     * Autoloader not found
     */
    public function composerError(): void
    {
        print '<div class="notice notice-error">
            <p><strong>There&apos;s a problem with the action network plugin.</strong></p>
            <p>Please run <code>composer install</code> in <code>' . $this->dir .'</code></p>
        </div>';
    }
})();
