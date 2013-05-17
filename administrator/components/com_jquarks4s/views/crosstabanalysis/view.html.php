<?php
/**
 * JQuarks4s Component cross tab analysis View
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


class JQuarks4sViewCrosstabanalysis extends JView
{
    function display($tpl = null)
    {
        $survey_id = JRequest::getVar('sid');

        $question_id1 = JRequest::getVar('qid1');
        $row_id1 = JRequest::getVar('rid1');

        $question_id2 = JRequest::getVar('qid2');
        $row_id2 = JRequest::getVar('rid2');

        $model = $this->getModel('analysis');

        $survey = $model->getSurveyStats($survey_id);
        $this->assignRef( 'survey', $survey );

        $question1 = $model->getQuestion($question_id1);
        $this->assignRef( 'question1', $question1 );
        $propositions1 =& $model->getPropositions($question_id1);
        $this->assignRef( 'propositions1', $propositions1 );

        $question2 = $model->getQuestion($question_id2);
        $this->assignRef( 'question2', $question2 );
        $propositions2 =& $model->getPropositions($question_id2);
        $this->assignRef( 'propositions2', $propositions2 );

        $sessions1 =& $model->getSessionsForCrosstab(1);
        $this->assignRef( 'sessions1', $sessions1 );

        $sessions2 =& $model->getSessionsForCrosstab(2);
        $this->assignRef( 'sessions2', $sessions2 );

        $nbrNANA = count($model->getNotAnsweredForCrossTab(1));
        $this->assignRef( 'nbrNANA', $nbrNANA );

        JToolBarHelper::title(   JText::_( 'CROSS_TAB_ANALYSIS' ) );

        $bar=& JToolBar::getInstance( 'toolbar' );
        // appendButton method parameters
        // 1- button type from JButton
        // 2- css class - image of the button
        // 3- text to display on the button
        // 4- the task to set
        // 5- whether a selection must be made from an admin list before continuing.
        global $mainframe;
        $mainframe->addCustomHeadTag ('<link rel="stylesheet" href="'.$this->baseurl.'/components/com_jquarks4s/assets/css/toolbar.css" type="text/css" media="screen" />');
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
