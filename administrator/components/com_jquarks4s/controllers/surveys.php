<?php
/**
 * JQuarks4s Component surveys Controller
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

class JQuarks4sControllerSurveys extends JQuarks4sController
{
    /**
     *
     * @var JModel
     */
    private $_model;

  
    function __construct()
    {
        parent::__construct();

        $this->registerTask('add', 'edit');
        $this->registerTask('apply', 'save');
        $this->registerTask('saveContinue', 'save');
        $this->registerTask('unassignUser', 'assignUser');
    }

    
    function display()
    {
        JRequest::setVar('view', 'surveys');
        JRequest::setVar('layout', 'default');

        parent::display() ;
    }

    

    function setAccess()
    {
        $this->_model = $this->getModel('survey');

        if ( ! $this->_model->setAccess())
        {
            $msg = JText::_('ERROR_SETTING_ACCESS');
            $type = 'error';
        }

        $url = 'index.php?option=com_jquarks4s&controller=surveys';
        $this->setRedirect($url, $msg, $type);
    }



    function setPublished()
    {
        $this->_model = $this->getModel('survey');
        if ( ! $this->_model->setPublished())
        {
            $msg = JText::_('ERROR_PUBLICATION_FAILED');
            $type = 'error';
        }

        $url = 'index.php?option=com_jquarks4s&controller=surveys';
        $this->setRedirect($url, $msg, $type);
    }


    function showUsers()
    {
        $view = & $this->getView( 'setUsers', 'html' );
        $view->setModel( $this->getModel( 'survey' ), true );

        JRequest::setVar('view', 'setUsers');
        JRequest::setVar('layout', 'default');
        JRequest::setVar('hidemainmenu', 1);

        parent::display() ;
    }


    function assignUser()
    {
        
        $cids = JRequest::getVar( 'cid', array(0) ) ;
        $survey_id = (int)$cids[0];
        $url = 'index.php?option=com_jquarks4s&controller=surveys&task=showUsers&cid[]='.$survey_id;

        $user_id   = JRequest::getInt('uid');
        $model = $this->getModel('survey');

        $task = $this->getTask();
        switch ($task)
        {
            case 'assignUser':
                 if ( ! $model->assignUser($user_id))
                 {
                     $msg = JText::_('ERROR_AFFECTING_USER_TO_SURVEY');
                     $type = 'error';
                 }
                 else
                 {
                     $msg = JText::_('USER_AFFECTED_TO_SURVEY');
                     $type = 'message';
                 }
                 break;
        
            case 'unassignUser':
                if ( ! $model->unassignUser($user_id))
                {
                     $msg = JText::_('ERROR_UNAFFECTING_USER_FROM_SURVEY');
                     $type = 'error';
                }
                else
                {
                     $msg = JText::_('USER_UNAFFECTED_FROM_SURVEY');
                     $type = 'message';
                }
        }

        $this->setRedirect($url, $msg, $type);
    }
    
    
    function saveOrder()
    {
        $link = 'index.php?option=com_jquarks4S&controller=surveys';

        $data = JRequest::get('post');

        $cids  = $data['cid'];
        $ranks = $data['rank'];

        if (is_null($cids))
        {
            $msg = JText::_('WARNING_NO_CHECKED_SURVEY');
            $this->setRedirect($link, $msg);
        }

        $model = $this->getModel('sections');

        foreach ($cids as $survey_id)
        {
            foreach ($ranks[$survey_id] as $section_id => $section_rank)
            {
                if (is_null($section_rank)) {
                    $section_rank = 0;
                }
                // important to convert section rank to int, case not int type then it will be set to 0
                if ( ! $model->updateRank((int)$survey_id, (int)$section_id, (int)$section_rank) )
                {
                    $msg = JText::_('ERROR_UPDATING_ORDER');
                    $type = 'error';
                }
            }
        }
        $msg = JText::_('ORDER_SAVED');
        $this->setRedirect($link, $msg, $type);
    }


    /**
     * display the edit form
     * @return void
     */
    function edit()
    {
        JRequest::setVar('view', 'survey');
        JRequest::setVar('hidemainmenu', 1);

        parent::display();
    }



    function save()
    {
        $surveyModel = $this->getModel('survey');

        if ($surveyModel->store())
        {
            $msg = JText::_( 'RECORD_SAVED' );
            $type = 'message';
        }
        else
        {
            $msg = JText::_( 'ERROR_SAVING_RECORD' );
            $type = 'error';
        }

        $task = $this->getTask();
        switch ($task)
        {
            case 'saveContinue':
                $link = 'index.php?option=com_jquarks4s&controller=surveys&task=edit&cid[]=0';
                break;

            case 'apply':
                $id = JRequest::getInt('id');
                if ($id == 0) // if new survey then fetch last id from DB
                {
                    $id = $surveyModel->getLastSurveyId();
                }
                $link = 'index.php?option=com_jquarks4s&controller=surveys&task=edit&cid[]=' . $id;
                break;

            case 'save':
                   $link = 'index.php?option=com_jquarks4S&controller=surveys';
        }

        $this->setRedirect($link, $msg, $type);
    }


    
    /**
     * remove record(s)
     * @return void
     */
    function remove()
    {
        $this->_model = $this->getModel('surveys');
        if( ! $this->_model->delete() )
        {
            $msg = JText::_( 'ERROR_ONE_OR_MORE_RECORDS_COULD_NOT_BE_DELETED' );
            $type = 'error';
        }
        else
        {
            $msg = JText::_( 'RECORDS(S)_DELETED' );
            $type = 'message';
        }

        $this->setRedirect( 'index.php?option=com_jquarks4s&controller=surveys', $msg, $type );
    }

    function authorizeUsers()
    {
        $survey_id = JRequest::getInt('survey_id');
        $user_ids = JRequest::getVar( 'cid', array(0), 'post', 'array' );
        
        $surveyModel = $this->getModel('survey');
        if ( ! $surveyModel->authorize($user_ids, $survey_id))
        {
            $msg = JText::_( 'ERROR_AUTHORIZING_USERS' );
            $type = 'error';
        }
        else
        {
            $msg = JText::_( 'SUCCESS' );
            $type = 'message';
        }
        $link = 'index.php?option=com_jquarks4s&controller=surveys&task=showUsers&cid[]='.$survey_id;
        $this->setRedirect( $link, $msg, $type );
    }


    function unauthorizeUsers()
    {
        $survey_id = JRequest::getInt('survey_id');
        $user_ids = JRequest::getVar( 'cid', array(0), 'post', 'array' );

        $surveyModel = $this->getModel('survey');
        if ( ! $surveyModel->unauthorize($user_ids, $survey_id))
        {
            $msg = JText::_( 'ERROR_UNAUTHORIZING_USERS' );
            $type = 'error';
        }
        else
        {
            $msg = JText::_( 'SUCCESS' );
            $type = 'message';
        }
        $link = 'index.php?option=com_jquarks4s&controller=surveys&task=showUsers&cid[]='.$survey_id;
        $this->setRedirect( $link, $msg, $type );
        
    }

    // @TODO
    // export to PDF view
    function export()
    {
//        $view = & $this->getView( 'surveypdf', 'pdf' );
//        $view->setModel( $this->getModel( 'survey' ), true );
//
//        JRequest::setVar('view', 'surveypdf');
//
//        parent::display() ;
    }
}
