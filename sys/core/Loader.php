<?php
class Loader {
    
    public function Load($path, $name) {
        if(file_exists($path)) {
            require_once $path;
            return new $name;
        }
        return false;
    }
}
?>