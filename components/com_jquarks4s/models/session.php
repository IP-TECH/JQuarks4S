<?php
/**
 * JQuarks4s Component Session Model
 *
 * @version     $Id$
 * @author      IP-Tech Labs <labs@iptech-offshore.com>
 * @copyright   2010 IP-Tech
 * @package     JQuarks4s-Front-End
 * @subpackage  Models
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

jimport('joomla.application.component.model');

class JQuarks4sModelSession extends JModel
{
    /**
     * @var int
     */
    private $_id;

    public function __construct()
    {
        parent::__construct();
        
    }

    /**
     * store new session
     * @param int $affected_id
     * @param string $ip_address
     * @return boolean
     */
    public function store($affected_id = 0, $ip_address = '0.0.0.0')
    {
        $session = $this->getTable('sessions');
        $session->id = 0;
        $session->affected_id = (int)$affected_id;
        $session->ip_address  = $ip_address;

        if ( ! $session->store()) {
            return false;
        }
        return true;
    }

    /**
     * last saved session id
     * @return int
     */
    public function getLastSessionId()
    {
        $query = 'SELECT MAX(id)'.
        ' FROM #__jquarks4s_sessions';

        $this->_db->setQuery($query);
        return $this->_db->loadResult();
    }



    public function storeTextAnswer($session_id, $question_id, $answer)
    {
        $textAnswer = $this->getTable('text_answers');
        $textAnswer->id          = 0;
        $textAnswer->answer      = $answer;
        $textAnswer->session_id  = $session_id;
        $textAnswer->question_id = $question_id;

        if ( ! $textAnswer->store()) {
            return false;
        }
        return true;
    }


    public function storeAnswer($session_id = 0, $proposition_id = 0, $altAnswer = '')
    {
        $Answer = $this->getTable('answers');
        $Answer->id             = 0;
        $Answer->altanswer      = $altAnswer;
        $Answer->session_id     = $session_id;
        $Answer->proposition_id = $proposition_id;

        if ( ! $Answer->store()) {
            return false;
        }
        return true;
    }


    public function getRows($question_id)
    {
        $query = 'SELECT id '.
        ' FROM #__jquarks4s_mat_rows'.
        ' WHERE question_id = '.(int)$question_id;
        
        return $this->_getList($query);
    }


    public function storeMatAnswer($session_id = 0, $row_id = 0, $column_id = 0)
    {
        $Answer = $this->getTable('mat_answers');
        $Answer->id         = 0;
        $Answer->session_id = $session_id;
        $Answer->row_id     = $row_id;
        $Answer->column_id  = $column_id;

        if ( ! $Answer->store()) {
            return false;
        }
        return true;
    }
}
