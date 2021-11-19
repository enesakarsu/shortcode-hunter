<?php

/** 
* SHORTCODE HUNTER <3
*
* A simple PHP Shortcode class, custom shortcodes and nested shortcodes...
*
* @author Enes Akarsu
* @github http://github.com/enesakarsu/
*@link http://github.com/enesakarsu/shortcode-hunter
*
**/

class Shortcode
{
    public $shortcodes = array();
    public function parse($text)
    {
        preg_match_all("/\[+(.*?)?\]/", $text, $matches);
        
        foreach ($matches[1] as $m) {
            $shortcode_parameters = explode(" ", $m);
            
            if ($this->shortcodes[$shortcode_parameters[0]]) {
                $text = preg_replace_callback("/\[(" . $shortcode_parameters[0] . "|" . $shortcode_parameters[0] . "(.*?)={{(.*?)}}.*?)\](?:(.+?)?\[\/" . $shortcode_parameters[0] . "\])?/", function($o)
                {
                    $shortcode_parse = explode(" ", $o[1]);
                    $shortcode_name  = $shortcode_parse[0];
                    
                    $shortcode_attributes = array();
                    $params               = str_replace($shortcode_name." ", "", $o[1]);
                    preg_match_all("/(.*?)=\{{(.*?)?\}}/", $params, $p);
                    
                    $parameters_counter = 0;
                    foreach ($p[1] as $p2) {
                        $shortcode_attributes[trim($p2)] = $p[2][$parameters_counter];
                        $parameters_counter++;
                    }
                    $shorcode_attrs = array_shift($shortcode_parse);
                    str_replace($shortcode_name, "", $o[1]);
                    
                    
                    if (strpos($this->shortcodes[$shortcode_name], "@")) {
                        $class_func = explode("@", $this->shortcodes[$shortcode_name]);
                        if (method_exists($class_func[0], $class_func[1])) {
                            $class = new $class_func[0];
                            return $class->{$class_func[1]}($shortcode_attributes, end($o));
                        }
                    } else {
                        if (function_exists($this->shortcodes[$shortcode_name])) {
                            return $this->shortcodes[$shortcode_name]($shortcode_attributes, end($o));
                        }
                    }
                }, $text);
            }
            
            
        }
        
        return $text;
    }
    
    public function create($name, $func)
    {
        $this->shortcodes[$name] = $func;
    }
    
    
}

$shortcode = new Shortcode();
include(__DIR__."/Functions.php");
include(__DIR__."/Shortcodes.php");
?>
