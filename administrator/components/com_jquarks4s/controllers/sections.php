<?php
/**
 * JQuarks4s Component sections Controller
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

class JQuarks4sControllerSections extends JQuarks4sController
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
    }

    function display()
    {
        JRequest::setVar('view', 'sections');
        JRequest::setVar('layout', 'default');

        parent::display() ;
    }


    /**
     * display the edit form
     * @return void
     */
    function edit()
    {
        JRequest::setVar('view', 'section');
        JRequest::setVar('hidemainmenu', 1);

        parent::display();
    }


    /**
     * remove record(s)
     * @return void
     */
    function remove()
    {
        $this->_model = $this->getModel('sections');
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

        $this->setRedirect( 'index.php?option=com_jquarks4s&controller=sections', $msg, $type );
    }


    function save()
    {
        $sectionModel = $this->getModel('section');

        if ($sectionModel->store())
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
                $link = 'index.php?option=com_jquarks4s&controller=sections&task=edit&cid[]=0';
                break;

            case 'apply':
                $id = JRequest::getInt('id');
                if ($id == 0) // if new section then fetch last id from DB
                {
                    $id = $sectionModel->getLastSectionId();
                }
                $link = 'index.php?option=com_jquarks4s&controller=sections&task=edit&cid[]=' . $id;
                break;

            case 'save': // save
                $link = 'index.php?option=com_jquarks4S&controller=sections';
        }

        $this->setRedirect($link, $msg, $type);
    }


    function saveOrder()
    {
        $link = 'index.php?option=com_jquarks4S&controller=sections';

        $data = JRequest::get('post');
        
        $cids  = $data['cid'];
        $ranks = $data['rank'];

        if (is_null($cids))
        {
            $msg = JText::_('WARNING_NO_CHECKED_SECTION');
            $this->setRedirect($link, $msg, 'notice');
        }

        $model = $this->getModel('questions');

        foreach ($cids as $section_id)
        {
            foreach ($ranks[$section_id] as $question_id => $question_rank)
            {
                if (is_null($question_rank)) {
                    $question_rank = 0;
                }
                // important to convert question rank to int, case not int type then it will be set to 0
                if ( ! $model->updateRank((int)$section_id, (int)$question_id, (int)$question_rank) )
                {
                    $msg = JText::_('ERROR_UPDATING_ORDER');
                    $type = 'error';
                }
            }
        }
        $msg = JText::_('ORDER_SAVED');
        $this->setRedirect($link, $msg, $type);
    }


}
