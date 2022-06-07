<?php

namespace FAU_Studium_Embed;

defined('ABSPATH') || exit;

use FAU_Studium_Embed\Settings;
use FAU_Studium_Embed\Shortcode;
	

/**
 * Hauptklasse (Main)
 */

class Main {
    protected $pluginFile;
    private $settings = '';

    public function __construct($pluginFile) {
        $this->pluginFile = $pluginFile;
    }

    /**
     * Es wird ausgeführt, sobald die Klasse instanziiert wird.
     */
    public function onLoaded() {
	 add_action('wp_enqueue_scripts', [$this, 'registerPluginStyles']);
	// Settings-Klasse wird instanziiert.
	 add_action('admin_enqueue_scripts', [$this, 'enqueueAdminScripts']);
	 
	 // Add and load other classes here

	// Settings-Klasse wird instanziiert.
        $settings = new Settings($this->pluginFile);
        $settings->onLoaded();

        // Shortcode-Klasse wird instanziiert.
        $shortcode = new Shortcode($this->pluginFile, $settings);
        $shortcode->onLoaded();
   
	return;			
    }
    
    
    
    
    public function registerPluginStyles() {
	wp_register_style('fau-studium-embed', plugins_url('css/fau-studium.css', plugin_basename($this->pluginFile)));
    }
    
     public function enqueueAdminScripts() {
	wp_register_style('fau-studium-adminstyle', plugins_url('css/fau-studium-admin.css', plugin_basename($this->pluginFile)));
	wp_enqueue_style('fau-studium-adminstyle');
	wp_register_script('fau-studium-adminscripts', plugins_url('js/fau-studium-admin.js', plugin_basename($this->pluginFile)));
	wp_enqueue_script('fau-studium-adminscripts');
	
    }

    
    
    public static function enqueueForeignThemes() {
	 wp_enqueue_style('fau-studium-embed');  
    }
    
  
}


