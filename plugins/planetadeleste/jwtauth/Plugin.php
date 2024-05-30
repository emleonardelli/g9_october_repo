<?php

namespace PlanetaDelEste\JWTAuth;

use Config;
use PlanetaDelEste\JWTAuth\Facades\JWTAuth;
use PlanetaDelEste\JWTAuth\Providers\AuthServiceProvider;
use System\Classes\PluginBase;
use System\Classes\SettingsManager;
use PlanetaDelEste\JWTAuth\Models\Settings as PluginSettings;

/**
 * JWTAuth Plugin Information File.
 */
class Plugin extends PluginBase
{
    /**
     * Plugin dependencies.
     *
     * @var array
     */
    public $require = ['Lovata.Buddies'];

    /**
     * Returns information about this plugin.
     *
     * @return array
     */
    public function pluginDetails()
    {
        return [
            'name'        => 'planetadeleste.jwtauth::lang.plugin.name',
            'description' => 'planetadeleste.jwtauth::lang.plugin.description',
            'author'      => 'Ricardo Lüders',
            'icon'        => 'icon-user-secret',
        ];
    }

    /**
     * Register the plugin settings
     *
     * @return array
     */
    public function registerSettings()
    {
        return [
            'settings' => [
                'label'       => 'planetadeleste.jwtauth::lang.settings.menu_label',
                'description' => 'planetadeleste.jwtauth::lang.settings.menu_description',
                'category'    => SettingsManager::CATEGORY_USERS,
                'icon'        => 'icon-user-secret',
                'class'       => 'PlanetaDelEste\JWTAuth\Models\Settings',
                'order'       => 600,
                'permissions' => ['planetadeleste.jwtauth.access_settings'],
            ]
        ];
    }

    /**
     * Register the plugin permissions
     *
     * @return array
     */
    public function registerPermissions()
    {
        return [
            'planetadeleste.jwtauth.access_settings' => [
                'tab' => 'planetadeleste.jwtauth::lang.plugin.name',
                'label' => 'planetadeleste.jwtauth::lang.permissions.settings'
            ]
        ];
    }

    /**
     * Register method, called when the plugin is first registered.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(AuthServiceProvider::class);
        $this->app->alias('JWTAuth', JWTAuth::class);
    }
}
