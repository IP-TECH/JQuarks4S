<?php

class Jquarks4sToolBarHelper
{

    
    function title($title, $icon = 'generic.png')
	{
		global $mainframe;

        $headtag  = '<link rel="stylesheet" href="'.$this->baseurl;
        $headtag .= '/components/com_jquarks4s/assets/css/toolbar.css" type="text/css" media="screen" />';

        $mainframe->addCustomHeadTag($headtag);

		//strip the extension
		$icon	= preg_replace('#\.[^.]*$#', '', $icon);

		$html  = "<div class=\"header icon-48-$icon\">\n";
		$html .= "$title\n";
		$html .= "</div>\n";

		$mainframe->set('JComponentTitle', $html);
	}

}


class Jquarks4sMenuHelper
{
    function addTopMenu($highlight)
    {
        
    }


	function addEntry($name, $link = '', $active = false)
	{
        $menu = &JToolBar::getInstance('jquarks_submenu1');
		$menu->appendButton($name, $link, $active);
	}
}
