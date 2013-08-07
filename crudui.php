<?php
namespace CrudUI;
/**
 * Assumptions:
 * BasicORM already loaded. http://github.com/rikkimax/BasicORM
 */

class CModel {
    protected $clas = '';
    protected $headers = array('');
    protected $data = array();
    protected $localization = false;
    protected $localeFunc;
    protected $validation = array();
    protected $headerLength = array();
            
    public function __construct($clasz) {
        $this->clasz = $clasz;
        $this->headers = \ORM\class_property_names($clasz);
        $this->localeFunc =
            function($name) {
                return ucfirst($name);
            };
    }
    
    public function locale($val) {
        $this->localization = $val;
        return $this;
    }
    
    public function localeCallback($func) {
        $this->localeFunc = $func;
        return $this;
    }
    
    public function data($val) {
        $this->data[] = \ORM\class_values($val);
        return $this;
    }
    
    public function addHeader($val) {
        $this->headers[] = $val;
        return $this;
    }
    
    public function removeHeader($val) {
        $this->headers = array_diff($this->headers, array($val));
        return $this;
    }
    
    public function headerLength($key, $val) {
        $this->headerLength[$key] = $val;
        return $this;
    }
    
    public function validate($name, $regex) {
        $this->validation[$name] = '/^' . $regex . '$/';
        return $this;
    }
    
    function checkAllData() {
        $correct = true;
        foreach($this->data as $d) {
            if (!$this->checkData($d)) {
                $correct = false;
                break;
            }
        }
        return $correct;
    }
    
    protected function checkData($input) {
        $correct = true;
        foreach($this->headers as $key) {
            if (array_key_exists($key, $this->validation)) {
                if (preg_match_all($this->validation[$key], $input[$key]) == FALSE) {
                    $correct = false;
                    break;
                }
            }
        }
        return $correct;
    }
    
    public function generate() {
        
    }
}
?>
