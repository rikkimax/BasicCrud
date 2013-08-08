<?php
namespace CrudUI;
/**
 * Assumptions:
 * BasicORM already loaded. http://github.com/rikkimax/BasicORM
 */

const Create = 0x1;
const Review = 0x2;
const Update = 0x4;
const Delete = 0x8;
const All = 0x15;

const TypeColor = 'color';
const TypeDate = 'date';
const TypeDateTime = 'datetime';
const TypeEmail = 'email';
const TypeFile = 'file';
const TypeImage = 'image';
const TypeMonth = 'month';
const TypeNumber = 'number';
const TypePassword = 'password';
const TypeText = 'text';
const TypeUrl = 'url';
const TypeWeek = 'week';


class CModel {
    protected $clas = '';
    protected $headers = array('');
    protected $data = array();
    protected $localeFunc;
    protected $validation = array();
    protected $headerLength = array();
    protected $headerType = array();
    protected $title;
            
    public function __construct($clasz, $title = '') {
        $this->clasz = $clasz;
        $this->headers = \ORM\class_property_names($clasz);
        $this->localeFunc =
            function($name) {
                return ucfirst($name);
            };
        $this->title = $title == '' ? $clasz . ' crud interface' : $title;
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
    
    public function headerType($key, $val) {
        $this->headerType[$key] = $val;
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
    
    public function generate($enabled = All) {
        $ret = '<span class="CrudUITitle">' . $this->title . '</span>&nbsp;<a href="javascript:alert(\'TODO ADD\');">add</a>';
        $ret .= '<table class="CrudUI">';
        
        $ret .=  "\n" . '<tr>';
        foreach($this->headers as $header) {
            $localeFunc = $this->localeFunc;
            $locale = $localeFunc($header);
            $ret .= '<td class="CrudUIHeader">' . $locale . '</td>';
        }
        if ($enabled & Delete == Delete) { 
            $ret .= '<td></td>';
        }
        
        $ret .= '</tr>' . "\n";
        
        if ($enabled & Create == Create) { 
            $ret .=  "\n" . '<tr>';
            foreach($this->headers as $header) {
                $length = array_key_exists($header, $this->headerLength) ? $this->headerLength[$header] : 20;
                $type = array_key_exists($header, $this->headerType) ? $this->headerType[$header] : TypeText;                
                $ret .= '<td><input name="' . $header. '" type="' . $type . '" class="CrudUIField CrudUIField' . $header .'" maxlength="' . $length . '" size="' . $length . '"/></td>';
            }
            $ret .= '<td><a href="javascript:alert(\'TODO DELETE\');">delete</a></td>';
            $ret .= '</tr>' . "\n";
        }
        
        if ($enabled & Review == Review) { 
            foreach($this->data as $d) {
                if ($enabled & Update == Update) {
                    $ret .=  "\n" . '<tr class="DataEditRow">';
                    foreach($this->headers as $header) {
                        $length = array_key_exists($header, $this->headerLength) ? $this->headerLength[$header] : 20;
                        $type = array_key_exists($header, $this->headerType) ? $this->headerType[$header] : TypeText;                
                        $ret .= '<td><input name="' . $header. '" type="' . $type . '" class="CrudUIField CrudUIField' . $header .'" maxlength="' . $length . '" size="' . $length . '" value="' . $d[$header] . '"/></td>';
                    }
                                    
                    if ($enabled & Delete == Delete) { 
                        $ret .= '<td><a href="javascript:alert(\'TODO DELETE\');">delete</a></td>';
                    }
                    $ret .= '</tr>' . "\n";
                }
                $ret .=  "\n" . '<tr class="DataRow" onclick="javascript:alert(\'TODO show edit row\')">';
                foreach($this->headers as $header) {
                    $length = array_key_exists($header, $this->headerLength) ? $this->headerLength[$header] : 20;
                    $type = array_key_exists($header, $this->headerType) ? $this->headerType[$header] : TypeText;                
                    $ret .= '<td>' . $d[$header] . '</td>';
                }

                if ($enabled & Delete == Delete) { 
                    $ret .= '<td><a href="javascript:alert(\'TODO DELETE\');">delete</a></td>';
                }
                $ret .= '</tr>' . "\n";
            }
        }
        
        $ret .= '</table>';
        return $ret;
    }
}
?>
