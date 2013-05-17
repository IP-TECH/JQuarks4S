<?php
/**
 * JQuarks4s Component Frequency analysis View
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


class JQuarks4sViewFrequencyanalysis extends JView
{
    function display($tpl = null)
    {
        $survey_id = JRequest::getVar('sid');
        $question_id = JRequest::getVar('qid');
        $row_id = JRequest::getVar('rid');

        $model = $this->getModel('analysis');

        $survey = $model->getSurveyStats($survey_id);
        $this->assignRef( 'survey', $survey );

        $question = $model->getQuestion($question_id);
        $this->assignRef( 'question', $question );
        $row_title = $model->getRowTitle($row_id);
        $this->assignRef( 'row_title', $row_title );

        $propositions =& $model->getPropositions($question_id);
        $this->assignRef( 'propositions', $propositions );

        $sessions =& $model->getSessionsForFrequency();
        $this->assignRef( 'sessions', $sessions );

        if ($question->type == '3')
        {
            $this->_layout = 'multiplechoice';
            
            $questionModel = JModel::getInstance('question', 'JQuarks4sModel');
            $propositionsStats = $questionModel->getPropositionsStats($question->id);
            $this->assignRef( 'propositionsStats', $propositionsStats );
        }

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
        
        // TODO: code temporaire
        // afficher la camera que si le snapshot est disponible
        // actuellement le snapshot n'est pas dispo pour les multiple choice questions
        if ($question->type != '3') {
            $bar->appendButton( 'popup', 'camera', JText::_('TAKE_SNAPSHOT'), 'index.php?option=com_jquarks4s&controller=analysis&task=snapshotDetails' );
        }
        $bar->appendButton( 'link', 'cancel', JText::_('BACK'), 'index.php?option=com_jquarks4s&controller=analysis&task=selectQuestions&cid[]='.$survey_id );
        

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
