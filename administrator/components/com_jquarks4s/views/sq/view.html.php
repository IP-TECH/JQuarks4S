<?php
/**
 * JQuarks4s Component Section to Questions View
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


class JQuarks4sViewSq extends JView
{
    function display($tpl = null)
    {
        $action = JRequest::getCmd('action');
        $this->assignRef( 'action', $action );

        $section_id = JRequest::getInt('id');
        $this->assignRef( 'section_id', $section_id );

        $model =& $this->getModel('sq');

        if ($action == 'setTask') {
            $questions =& $model->getQuestionsForSet($section_id);
        }
        elseif ($action == 'unsetTask') {
            $questions =& $model->getQuestionsForUnset($section_id);
        }

        $this->assignRef( 'questions', $questions );

        parent::display($tpl);
    }

}