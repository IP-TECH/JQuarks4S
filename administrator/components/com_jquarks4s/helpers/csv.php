<?php
defined('_JEXEC') or die('Restricted access');

class Jquarks4sCsvHelper
{
    /**
     *
     * @var string
     */
    static private $_content;

    static private $_separator = ';';

    static private $_lineEnding = "\r\n";

    
    function getContent()
    {
        return self::$_content;
    }


    function initContent()
    {
        self::$_content = null;
    }

    function setSeparator($separator)
    {
        switch ($separator)
        {
            case 'comma':
                self::$_separator = ',';
                return true;
                break;

            case 'semicolon':
                self::$_separator = ';';
                return true;
                break;
        }
        return false;
    }


    function setLineEnding ($end)
    {
        self::$_lineEnding = $end;
    }


    function endLine()
    {
        self::$_content .= self::$_lineEnding;
    }

    
    /**
     * $last true if $value is last element in the line
     * @param string $value
     * @param boolean $last
     */
    function addValue($value = '', $last = false)
    {
        self::$_content .= '"'.$value.'"';
        if ( ! $last) {
            self::$_content .= self::$_separator;
        }
    }

    function addLine($values = array())
    {
        if (count($values)) {
            self::$_content .= '"' . implode('"'.self::$_separator.' "', $values) . '"' . self::$_lineEnding;
        }
    }

    
    function object_to_array($mixed)
    {
        if ( is_object($mixed) ) {
            $mixed = (array) $mixed;
        }
        if ( is_array($mixed) )
        {
            $new = array();
            foreach ($mixed AS $key => $val)
            {
                $key = preg_replace("/^\\0(.*)\\0/","",$key);
                $new[$key] = self::object_to_array($val);
            }
        }
        else {
            $new = $mixed;
        }
        return $new;
    }
}
