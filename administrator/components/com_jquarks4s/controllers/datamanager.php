<?php
/**
 * JQuarks4s Component Data Manager Controller
 *
 * @version     $Id$
 * @author      IP-Tech Labs <labs@iptech-offshore.com>
 * @copyright   2010 IP-Tech
 * @package     JQuarks4s-Back-End
 * @subpackage  Controllers
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

jimport('joomla.application.component.controller');

class JQuarks4sControllerDatamanager extends JQuarks4sController
{
    /**
     *
     * @var JModel
     */
    private $_model;

  
    function __construct()
    {
        parent::__construct();
        $this->_model = $this->getModel() ;
        //$this->registerTask();
    }

    function display()
    {
        JRequest::setVar('view', 'datamanager');
        JRequest::setVar('layout', 'default');
        
        parent::display() ;
    }


    function export()
    {
        $export_type = JRequest::getInt('export_type');
        switch ($export_type)
        {
            case 1: // answers
                $view = & $this->getView( 'exportanswers', 'html' );
                $view->setModel( $this->getModel( 'surveys' ), true );

                JRequest::setVar('view', 'exportanswers');
                JRequest::setVar('layout', 'default');
                parent::display() ;
                break;

            case 2:
                break;

            case 3:
                break;

            case 4:
                break;
        }

    }

    /**
     * save selected survey ID to JSession (in export answers view)
     */
    function setSurveyID()
    {
        $survey_id = JRequest::getInt('id');

        $currentSession = &JFactory::getSession();
        $currentSession->set('survey_id', $survey_id);
    }

    
    function exportAnswers()
    {
        $currentSession = &JFactory::getSession();
        $survey_id = $currentSession->get('survey_id', 'none');
        $questions = $currentSession->get('questions_to_export', array());
        $sessions = $currentSession->get('sessions_to_export', array());

        $DataManager = &$this->getModel('datamanager');
        $answers = &$DataManager->getAnswersForExport($survey_id);
        
        // Require additional files
        require_once (JPATH_COMPONENT . DS . 'helpers' . DS . 'CSVAnswers.php');

        Jquarks4sCsvAnswersHelper::write($answers, $questions, $sessions);
        $file = Jquarks4sCsvAnswersHelper::getContent();

        // send csv file
        header('Content-Description: File Transfer');
		header('Content-Type: text/csv') ;
		header('Content-Disposition: attachment; filename=jquarks4s_answers.csv');
		header('Content-Transfer-Encoding: binary');
    	header('Expires: 0');
    	header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    	header('Pragma: public');
        echo $file; exit;
    }


    function selectQuestions()
    {
        $view = & $this->getView( 'questionstoexport', 'html' );
        $view->setModel( $this->getModel( 'survey' ), true );

        JRequest::setVar('view', 'questionstoexport');
        JRequest::setVar('layout', 'default');
        parent::display() ;
    }


    function selectSessions()
    {
        $view = & $this->getView( 'sessionstoexport', 'html' );
        $view->setModel( $this->getModel( 'survey' ), true );

        JRequest::setVar('view', 'sessionstoexport');
        JRequest::setVar('layout', 'default');
        parent::display() ;
    }

    /**
     * save selected questions to JSession for future export
     */
    function registerQuestions()
    {
        $questions = JRequest::getVar( 'cid', array(), 'post', 'array' );

        $currentSession = &JFactory::getSession();
        $currentSession->set('questions_to_export', $questions);

        $this->setRedirect('index.php?option=com_jquarks4s&view=nothing&tmpl=component');
    }


    function registerSessions()
    {
        $sessions = JRequest::getVar( 'cid', array(), 'post', 'array' );

        $currentSession = &JFactory::getSession();
        $currentSession->set('sessions_to_export', $sessions);

        $this->setRedirect('index.php?option=com_jquarks4s&view=nothing&tmpl=component');
    }



}
