<?php
/**
 * JQuarks4s Component Session Model
 *
 * @version     $Id$
 * @author      IP-Tech Labs <labs@iptech-offshore.com>
 * @copyright   2010 IP-Tech
 * @package     JQuarks4s-Back-End
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
        $this->_id = JRequest::getInt('id');
    }

    
    public function getId()
    {
        return $this->_id;
    }

    
    public function getSession($session_id = null)
    {
        if (is_null($session_id))
        {
            $session_id = $this->_id;
        }
        $query = 'SELECT s.id, u.name as user_name, s.ip_address, s.submit_date, us.survey_id'.
        ' FROM #__jquarks4s_sessions s'.
        ' LEFT JOIN #__jquarks4s_users_surveys us ON s.affected_id = us.id'.
        ' LEFT JOIN #__users u ON u.id = us.user_id'.
        ' WHERE s.id = '.$session_id;

        $this->_db->setQuery($query);
        return $this->_db->loadObject();
    }


    public function getAnswers($surv_id)
    {
        $survey_id = strval($surv_id);

        $query = ' ( SELECT q.id, null AS pid, ans.answer AS answer, null AS altanswer, null AS row_title'.
        ' , ss.section_rank, sq.question_rank'.
        ' FROM #__jquarks4s_surveys s'.
        ' LEFT JOIN #__jquarks4s_surveys_sections ss ON s.id = ss.survey_id'.
        ' LEFT JOIN #__jquarks4s_sections sec ON ss.section_id = sec.id'.
        ' LEFT JOIN #__jquarks4s_sections_questions sq ON sec.id = sq.section_id'.
        ' LEFT JOIN #__jquarks4s_questions q ON sq.question_id = q.id'.
        ' LEFT JOIN #__jquarks4s_text_answers ans ON q.id = ans.question_id'.
        ' WHERE s.id = '.$survey_id.
        ' AND ans.session_id = '.$this->_id.
        ' AND q.type_id = 1'.
        ' )'.
        ' UNION'.
        ' ('.
        ' SELECT q.id, p.id AS pid, p.proposition AS answer, ans.altanswer AS altanswer, null AS row_title'.
        ' , ss.section_rank, sq.question_rank'.
        ' FROM #__jquarks4s_surveys s'.
        ' LEFT JOIN #__jquarks4s_surveys_sections ss ON s.id = ss.survey_id'.
        ' LEFT JOIN #__jquarks4s_sections sec ON ss.section_id = sec.id'.
        ' LEFT JOIN #__jquarks4s_sections_questions sq ON sec.id = sq.section_id'.
        ' LEFT JOIN #__jquarks4s_questions q ON sq.question_id = q.id'.
        ' LEFT JOIN #__jquarks4s_propositions p ON p.question_id = q.id'.
        ' LEFT JOIN #__jquarks4s_answers ans ON ans.proposition_id = p.id'.
        ' WHERE s.id = '.$survey_id.
        ' AND ans.session_id = '.$this->_id.
        ' AND ( q.type_id = 2 OR q.type_id = 3 )'.
        ' )'.
        ' UNION'.
        ' ('.
        ' SELECT q.id, null AS pid, col.title AS answer, null AS altanswer, r.title AS row_title'.
        ' , ss.section_rank, sq.question_rank'.
        ' FROM #__jquarks4s_surveys s'.
        ' LEFT JOIN #__jquarks4s_surveys_sections ss ON s.id = ss.survey_id'.
        ' LEFT JOIN #__jquarks4s_sections sec ON ss.section_id = sec.id'.
        ' LEFT JOIN #__jquarks4s_sections_questions sq ON sec.id = sq.section_id'.
        ' LEFT JOIN #__jquarks4s_questions q ON sq.question_id = q.id'.
        ' LEFT JOIN #__jquarks4s_mat_rows r ON r.question_id = q.id'.
        ' LEFT JOIN #__jquarks4s_mat_answers ans ON ans.row_id = r.id'.
        ' LEFT JOIN #__jquarks4s_mat_columns col ON ans.column_id = col.id'.
        ' WHERE s.id = '.$survey_id.
        ' AND ans.session_id = '.$this->_id.
        ' AND q.type_id = 4'.
        ' )'.
        ' ORDER BY section_rank, question_rank, pid';

        return $this->_getList($query);
    }

}
