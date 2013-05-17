<?php
defined('_JEXEC') or jexit('Restricted access');

class JElementSurvey extends JElement
{    
    function fetchElement($name, $value, &$node, $control_name)
    {
        $database =& JFactory::getDBO();

        $query = ' SELECT id, title'.
                 ' FROM #__jquarks4s_surveys';
        $database->setQuery($query);

        $surveys = $database->loadObjectList();
        $options = array();
        foreach ($surveys as $survey)
        {
            $options[] = array('id' => $survey->id, 'value' => $survey->title);
        }
        return JHTML::_('select.genericlist',  $options, ''.$control_name.'['.$name.']', 'class="inputbox"', 'id', 'value', $value, $control_name.$name );
   }
}
