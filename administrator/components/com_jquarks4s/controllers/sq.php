<?php
/**
 * JQuarks4s Component Section to questions Controller
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

class JQuarks4sControllerSq extends JQuarks4sController
{
    /**
     *
     * @var JModel
     */
    private $_model;

  
    function __construct()
    {
        parent::__construct();

        $this->_model = $this->getModel('sq') ;

        $this->registerTask('showUnset', 'showSet');
        $this->registerTask('unsetTask', 'setTask');
    }

        
    function showSet()
    {
        $task = $this->getTask();
        switch ($task)
        {
            case 'showSet':
                $id = JRequest::getInt('id');
                if ($id == 0)
                {
                    $url  = 'index.php?option=com_jquarks4s&controller=sections&task=edit&cid[]=0';
                    $msg  = JText::_('WARNING_YOU_MUST_SAVE_THE_SECTION_BEFORE');
                    $type = 'notice';
                        
                    $this->setRedirect($url, $msg, $type);
                }
                $action = 'setTask';
                break;
                
            case 'showUnset':
                $action = 'unsetTask';
        }

        JRequest::setVar('action', $action);
        JRequest::setVar('view', 'sq');
        JRequest::setVar('tmpl', 'component');
        JRequest::setVar('layout', 'default');

        parent::display() ;
    }

    
    function setTask()
    {
        switch ($this->getTask())
        {
            case 'setTask':
                $this->_model->setQuestions();
                break;

            case 'unsetTask':
                $this->_model->unsetQuestions();
        }
        $this->setRedirect('index.php?option=com_jquarks4s&view=nothing&tmpl=component');
    }

    
}
