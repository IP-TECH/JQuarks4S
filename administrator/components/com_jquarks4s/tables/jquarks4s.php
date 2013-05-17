<?php
/**
 * @version SVN: $Id$
 * @package    JQuarks4s
 * @subpackage Tables
 * @author     EasyJoomla {@link http://www.easy-joomla.org Easy-Joomla.org}
 * @author     IP-Tech Labs {@link http://www.iptechinside.com/labs/projects/show/jquarks-for-surveys}
 * @author     Created on 05-Mar-2010
 */

//-- No direct access
defined('_JEXEC') or die('=;)');

/**
 * JQuarks4s Table class
 *
 * @package    JQuarks4s
 * @subpackage Tables
 */
class TableJQuarks4s extends JTable
{
    /**
     * Primary Key
     *
     * @var int
     */
    var $id = null;

    /**
     * @var string
     */
    var $greeting = null;

    /**
     * Constructor
     *
     * @param object $db Database connector object
     */
    function TableJQuarks4s(& $db)
    {
        parent::__construct('#__jquarks4s', 'id', $db);
    }//function

}//class
