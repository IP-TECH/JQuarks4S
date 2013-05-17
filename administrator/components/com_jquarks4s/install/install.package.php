<?php
/**
 * @version     $Id$
 * @author      IP-Tech Labs <labs@iptech-offshore.com>
 * @copyright   2010 IP-Tech
 * @package     JQuarks4s-Back-End
 * @subpackage  install
 * @link        http://www.iptechinside.com/labs/projects/show/jquarks-for-surveys
 * @since       1.0.0
 * @license     GNU/GPL2
 *
 *    This program is free software; you can redistribute it and/or
 *  modify it under the terms of the GNU General Public License
 *  as published by the Free Software Foundation; version 2
 *  of the License.
 *
 *    This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *    You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *  or see <http://www.gnu.org/licenses/>
 */

defined('_JEXEC') or die();

$status = new JObject();
$status->modules = array();
$status->plugins = array();

/***********************************************************************************************
 * ---------------------------------------------------------------------------------------------
 * MODULE INSTALLATION SECTION
 * ---------------------------------------------------------------------------------------------
 ***********************************************************************************************/

$modules = &$this->manifest->getElementByPath('modules');
if (is_a($modules, 'JSimpleXMLElement') && count($modules->children()))
{
    foreach ($modules->children() as $module)
    {
        $mname		= $module->attributes('module');
        $mclient	= JApplicationHelper::getClientInfo($module->attributes('client'), true);

        //--Set the installation path
        if( ! empty ($mname))
        {
            $this->parent->setPath('extension_root', $mclient->path.DS.'modules'.DS.$mname);
        }
        else
        {
            $this->parent->abort(JText::_('Module').' '.JText::_('Install').': '.JText::_('No module file specified'));
            return false;
        }

        /*
         * If the module directory already exists, then we will assume that the
         * module is already installed or another module is using that directory.
         */
        if (file_exists($this->parent->getPath('extension_root'))&&!$this->parent->getOverwrite())
        {
            $this->parent->abort(JText::_('Module').' '.JText::_('Install').': '.JText::_('Another module is already using directory').': "'.$this->parent->getPath('extension_root').'"');
            return false;
        }

        //--If the module directory does not exist, lets create it
        $created = false;
        if( ! file_exists($this->parent->getPath('extension_root')))
        {
            if( ! $created = JFolder::create($this->parent->getPath('extension_root')))
            {
                $this->parent->abort(JText::_('Module').' '.JText::_('Install').': '.JText::_('Failed to create directory').': "'.$this->parent->getPath('extension_root').'"');
                return false;
            }
        }

        /*
         * Since we created the module directory and will want to remove it if
         * we have to roll back the installation, lets add it to the
         * installation step stack
         */
        if ($created)
        {
            $this->parent->pushStep(array ('type' => 'folder', 'path' => $this->parent->getPath('extension_root')));
        }

        //--Copy all necessary files
        $element = &$module->getElementByPath('files');
        if ($this->parent->parseFiles($element, -1) === false)
        {
            //--Install failed, roll back changes
            $this->parent->abort();
            return false;
        }

        //--Copy language files
        $element = &$module->getElementByPath('languages');
        if ($this->parent->parseLanguages($element, $mclient->id) === false)
        {
            //--Install failed, roll back changes
            $this->parent->abort();
            return false;
        }

        //--Copy media files
        $element = &$module->getElementByPath('media');
        if ($this->parent->parseMedia($element, $mclient->id) === false)
        {
            //--Install failed, roll back changes
            $this->parent->abort();
            return false;
        }

        $mtitle		= $module->attributes('title');
        $mposition	= $module->attributes('position');
        $morder		= $module->attributes('order');

        //--If module already installed do not create a new instance
        $db =& JFactory::getDBO();
        $query = 'SELECT `id` FROM `#__modules` WHERE module = '.$db->Quote( $mname);
        $db->setQuery($query);
        if( ! $db->Query())
        {
            //--Install failed, roll back changes
            $this->parent->abort(JText::_('Module').' '.JText::_('Install').': '.$db->stderr(true));
            return false;
        }

        $id = $db->loadResult();

        if( ! $id)
        {
            $row = & JTable::getInstance('module');
            $row->title		= $mtitle;
            $row->ordering	= $morder;
            $row->position	= $mposition;
            $row->showtitle	= 0;
            $row->iscore	= 0;
            $row->access	= ($mclient->id) == 1 ? 2 : 0;
            $row->client_id	= $mclient->id;
            $row->module	= $mname;
            $row->published	= 1;
            $row->params	= '';

            if( ! $row->store())
            {
                //--Install failed, roll back changes
                $this->parent->abort(JText::_('Module').' '.JText::_('Install').': '.$db->stderr(true));
                return false;
            }

            //--Make visible evertywhere if site module
            if ($mclient->id==0)
            {
                $query = 'REPLACE INTO `#__modules_menu` (moduleid,menuid) values ('.$db->Quote( $row->id).',0)';
                $db->setQuery($query);
                if( ! $db->query())
                {
                    //--Install failed, roll back changes
                    $this->parent->abort(JText::_('Module').' '.JText::_('Install').': '.$db->stderr(true));
                    return false;
                }
            }
        }

        $status->modules[] = array('name'=>$mname,'client'=>$mclient->name);
    }//foreach
}

/***********************************************************************************************
 * ---------------------------------------------------------------------------------------------
 * PLUGIN INSTALLATION SECTION
 * ---------------------------------------------------------------------------------------------
 ***********************************************************************************************/

$plugins = &$this->manifest->getElementByPath('plugins');
if (is_a($plugins, 'JSimpleXMLElement') && count($plugins->children()))
{
    foreach ($plugins->children() as $plugin)
    {
        $pname		= $plugin->attributes('plugin');
        $pgroup		= $plugin->attributes('group');
        $porder		= $plugin->attributes('order');

        //--Set the installation path
        if( ! empty($pname) && ! empty($pgroup))
        {
            $this->parent->setPath('extension_root', JPATH_ROOT.DS.'plugins'.DS.$pgroup);
        }
        else
        {
            $this->parent->abort(JText::_('Plugin').' '.JText::_('Install').': '.JText::_('No plugin file specified'));
            return false;
        }

        /**
         * ---------------------------------------------------------------------------------------------
         * Filesystem Processing Section
         * ---------------------------------------------------------------------------------------------
         */

        //--If the plugin directory does not exist, lets create it
        $created = false;
        if( ! file_exists($this->parent->getPath('extension_root')))
        {
            if( ! $created = JFolder::create($this->parent->getPath('extension_root')))
            {
                $this->parent->abort(JText::_('Plugin').' '.JText::_('Install').': '.JText::_('Failed to create directory').': "'.$this->parent->getPath('extension_root').'"');
                return false;
            }
        }

        /*
         * If we created the plugin directory and will want to remove it if we
         * have to roll back the installation, lets add it to the installation
         * step stack
         */
        if ($created)
        {
            $this->parent->pushStep(array ('type' => 'folder', 'path' => $this->parent->getPath('extension_root')));
        }

        //--Copy all necessary files
        $element = &$plugin->getElementByPath('files');
        if ($this->parent->parseFiles($element, -1) === false)
        {
            //--Install failed, roll back changes
            $this->parent->abort();
            return false;
        }

        //--Copy all necessary files
        $element = &$plugin->getElementByPath('languages');
        if ($this->parent->parseLanguages($element, 1) === false)
        {
            //--Install failed, roll back changes
            $this->parent->abort();
            return false;
        }

        //--Copy media files
        $element = &$plugin->getElementByPath('media');
        if ($this->parent->parseMedia($element, 1) === false)
        {
            //--Install failed, roll back changes
            $this->parent->abort();
            return false;
        }

        /**
         * ---------------------------------------------------------------------------------------------
         * Database Processing Section
         * ---------------------------------------------------------------------------------------------
         */
        $db = &JFactory::getDBO();

        //--Check to see if a plugin by the same name is already installed
        $query = 'SELECT `id`' .
		' FROM `#__plugins`' .
		' WHERE folder = '.$db->Quote($pgroup) .
		' AND element = '.$db->Quote($pname);
        $db->setQuery($query);
        if( ! $db->Query())
        {
            //--Install failed, roll back changes
            $this->parent->abort(JText::_('Plugin').' '.JText::_('Install').': '.$db->stderr(true));
            return false;
        }

        $id = $db->loadResult();

        //--Was there a plugin already installed with the same name?
        if ($id)
        {
            if( ! $this->parent->getOverwrite())
            {
                //--Install failed, roll back changes
                $this->parent->abort(JText::_('Plugin').' '.JText::_('Install').': '.JText::_('Plugin').' "'.$pname.'" '.JText::_('already exists!'));
                return false;
            }
        }
        else
        {
            $row =& JTable::getInstance('plugin');
            $row->name = JText::_(ucfirst($pgroup)).' - '.JText::_(ucfirst($pname));
            $row->ordering = $porder;
            $row->folder = $pgroup;
            $row->iscore = 0;
            $row->access = 0;
            $row->client_id = 0;
            $row->element = $pname;
            $row->published = 1;
            $row->params = '';

            if( ! $row->store())
            {
                //--Install failed, roll back changes
                $this->parent->abort(JText::_('Plugin').' '.JText::_('Install').': '.$db->stderr(true));
                return false;
            }
        }

        $status->plugins[] = array('name'=>$pname,'group'=>$pgroup);
    }//foreach
}

/***********************************************************************************************
 * ---------------------------------------------------------------------------------------------
 * SETUP DEFAULTS
 * ---------------------------------------------------------------------------------------------
 ***********************************************************************************************/

/***********************************************************************************************
 * ---------------------------------------------------------------------------------------------
 * Execute specific system steps to ensure a consistent installation
 * ---------------------------------------------------------------------------------------------
 ***********************************************************************************************/
$db =& JFactory::getDBO();

// redirect_url column
$checkRedirectUrl = "SHOW columns FROM #__jquarks4s_surveys WHERE field = 'redirect_url'";
$db->setQuery($checkRedirectUrl);
$resultat = $db->loadObjectList();
if (count($resultat) == 0)
{
    $redirectUrlColumn = "ALTER TABLE `#__jquarks4s_surveys` ADD `redirect_url` varchar(255) NOT NULL DEFAULT 'index.php?option=com_jquarks4s'";
    $db->Execute($redirectUrlColumn);
}

// is_active column
$checkIsActive = "SHOW columns FROM #__jquarks4s_types WHERE field = 'redirect_url'";
$db->setQuery($checkIsActive);
$resultat = $db->loadObjectList();
if (count($resultat) == 0)
{
    $isActiveColumn = "ALTER TABLE `#__jquarks4s_users_surveys` ADD `is_active` tinyint(1) NOT NULL";
    $db->Execute($isActiveColumn);
}
/***********************************************************************************************
 * ---------------------------------------------------------------------------------------------
 * OUTPUT TO SCREEN
 * ---------------------------------------------------------------------------------------------
 ***********************************************************************************************/
$rows = 0;
?>

<h2>JQuarks4s Installation</h2>
<table class="adminlist">
	<thead>
		<tr>
			<th class="title" colspan="2"><?php echo JText::_('Extension'); ?></th>
			<th width="30%"><?php echo JText::_('Status'); ?></th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<td colspan="3"></td>
		</tr>
	</tfoot>
	<tbody>
		<tr class="row0">
			<td class="key" colspan="2"><?php echo 'JQuarks4s '.JText::_('Component'); ?></td>
			<td><img src="images/publish_g.png" alt="OK" /> <strong><?php echo JText::_('Installed'); ?></strong></td>
		</tr>
		<?php if (count($status->modules)) : ?>
		<tr>
			<th><?php echo JText::_('Module'); ?></th>
			<th><?php echo JText::_('Client'); ?></th>
			<th></th>
		</tr>
		<?php foreach ($status->modules as $module) : ?>
		<tr class="row<?php echo (++ $rows % 2); ?>">
			<td class="key"><?php echo $module['name']; ?></td>
			<td class="key"><?php echo ucfirst($module['client']); ?></td>
			<td><img src="images/publish_g.png" alt="OK" /> <strong><?php echo JText::_('Installed'); ?></strong></td>
		</tr>
		<?php endforeach;
	endif;
    if (count($status->plugins)) : ?>
		<tr>
			<th><?php echo JText::_('Plugin'); ?></th>
			<th><?php echo JText::_('Group'); ?></th>
			<th></th>
		</tr>
		<?php foreach ($status->plugins as $plugin) : ?>
		<tr class="row<?php echo (++ $rows % 2); ?>">
			<td class="key"><?php echo ucfirst($plugin['name']); ?></td>
			<td class="key"><?php echo ucfirst($plugin['group']); ?></td>
			<td><img src="images/publish_g.png" alt="OK" /> <strong><?php echo JText::_('Installed'); ?></strong></td>
		</tr>
		<?php endforeach;
    endif; ?>
	</tbody>
</table>
