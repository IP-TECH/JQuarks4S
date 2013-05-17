<?php
/**
 * JQuarks4s Component Session View
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


class JQuarks4sViewSession extends JView
{
    function display($tpl = null)
    {
       $sessionModel = $this->getModel('session');
       $surveyModel  = $this->getModel('survey');

       $cids = JRequest::getVar( 'cid', array(0) ) ;
       $survey_id = (int)$cids[0];
       $answers   = $sessionModel->getAnswers($survey_id);

       $session   = $sessionModel->getSession();
       $questions = $surveyModel->getQuestions();
       $survey    = $surveyModel->getSurvey();

       $session_id = (int)$session->id;
       $nextSession     = $sessionModel->getSession($session_id + 1);
       $previousSession = $sessionModel->getSession($session_id - 1);

        
       $this->assignRef( 'session',   $session );
       $this->assignRef( 'questions', $questions );
       $this->assignRef( 'answers',   $answers );
       $this->assignRef( 'survey',    $survey );
       $this->assignRef( 'nextSession',    $nextSession );
       $this->assignRef( 'previousSession',    $previousSession );

       JToolBarHelper::title( JText::_( 'SESSION' ).': <small><small> #' . $session->id .'</small></small>' );
       $bar=& JToolBar::getInstance( 'toolbar' );
       $bar->appendButton( 'link', 'cancel', JText::_('BACK'), 'index.php?option=com_jquarks4s&amp;controller=sessions' );

        parent::display($tpl);
    }

}
