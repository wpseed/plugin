<?php

namespace Wpseed;

use Closure;
use League\Container\Container;
use Configula\ConfigFactory;

/**
 * Class Plugin
 */
class Plugin extends Container {

    use Macroable;

    /**
     * @var string
     */
    protected $filePath;

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
     * Trigger callback on activation of the plugin.
     *
     * @param Closure $activation
     * @return void
     */
    public function onActivation(Closure $activation ) {

        $instance = $this;

        register_activation_hook(
            $this->filePath,
            function () use ( $activation, $instance ) {
                try {
                    call_user_func_array( $activation, [ $instance ] );
                } catch ( \Exception $e ) {
                    deactivate_plugins( basename( $this->filePath ) );
                    wp_die( $e->getMessage() ); //phpcs:ignore
                }
            }
        );

    }

    /**
     * Trigger callback on plugin deactivation.
     *
     * @param Closure $deactivation
     * @return void
     */
    public function onDeactivation(Closure $deactivation ) {

        $instance = $this;

        register_deactivation_hook(
            $this->filePath,
            function () use ( $deactivation, $instance ) {
                try {
                    call_user_func_array( $deactivation, [ $instance ] );
                } catch ( \Exception $e ) {
                    wp_die( $e->getMessage() ); //phpcs:ignore
                }
            }
        );
    }


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