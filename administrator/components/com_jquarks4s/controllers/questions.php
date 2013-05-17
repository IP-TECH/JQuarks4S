<?php
/**
 * JQuarks4s Component questions Controller
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

class JQuarks4sControllerQuestions extends JQuarks4sController
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
        $this->registerTask('add', 'edit');
        $this->registerTask('apply', 'save');
        $this->registerTask('saveContinue', 'save');
    }

    
    function display()
    {
        JRequest::setVar('view', 'questions');
        JRequest::setVar('layout', 'default');

        parent::display() ;
    }


    function cancel()
	{
		$this->setRedirect( 'index.php?option=com_jquarks4s&controller=questions' ) ;
	}


    /**
     * display the edit form
     * @return void
     */
    function edit()
    {
        JRequest::setVar('view', 'question');
        JRequest::setVar('hidemainmenu', 1);

        parent::display();
    }

    
    /**
     * remove record(s)
     * @return void
     */
    function remove()
    {
        $this->_model = $this->getModel('questions');

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

        $this->setRedirect( 'index.php?option=com_jquarks4s&controller=questions', $msg, $type );
    }

    
    /**
     * save question
     * @return void
     */
    function save()
    {
        $questionModel = $this->getModel('question');

        if ($questionModel->store())
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
                $link = 'index.php?option=com_jquarks4s&controller=questions&task=edit&cid[]=0';
                break;

            case 'apply':
                $id = (int)JRequest::getVar('id');
                if ($id == 0) // new question so fetch last id from DB
                {
                    $id = $questionModel->getLastQuestionId();
                }
                $link = 'index.php?option=com_jquarks4s&controller=questions&task=edit&cid[]=' . $id;
                break;

            default: //save
                $link = 'index.php?option=com_jquarks4S&controller=questions';
        }

        $this->setRedirect($link, $msg, $type);
    }


    
    function preview()
    {
        JRequest::setVar('view', 'prevquestion');
        JRequest::setVar('tmpl', 'component');
        JRequest::setVar('layout', 'default');

        parent::display() ;
    }

    function setCompulsory()
    {
        $this->_model = $this->getModel('question');

        if ( ! $this->_model->setCompulsory())
        {
            $msg = JText::_('ERROR_CHANGING_COMPULSORY_PROPERTY');
            $type = 'error';
        }

        $url = 'index.php?option=com_jquarks4s&controller=questions';
        $this->setRedirect($url, $msg, $type);
    }

    function setNature()
    {
        $this->_model = $this->getModel('question');

        if ( ! $this->_model->setNature())
        {
            $msg = JText::_('ERROR_SETTING_CHARACTER_NATURE');
            $type = 'error';
        }

        $url = 'index.php?option=com_jquarks4s&controller=questions';
        $this->setRedirect($url, $msg, $type);

    }
    

}
