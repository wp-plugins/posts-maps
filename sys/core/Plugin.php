<?php
class CorePlugin {
    protected $_pluginRoot;
    protected $_pluginFolder;
    protected $_sysCoreFolder;

    public function __construct($rootFolder = false) {
        if($rootFolder)
            $this->_pluginRoot = $rootFolder;
        else
            $this->_pluginRoot = dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..';
        
        $this->_sysCoreFolder = $this->getRootFolder() . DIRECTORY_SEPARATOR . 'sys' . DIRECTORY_SEPARATOR . 'core';
        $this->_pluginFolder = $this->getRootFolder() . DIRECTORY_SEPARATOR . 'PostsMaps';
        
        require_once $this->getSysCoreFolder() . DIRECTORY_SEPARATOR . 'Loader.php';
        $this->Loader = new Loader();
    }
    
    public function getRootFolder() {
        return $this->_pluginRoot;
    }
    
    public function getPluginFolder() {
        return $this->_pluginFolder;
    }
    
    public function getSysCoreFolder() {
        return $this->_sysCoreFolder;
    }
    
    public function load($path, $name) {
        return $this->Loader->Load($path, $name);
    }
    
    public function loadController($name, $propName = false) {
        if(!$propName)
            $propName = $name;
        $this->$propName = $this->load($this->getPluginFolder() . DIRECTORY_SEPARATOR . 'controllers' . DIRECTORY_SEPARATOR . $name . '.php', $name);
    }
    
    public function loadModel($name, $propName = false) {
        if(!$propName)
            $propName = $name;
        $this->$propName = $this->load($this->getPluginFolder() . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . $name . '.php', $name);
    }
    
    public function loadService($name, $propName = false) {
        if(!$propName)
            $propName = $name;
        $this->$propName = $this->load($this->getPluginFolder() . DIRECTORY_SEPARATOR . 'services' . DIRECTORY_SEPARATOR . $name . '.php', $name);
    }
    
    public function loadHelper($name) {
        if(file_exists($this->getPluginFolder() . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . $name . '.php'))
            require_once $this->getPluginFolder() . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . $name . '.php';
    }

    protected function display($tmp, $args = false) {
        if($args)
            extract($args);
        if(file_exists($this->getPluginFolder() . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . $tmp . '.php'))
            require_once $this->getPluginFolder() . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . $tmp . '.php';
        exit;
    }

    protected function displayBlock($tmp, $args = false) {
        if($args)
            extract($args);
        if(file_exists(get_template_directory() . DIRECTORY_SEPARATOR . 'pmViews' . DIRECTORY_SEPARATOR . $tmp . '.php'))
            require_once get_template_directory() . DIRECTORY_SEPARATOR . 'pmViews' . DIRECTORY_SEPARATOR . $tmp . '.php';
        elseif(file_exists($this->getPluginFolder() . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . $tmp . '.php'))
            require_once $this->getPluginFolder() . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . $tmp . '.php';
    }
}
?>