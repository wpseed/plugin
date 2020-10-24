<?php

namespace Wpseed;

use Closure;
use League\Container\Container;
use Configula\ConfigFactory;

/**
 * Class Plugin
 */
class Plugin extends Container {
    /**
     * @var ConfigFactory;
     */
    protected $config;

    /**
     * Indicates if the plugin has booted.
     *
     * @var bool
     */
    protected $booted = false;


    /**
     * Boot the plugin by running it up to the WordPress plugins_loaded hook.
     *
     * @param Closure $boot
     * @return void
     */
    public function boot( Closure $boot ) {

        $instance = $this;

        add_action(
            'plugins_loaded',
            function () use ( $boot, $instance ) {
                try {
                    $instance->bootPluginProviders();
                    $instance->booted = true;
                    call_user_func_array( $boot, [ $instance ] );
                } catch ( \Exception $e ) {
                    wp_die( $e->getMessage() ); //phpcs:ignore
                }
            }
        );
    }
}