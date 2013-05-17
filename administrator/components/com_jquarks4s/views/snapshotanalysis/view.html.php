<?php
/**
 * JQuarks4s Component Snapshot Analysis View
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


class JQuarks4sViewSnapshotAnalysis extends JView
{
    function display($tpl = null)
    {
        $model = $this->getModel('analysis');

        $cids = JRequest::getVar( 'cid', array(0) ) ;
        $snapshot_id = (int)$cids[0];

        $analysis = $model->getSnapshotAnalysis($snapshot_id);
        $this->assignRef( 'analysis', $analysis );

        $analysisType = $analysis->analysis_type_id;
        switch ($analysisType)
        {
            case 1: // frequency
                $this->setLayout('frequency');
            
                $snapshot_survey       = $model->getSnapshotSurvey($snapshot_id);
                $snapshot_question     = $model->getSnapshotQuestions($snapshot_id);
                $snapshot_propositions = $model->getSnapshotPropositions($snapshot_question[0]->id);

                $this->assignRef( 'survey',       $snapshot_survey );
                $this->assignRef( 'question',     $snapshot_question );
                $this->assignRef( 'propositions', $snapshot_propositions );
                break;

            case 2: // cross tab
                $this->setLayout('crosstab');
                break;

            case 3: // custom
                $this->setLayout('custom');
                break;
        }


        JToolBarHelper::title(   JText::_( 'FREQUENCY_ANALYSIS_SNAPSHOT' ) );

        JToolBarHelper::title(   JText::_( 'FREQUENCY_ANALYSIS' ) );

        $bar=& JToolBar::getInstance( 'toolbar' );
        // appendButton method parameters
        // 1- button type from JButton
        // 2- css class - image of the button
        // 3- text to display on the button
        // 4- the task to set
        // 5- whether a selection must be made from an admin list before continuing.
        global $mainframe;
        $mainframe->addCustomHeadTag ('<link rel="stylesheet" href="'.$this->baseurl.'/components/com_jquarks4s/assets/css/toolbar.css" type="text/css" media="screen" />');
        $bar->appendButton( 'link', 'cancel', JText::_('BACK'), 'index.php?option=com_jquarks4s&controller=analysis' );

        // SUBMENU
        JSubMenuHelper::addEntry( JText::_( 'JQUARKS4S_HOME' ), 'index.php?option=com_jquarks4s');
        JSubMenuHelper::addEntry( JText::_( 'SURVEYS' ),        'index.php?option=com_jquarks4s&controller=surveys');
        JSubMenuHelper::addEntry( JText::_( 'SECTIONS'),        'index.php?option=com_jquarks4s&controller=sections');
        JSubMenuHelper::addEntry( JText::_( 'QUESTIONS' ),      'index.php?option=com_jquarks4s&controller=questions');
        JSubMenuHelper::addEntry( JText::_( 'ANSWERS' ),       'index.php?option=com_jquarks4s&controller=sessions');
        JSubMenuHelper::addEntry( JText::_( 'ANALYSES' ),       'index.php?option=com_jquarks4s&controller=analysis');
        JSubMenuHelper::addEntry( JText::_( 'IMPORT_EXPORT' ),  'index.php?option=com_jquarks4s&controller=datamanager');

        parent::display($tpl);
    }

}
