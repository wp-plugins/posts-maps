<?php
class PostsMapsController extends PostsMapsCorePlugin {
    private $_display = array();

    public function __construct() {
        parent::__construct();
        
        $this->_display['menuSlugs'] = $this->getMenuSlugs();        
    }

    public function index() {
        $this->displayBlock('main', $this->_display);
    }

    public function marker() {
        $this->displayBlock('marker', $this->_display);
    }

}
