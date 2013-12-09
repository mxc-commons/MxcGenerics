<?php

namespace MxcGenerics\StdLib;

class StringHelper {
    
    /**
     * Format var_exported array to look a little bit nicer
     * 
     * @param string $var           (var_export to be formatted)
     * @param number $indent        (default: 4)
     * @param bool   $stripslashes  (default: true)
     * @return string               (formatted string)
     */
    public static function formatVarExport($var, $indent = 4, $stripSlashes = true) {
        $indent = str_repeat(' ',$indent);
        //-- remove double backslashes
        if ($stripSlashes) {
            $var = stripslashes($var);
        }
        //-- replace 2 spaces by 4
        $var = preg_replace('/[ ]{2}/', $indent, $var);
        //-- replace array ( by array(
        $var = preg_replace("/=>[ \n\t]+array[ ]+\\(/", '=> array(', $var);
        //-- remove numeric keys
        $var = preg_replace('/([0-9]+ =>)/', '', $var);
        // make file end with EOL
        return rtrim($var, "\n") . "\n";
    }
    
}
