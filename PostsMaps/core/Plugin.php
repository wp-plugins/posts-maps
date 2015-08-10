<?php
class PostsMapsCorePlugin extends CorePlugin {
    private $_capability = 'manage_options';
    private $_display = array();
    
    private $_menu_slugs = array(
                                'posts_maps_index' => 'posts-maps',
                                'posts_maps_marker_icons' => 'posts-maps-marker-icons',
                                );

    public function __construct($rootFolder = false) {
        parent::__construct($rootFolder);        
        $this->initHooks();
        $this->initUI();
        $this->initHelpers();
        $this->initShortCodes();
        $this->initAdminHooks();
    }
    
    public function PostsMapsInstall() {
        $markerIcons = array(
            'diving' => 'div.png',
            'island' => 'isl.png',
            'library' => 'lib.png',
            'mountain' => 'mou.png',
            'nature' => 'nat.png',
            'pyramid' => 'pyr.png',
            'square' => 'squ.png',
            'temple' => 'tem.png',
            'canyon' => 'can.png',
            'sphinx' => 'sph.png',
            'crypt' => 'cry.png',
            'monument' => 'mon.png',
            'beach' => 'bea.png',
            'desert' => 'des.png',
            'circle' => 'cir.png',
            'lighthouse' => 'lig.png',
            'resort' => 'res.png',
            'city' => 'cit.png',
            );
        update_option('pm_marker_icons', serialize($markerIcons));
    }
    
    public function getMenuSlugs() {
        return $this->_menu_slugs;
    }

    public function getMenuSlugByName($name) {
        if($name && isset($this->_menu_slugs[$name]))
            return $this->_menu_slugs[$name];
        return $name;
    }
    
    private function initHooks() {
        register_activation_hook($this->getRootFolder() . DIRECTORY_SEPARATOR . 'posts-maps.php', array($this, 'PostsMapsInstall'));        
    }

    private function initUI() {
        wp_enqueue_style('posts-maps', plugins_url( '/assets/css/posts-maps.css', $this->getPluginFolder()));
        wp_enqueue_script('maps-googleapis', 'https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=places&signed_in=true');
        wp_enqueue_script('pm-js', plugins_url('/assets/js/pm.js', $this->getPluginFolder()), array('jquery', 'maps-googleapis'), '0.0.1');
    }

    private function initHelpers() {
        $helpers = array('PostsMapsHelper', 'PostsMapsTemplateHelper');
        foreach($helpers as $helper) {
            $this->loadHelper($helper);
        }
    }

    private function initAdminHooks() {
        add_action('admin_menu', array($this, 'initAdminMenu'));
        add_action('admin_init', array($this, 'initAdminPostsMapsFileds'), 1);
        add_action('save_post', array($this, 'initAdminPostsMapsFiledsSave'), 1);
        add_filter('plugin_action_links', array($this, 'initAdminPluginSettingLink'), 10, 5);
    }
    
    private function initShortCodes() {
        add_shortcode('pm-show-map', array($this, 'shortcodeShowMap'));
        add_shortcode('pm-show-map-by-post-types', array($this, 'shortcodeShowMapByPostTypes'));
        add_shortcode('pm-show-map-by-post-id', array($this, 'shortcodeShowMapByPostId'));
    }

    public function shortcodeShowMap($atts) {
        global $post;
        if($post) {
            $this->_display['post'] = $post;
            $this->_display += pmGetPostMapData($post);
            $this->displayBlock('mapBlock', $this->_display);
        }
    }

    public function shortcodeShowMapByPostTypes($atts) {
        if(isCorrectArray($atts)) {
            $atts = array_map('trim', $atts);
            $this->showMapByPostTypes($atts);
        }
    }

    public function shortcodeShowMapByPostId($atts) {
        if(isCorrectArray($atts)) {
            $atts = array_map('trim', $atts);
            $this->showMapByPostId($atts);
        }
    }

    public function showMap($postId) {
        if(isCorrectNumber($postId)) {
            $post = get_post($postId);
            $this->_display['post'] = $post;
            $this->_display += pmGetPostMapData($post);
            $this->displayBlock('mapBlock', $this->_display);
        }
    }

    public function showMapByPostTypes($postTypes) {
        if(!$this->PostsMapsModel)
            $this->loadModel('PostsMapsModel');
        $posts = $this->PostsMapsModel->getPostsByTypes($postTypes);
        if($posts && isCorrectArray($posts)) {
            $this->_display['posts'] = $posts;
            $this->_display['mapSettings'] = pmGetPostMapSettings();
            $this->displayBlock('mapBlockMultiple', $this->_display);
        }
    }

    public function showMapByPostId($postId) {
        if(!$this->PostsMapsModel)
            $this->loadModel('PostsMapsModel');
        $posts = $this->PostsMapsModel->getPostsById($postId);
        if($posts && isCorrectArray($posts)) {
            $this->_display['posts'] = $posts;
            $this->_display['mapSettings'] = pmGetPostMapSettings();
            $this->displayBlock('mapBlockMultiple', $this->_display);
        }
    }

    public function initAdminMenu() {
        if(!$this->PostsMapsController)
            $this->loadController('PostsMapsController');
        add_menu_page(__('Posts Maps', 'posts-maps'), __('Posts Maps', 'posts-maps'), $this->_capability, $this->getMenuSlugByName('posts_maps_index'), array($this->PostsMapsController, 'index'), plugins_url('/assets/images/pmicon.png', $this->getPluginFolder()));
        add_submenu_page('posts-maps', 'Markers', 'Markers', $this->_capability, $this->getMenuSlugByName('posts_maps_marker_icons'), array($this->PostsMapsController, 'marker'));
    }

    public function initAdminPostsMapsFileds() {
        $pmPostTypes = get_option('pm_enabled_types');
        if(isCorrectArray($pmPostTypes)) {
            foreach($pmPostTypes as $pmPostType) {
                add_meta_box('pmAdminFieldsBox', 'PostsMaps', array($this, 'initAdminFieldsBox'), $pmPostType, 'normal', 'high');
            }
        }
    }

    public function initAdminFieldsBox($post) {
        $this->_display['post'] = $post;
        $this->displayBlock('adminFieldsBox', $this->_display);
    }

    public function initAdminPostsMapsFiledsSave($postId) {
        if(!wp_verify_nonce($_POST['pm_fields_nonce'], 'pm-fields-save')) return false;
        if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return false;
        if(!isset($_POST['pm_fields'])) return false;

        if(isset($_POST['pm_fields']) && isCorrectArray($_POST['pm_fields'])) {
            $_POST['pm_fields'] = array_map('trim', $_POST['pm_fields']);
            foreach($_POST['pm_fields'] as $key => $value){
                if(!trim($value))
                    delete_post_meta($postId, $key);
                else
                    update_post_meta($postId, $key, $value);
            }
        }
    }

    public function initAdminPluginSettingLink($actions, $plugin_file) {
        if($plugin_file == 'posts-maps/posts-maps.php') {
            $settings = array('settings' => '<a href="' . get_admin_url() . 'admin.php?page=' . $this->getMenuSlugByName('posts_maps_index') . '">' . __('Settings', 'General') . '</a>');
            $actions = array_merge($settings, $actions);
        }
        return $actions;
    }

    public function addPostAditFormTag() {
        echo ' enctype="multipart/form-data"';
    }
    
    public function uploadFiles($fileData) {
        if(!$fileData['error'] && $fileData['name'] && $fileData['tmp_name']) {
            $upload = wp_upload_bits($fileData['name'], null, file_get_contents($fileData['tmp_name']));
            if(isset($upload['error']) && $upload['error'] != 0) {
                return array('error' => $upload['error']);
            }
            else {
                return array('url' => $upload['url']);
            }
        }
        return array('error' => $fileData['error']);
    }


}
