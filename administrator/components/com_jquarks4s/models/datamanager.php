<?php
/**
 * JQuarks4s Component Data Manager Model
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

class JQuarks4sModelDataManager extends JModel
{
    
    public function __construct()
    {
        parent::__construct();
    }


    public function getAnswersForExport($survey_id)
    {
        $query =  ' ( SELECT s.id AS survey_id, sess.id AS session_id, sess.submit_date, sess.ip_address, u.name AS user_name, us.user_id,  '.
         ' q.alias AS question_alias, q.id AS question_id, q.type_id AS question_type_id, ans.answer AS answer, null AS altanswer, ans.id AS answer_id,'.
         ' null AS row_title, null AS row_id'.
         ' FROM #__jquarks4s_surveys s'.
         ' LEFT JOIN #__jquarks4s_surveys_sections ss ON s.id = ss.survey_id'.
         ' LEFT JOIN #__jquarks4s_sections sec ON ss.section_id = sec.id'.
         ' LEFT JOIN #__jquarks4s_sections_questions sq ON sec.id = sq.section_id'.
         ' LEFT JOIN #__jquarks4s_questions q ON sq.question_id = q.id'.
         ' LEFT JOIN #__jquarks4s_text_answers ans ON q.id = ans.question_id'.
         ' RIGHT JOIN #__jquarks4s_sessions sess ON sess.id = ans.session_id'.
         ' LEFT JOIN #__jquarks4s_users_surveys us ON sess.affected_id = us.id'.
         ' LEFT JOIN #__users u ON us.user_id = u.id'.
         ' WHERE q.type_id = 1'.
         ' AND s.id = '.$survey_id.
         ' )'.
         ' UNION'.
         ' ('.
         ' SELECT s.id AS survey_id, sess.id AS session_id, sess.submit_date, sess.ip_address, u.name AS user_name, us.user_id, '.
         ' q.alias AS question_alias, q.id AS question_id, q.type_id AS question_type_id, p.proposition AS answer, ans.altanswer AS altanswer, ans.id AS answer_id,'.
         ' null AS row_title, null AS row_id'.
         ' FROM #__jquarks4s_surveys s'.
         ' LEFT JOIN #__jquarks4s_surveys_sections ss ON s.id = ss.survey_id'.
         ' LEFT JOIN #__jquarks4s_sections sec ON ss.section_id = sec.id'.
         ' LEFT JOIN #__jquarks4s_sections_questions sq ON sec.id = sq.section_id'.
         ' LEFT JOIN #__jquarks4s_questions q ON sq.question_id = q.id'.
         ' LEFT JOIN #__jquarks4s_propositions p ON p.question_id = q.id'.
         ' LEFT JOIN #__jquarks4s_answers ans ON ans.proposition_id = p.id'.
         ' RIGHT JOIN #__jquarks4s_sessions sess ON sess.id = ans.session_id'.
         ' LEFT JOIN #__jquarks4s_users_surveys us ON sess.affected_id = us.id'.
         ' LEFT JOIN #__users u ON us.user_id = u.id'.
         ' WHERE  ( q.type_id = 2 OR q.type_id = 3 )'.
         ' AND s.id = '.$survey_id.
         ' )'.
         ' UNION'.
         ' ('.
         ' SELECT s.id AS survey_id, sess.id AS session_id, sess.submit_date, sess.ip_address, u.name AS user_name, us.user_id, '.
         ' q.alias AS question_alias, q.id AS question_id, q.type_id AS question_type_id, col.title AS answer, null AS altanswer, ans.id AS answer_id,'.
         ' r.title AS row_title, r.id AS row_id'.
         ' FROM #__jquarks4s_surveys s'.
         ' LEFT JOIN #__jquarks4s_surveys_sections ss ON s.id = ss.survey_id'.
         ' LEFT JOIN #__jquarks4s_sections sec ON ss.section_id = sec.id'.
         ' LEFT JOIN #__jquarks4s_sections_questions sq ON sec.id = sq.section_id'.
         ' LEFT JOIN #__jquarks4s_questions q ON sq.question_id = q.id'.
         ' LEFT JOIN #__jquarks4s_mat_rows r ON r.question_id = q.id'.
         ' LEFT JOIN #__jquarks4s_mat_answers ans ON ans.row_id = r.id'.
         ' LEFT JOIN #__jquarks4s_mat_columns col ON ans.column_id = col.id'.
         ' RIGHT JOIN #__jquarks4s_sessions sess ON sess.id = ans.session_id'.
         ' LEFT JOIN #__jquarks4s_users_surveys us ON sess.affected_id = us.id'.
         ' LEFT JOIN #__users u ON us.user_id = u.id'.
         ' WHERE q.type_id = 4'.
         ' AND s.id = '.$survey_id.
         ' )'.
         ' ORDER BY session_id, question_id';

        return $this->_getList($query);
    }
    

}
