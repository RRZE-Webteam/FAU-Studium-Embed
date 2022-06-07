<?php

namespace FAU_Studium_Embed;

defined('ABSPATH') || exit;
use function FAU_Studium_Embed\Config\getShortcodeSettings;



/**
 * Shortcode
 */
class Shortcode
{

    /**
     * Der vollständige Pfad- und Dateiname der Plugin-Datei.
     * @var string
     */
    protected $pluginFile;

    /**
     * Settings-Objekt
     * @var object
     */
    private $settings = '';
    private $pluginname = '';

    /**
     * Variablen Werte zuweisen.
     * @param string $pluginFile Pfad- und Dateiname der Plugin-Datei
     */
    public function __construct($pluginFile, $settings)
    {
        $this->pluginFile = $pluginFile;
        $this->settings = getShortcodeSettings();
        $this->pluginname = $this->settings['block']['blockname'];
        add_action('admin_enqueue_scripts', [$this, 'enqueueGutenberg']);
        add_action('init',  [$this, 'initGutenberg']);
        add_action('admin_head', [$this, 'setMCEConfig']);
        add_filter('mce_external_plugins', [$this, 'addMCEButtons']);
    }

    /**
     * Er wird ausgeführt, sobald die Klasse instanziiert wird.
     * @return void
     */
    public function onLoaded()
    {
	// to be filled
    }

   

    /**
     * Generieren Sie die Shortcode-Ausgabe
     * @param  array   $atts Shortcode-Attribute
     * @param  string  $content Beiliegender Inhalt
     * @return string Gib den Inhalt zurück
     */
    public function shortcodeOutput( $atts ) {
        // merge given attributes with default ones
        $atts_default = array();
        foreach( $this->settings as $k => $v ){
            if ( $k != 'block' ){
                $atts_default[$k] = $v['default'];
            }
        }
        $atts = shortcode_atts( $atts_default, $atts );

        $content = '';

        $display = $shortcode_atts['display'] == 'true' ? true : false;

        $output = '';

        if ($display) {
            $output = '<span class="fau-studium-embed" data-display="true">[shortcode display]</span>';
        } else {
            $output = '<span class="fau-studium-embed" data-display="false">[shortcode hidden]</span>';
        }

        wp_enqueue_style('fau-studium-embed');
     //   wp_enqueue_script('fau-studium-embed');

        return $output;
    }

    public function isGutenberg(){
        $postID = get_the_ID();
        if ($postID && !use_block_editor_for_post($postID)){
            return false;
        }

        return true;        
    }

    public function fillGutenbergOptions() {
        // Example:
        // fill select id ( = glossary )
        $glossaries = get_posts( array(
            'posts_per_page'  => -1,
            'post_type' => 'glossary',
            'orderby' => 'title',
            'order' => 'ASC'
        ));

        $this->settings['id']['field_type'] = 'multi_select';
        $this->settings['id']['default'] = array(0);
        $this->settings['id']['type'] = 'array';
        $this->settings['id']['items'] = array( 'type' => 'number' );
        $this->settings['id']['values'][] = ['id' => 0, 'val' => __( '-- all --', 'rrze-basis' )];
        foreach ( $glossaries as $glossary){
            $this->settings['id']['values'][] = [
                'id' => $glossary->ID,
                'val' => str_replace( "'", "", str_replace( '"', "", $glossary->post_title ) )
            ];
        }

        return $this->settings;
    }


    public function initGutenberg() {
        if (! $this->isGutenberg()){
            return;
        }

        // get prefills for dropdowns
        $this->settings = $this->fillGutenbergOptions();

        // register js-script to inject php config to call gutenberg lib
        $editor_script = $this->settings['block']['blockname'] . '-block';        
        $js = '../assets/js/' . $editor_script . '.js';

        wp_register_script(
            $editor_script,
            plugins_url( $js, __FILE__ ),
            array(
                'RRZE-Gutenberg',
            ),
            NULL
        );
        wp_localize_script( $editor_script, $this->settings['block']['blockname'] . 'Config', $this->settings );

        // register block
        register_block_type( $this->settings['block']['blocktype'], array(
            'editor_script' => $editor_script,
            'render_callback' => [$this, 'shortcodeOutput'],
            'attributes' => $this->settings
            ) 
        );
    }

    public function enqueueGutenberg(){
        if (! $this->isGutenberg()){
            return;
        }

        // include gutenberg lib
        wp_enqueue_script(
            'RRZE-Gutenberg',
            plugins_url( '../assets/js/gutenberg.js', __FILE__ ),
            array(
                'wp-blocks',
                'wp-i18n',
                'wp-element',
                'wp-components',
                'wp-editor'
            ),
            NULL
        );
    }

    public function setMCEConfig(){
        $shortcode = '';
        foreach($this->settings as $att => $details){
            if ($att != 'block'){
                $shortcode .= ' ' . $att . '=""';
            }
        }
        $shortcode = '[' . $this->pluginname . ' ' . $shortcode . ']';
        ?>
        <script type='text/javascript'>
            tmp = [{
                'name': <?php echo json_encode($this->pluginname); ?>,
                'title': <?php echo json_encode($this->settings['block']['title']); ?>,
                'icon': <?php echo json_encode($this->settings['block']['tinymce_icon']); ?>,
                'shortcode': <?php echo json_encode($shortcode); ?>,
            }];
            phpvar = (typeof phpvar === 'undefined' ? tmp : phpvar.concat(tmp)); 
        </script> 
        <?php        
    }

    public function addMCEButtons($pluginArray){
        if (current_user_can('edit_posts') &&  current_user_can('edit_pages')) {
            $pluginArray['rrze_shortcode'] = plugins_url('../assets/js/tinymce-shortcodes.js', plugin_basename(__FILE__));
        }
        return $pluginArray;
    }
}
