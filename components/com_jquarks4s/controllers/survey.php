<?php
/**
 * JQuarks4s Component survey controller
 *
 * @version     $Id$
 * @author      IP-Tech Labs <labs@iptech-offshore.com>
 * @copyright   2010 IP-Tech
 * @package     JQuarks4s-Front-End
 * @link        http://www.iptechinside.com/labs/projects/show/jquarks-for-surveys
 * @since       1.1.2
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

defined('_JEXEC') or die('');

jimport('joomla.application.component.controller');


class JQuarks4sControllerSurvey extends JController
{    
    function display()
    {
        $survey_id = JRequest::getInt('id');

        $session =& JFactory::getSession();

        $surveyModel = $this->getModel('survey');
        $accessId = $surveyModel->getAccessId($survey_id);
        // private
        if ($accessId)
        {
            $user = JFactory::getUser();
            if ($user->id == 0) // not logged in
            {
                $msg = JText::_('THIS_IS_A_PRIVATE_SURVEY_PLEASE_LOGIN_TO_CONTINUE');
                $this->setRedirect('index.php?option=com_user&view=login', $msg, 'message');
                $this->redirect();
            }
            else
            {
                $hasAccess = $surveyModel->hasAccess($user->id, $survey_id);
                if ($hasAccess)
                {
                    $view = & $this->getView( 'survey', 'html' );
                    $view->setModel( $this->getModel( 'jquarks4s' ), true );

                    JRequest::setVar('view', 'survey');
                    JRequest::setVar('layout', 'default');

                    parent::display();
                }
                else
                {
                    $msg = JText::_('YOU_ARE_NOT_ALLOWED_TO_VIEW_THIS_SURVEY');
                    $this->setRedirect('index.php?option=com_jquarks4s', $msg, 'warning');
                    $this->redirect();
                }

            }

        }
        else
        {
            //$isUniqueSession = $surveyModel->getUniqueSession($survey_id);
            $isUniqueSession = $session->get('isunique['.$survey_id.']', 'unknown');
            $submitted = $session->get('submitted['.$survey_id.']', 'unknown');

            if ($isUniqueSession == 'yes' && $submitted == 'yes')
            {
                $msg = JText::_('YOU_HAVE_ALREADY_SUBMITTED_YOUR_ANSWERS_FOR_THIS_SURVEY') ;
                $this->setRedirect('index.php?option=com_jquarks4s', $msg, 'message');
                $this->redirect();
            }
            else // view survey
            {
                $view = & $this->getView( 'survey', 'html' );
                $view->setModel( $this->getModel( 'jquarks4s' ), true );

                JRequest::setVar('view', 'survey');
                JRequest::setVar('layout', 'default');

                parent::display();
            }
        }
    }


    

    function submitSurvey()
    {
        // get post data     
        $data = JRequest::get('post');

        // store new session
        $ip_address  = $data['ip_address'];
        $user_id     = $data['user_id'];
        $survey_id   = $data['survey_id'];

        $users_surveysModel = $this->getModel('users_surveys');
        

        $affected_id = (int)$users_surveysModel->getAffectedId($user_id, $survey_id);
        

        $sessionModel = $this->getModel('session');
        if ( ! $sessionModel->store($affected_id, $ip_address)) {
            $msg = JText::_('ERROR_SESSION_STORE_FAILED');
        }
        else
        {
            // get session_id
            $session_id = $sessionModel->getLastSessionId();

            // get and store questions answers
            $questions = $data['q'];

            // trigger onAfterSurveySubmit event
            $dispatcher =& JDispatcher::getInstance();
            $results    = $dispatcher->trigger( 'onAfterSurveySubmit', array('session_id' => $session_id, 'questions' => $questions) );

            foreach ($questions as $question_id => $question) :

                $type_id = (int)$question['type_id'];

                switch ($type_id) :
                    case 1: // text
                        $answer = $question['answer'];
                        if ( ! $sessionModel->storeTextAnswer($session_id, $question_id, $answer)) {
                            // TODO
                        }
                        break;
                    
                    case 2: // single choice
                        $proposition_id = $question['proposition'];
                        if ( $question[$proposition_id]['is_text_field'] == '1') {
                            $altAnswer = $data['qp_field'][$question_id][$proposition_id];
                        } else {
                            $altAnswer = '';
                        }
                        if ( ! $sessionModel->storeAnswer($session_id, (int)$proposition_id, $altAnswer)) {
                            // TODO
                        }
                        break;
                        
                    case 3: // multiple choice
                        $selected_propositions = $question['proposition'];
                        foreach ($selected_propositions as $proposition_id)
                        {
                            if ( $question[$proposition_id]['is_text_field'] == '1') {
                                $altAnswer = $data['qp_field'][$question_id][$proposition_id];
                            } else {
                                $altAnswer = '';
                            }
                            if ( ! $sessionModel->storeAnswer($session_id, (int)$proposition_id, $altAnswer)) {
                                // TODO
                            }
                        }
                        break;
                        
                    case 4:
                        // recuperer les row_id du question_id
                        $rowsDB = $sessionModel->getRows($question_id);
                        $rows = array();
                        $i = 0;
                        // initalisation du tableau: les colonnes sont non remplies par deafut
                        foreach ($rowsDB as $rowDB)
                        {
                            $rows[$i]['row_id'] = (int)$rowDB->id;
                            $rows[$i]['col_id'] = 0;
                            $i++;
                        }
                        
                        // remplissage des colonnes si l'on trouve des lignes cochées
                        foreach ($question as $row_id => $column_id)
                        {
                            if ($row_id != 'type_id')
                            {
                                foreach ($rows as $key => $var)
                                {
                                    if ((int)$row_id == (int)$var['row_id']) {
                                        $rows[$key]['col_id'] = (int)$column_id;                                        
                                    }
                                }
                                reset($rows);
                            }
                        }
                        
                        // insertion des reponses matrix
                        reset($rows);
                        for ($i=0; $i<count($rows); $i++) {
                            $sessionModel->storeMatAnswer($session_id, $rows[$i]['row_id'], $rows[$i]['col_id']);
                        }
                endswitch;
                    
            endforeach;
            $msg = JText::_('THANKS_FOR_PARTICIPATION_SURVEY_SUBMITTED') ;
        }

        // check for unique session
        $surveyModel = $this->getModel('survey');

        $session =& JFactory::getSession();
        $session->set('submitted['.$survey_id.']', 'yes');

        $isUniqueSession = $surveyModel->getUniqueSession($survey_id);
        if ($isUniqueSession)
        {            
            $session->set('isunique['.$survey_id.']', 'yes');
            // if registered user
            if ($user_id > 0)
            {
                // unaffect user from survey
                if ( ! $surveyModel->unauthorize($user_id, $survey_id))
                {
                    $msg = JText::_('ERROR_UNAUTHORIZING_USER') ;
                }
            }
        }
        else {
            $session->set('isunique['.$survey_id.']', 'no');
        }

        $url = $surveyModel->getRedirectUrl($survey_id);
        if ($url == '') {
            $url = 'index.php?option=com_jquarks4s';
        }
        $this->setRedirect($url, $msg, 'message');
    }
}
