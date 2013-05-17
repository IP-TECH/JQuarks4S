<?php
/**
 * JQuarks4s Component Export Answers View
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


class JQuarks4sViewExportAnswers extends JView
{
    function display($tpl = null)
    {
        $surveys = &$this->get( 'SurveysForAnalysis' );
        $this->assignRef( 'surveys', $surveys );

        // TOOLBAR
  		JToolBarHelper::title( JText::_( 'EXPORT_ANSWERS' ), 'generic.png' );

        $bar=& JToolBar::getInstance( 'toolbar' );
        // appendButton method parameters
        // 1- button type from JButton
        // 2- css class - image of the button
        // 3- text to display on the button
        // 4- the task to set
        // 5- whether a selection must be made from an admin list before continuing.
        global $mainframe;
        $mainframe->addCustomHeadTag ('<link rel="stylesheet" href="'.$this->baseurl.'/components/com_jquarks4s/assets/css/toolbar.css" type="text/css" media="screen" />');
        $bar->appendButton( 'popup', 'question', JText::_('SELECT_QUESTIONS'),
                'index.php?option=com_jquarks4s&controller=datamanager&task=selectQuestions&tmpl=component' );
        $bar->appendButton( 'popup', 'session',  JText::_('SELECT_SESSIONS'),
                'index.php?option=com_jquarks4s&controller=datamanager&task=selectSessions&tmpl=component' );
        $bar->appendButton( 'link',  'export',   JText::_('EXPORT'),
                'index.php?option=com_jquarks4s&controller=datamanager&task=exportAnswers' );

        // SUBMENU
        JSubMenuHelper::addEntry( JText::_( 'JQUARKS4S_HOME' ), 'index.php?option=com_jquarks4s');
        JSubMenuHelper::addEntry( JText::_( 'SURVEYS' ),        'index.php?option=com_jquarks4s&controller=surveys');
        JSubMenuHelper::addEntry( JText::_( 'SECTIONS'),        'index.php?option=com_jquarks4s&controller=sections');
        JSubMenuHelper::addEntry( JText::_( 'QUESTIONS' ),      'index.php?option=com_jquarks4s&controller=questions');
        JSubMenuHelper::addEntry( JText::_( 'ANSWERS' ),       'index.php?option=com_jquarks4s&controller=sessions');
        JSubMenuHelper::addEntry( JText::_( 'ANALYSES' ),       'index.php?option=com_jquarks4s&controller=analysis');
        JSubMenuHelper::addEntry( JText::_( 'IMPORT_EXPORT' ),  'index.php?option=com_jquarks4s&controller=datamanager', true);

        parent::display($tpl);
    }

}
