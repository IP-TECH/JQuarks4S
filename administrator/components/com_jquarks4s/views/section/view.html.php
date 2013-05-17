<?php
/**
 * JQuarks4s Component Section View
 *
 * @version     $Id$
 * @author      IP-Tech Labs <labs@iptech-offshore.com>
 * @copyright   2010 IP-Tech
 * @package     JQuarks4s-Back-End
 * @subpackage  Views
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

jimport('joomla.application.component.view');


class JQuarks4sViewSection extends JView
{
    function display($tpl = null)
    {
        $model =& $this->getModel('section');

        $section =& $model->getSection();

        $this->assignRef( 'section', $section );

        $isNew = ($section->id < 1);
		$text = $isNew ? JText::_( 'NEW' ) : JText::_( 'EDIT' );
  		JToolBarHelper::title(   JText::_( 'SECTION' ).': <small><small>[ ' . $text .' ]</small></small>' );

        

        $bar=& JToolBar::getInstance( 'toolbar' );
        // appendButton method parameters
        // 1- button type from JButton
        // 2- css class - image of the button
        // 3- text to display on the button
        // 4- the task to set
        // 5- whether a selection must be made from an admin list before continuing.
        if ($section->id == 0) {
            $bar->appendButton( 'link', 'publish', JText::_('PLACE_IN_SURVEY'), 'index.php?option=com_jquarks4s&amp;controller=ss&amp;task=showPlace&amp;id='.$section->id, false );
        }
        else {
            $bar->appendButton( 'Popup', 'publish', JText::_('PLACE_IN_SURVEY'), 'index.php?option=com_jquarks4s&amp;controller=ss&amp;task=showPlace&amp;id='.$section->id, 550, 400 );
        }
        
        $bar->appendButton( 'Popup', 'unpublish', JText::_('UNPLACE_FROM_SURVEY'), 'index.php?option=com_jquarks4s&amp;controller=ss&amp;task=showUnplace&amp;id='.$section->id, 550, 400 );

        if ($section->id == 0) {
            $bar->appendButton( 'link', 'publish', JText::_('SET_QUESTIONS'), 'index.php?option=com_jquarks4s&amp;controller=sq&amp;task=showSet&amp;id='.$section->id, false );
        }
        else {
            $bar->appendButton( 'Popup', 'publish', JText::_('SET_QUESTIONS'), 'index.php?option=com_jquarks4s&amp;controller=sq&amp;task=showSet&amp;id='.$section->id, 550, 400 );
        }
        
        $bar->appendButton( 'Popup', 'unpublish', JText::_('UNSET_QUESTIONS'), 'index.php?option=com_jquarks4s&amp;controller=sq&amp;task=showUnset&amp;id='.$section->id, 550, 400 );

        JToolBarHelper::save() ;
		JToolBarHelper::custom( 'saveContinue', 'save.png', 'save.png', JText::_('SAVECONTINUE'), false, false);
        JToolBarHelper::apply() ;

		if ($isNew) {
			JToolBarHelper::cancel();
		} else {
			JToolBarHelper::cancel( 'cancel', 'Close' ) ;
		}
        parent::display($tpl);
    }

}