<?php
/**
 * JQuarks4s Component Survey Model
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

class JQuarks4sModelSurvey extends JModel
{
    /**
     * @var int
     */
    private $_id;

    public function __construct()
    {
        parent::__construct();
        $cids = JRequest::getVar( 'cid', array(0) ) ;
        $this->_id = (int)$cids[0];
        
    }

    public function getId()
    {
        return $this->_id;
    }

    /**
     * ID oh the last saved survey
     * @return int
     */
    public function getLastSurveyId()
    {
        $query = 'SELECT MAX(id)'.
        ' FROM #__jquarks4s_surveys';

        $this->_db->setQuery($query);
        return $this->_db->loadResult();
    }

    
    private function getAccess()
    {
        
        $num_args = func_num_args();
        if ($num_args == 0)
        {
            $survey_id = $this->_id;
        }
        elseif ($num_args == 1)
        {
            $survey_id = func_get_arg(0);
        }
        else
        {
            return false;
        }
        
        $query = 'SELECT access_id
                  FROM #__jquarks4s_surveys
                  WHERE id = '.$survey_id;
        
        $this->_db->setQuery($query);
        return (int)$this->_db->loadResult();
    }


    public function store()
    {
        $row =& $this->getTable('surveys');

        $data = JRequest::get('post', JREQUEST_ALLOWHTML);
        $isNew = !(boolean)$data['id'];
        if ( ! $row->bind($data))
        {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }

        // Make sure the record is valid
        if ( ! $row->check())
        {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }
        // Store the web link table to the database
        if ( ! $row->store())
        {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }

        $access_id = (int)$row->access_id;
        $survey_id = $row->id;

        if ($isNew) {
            $this->createAffectations($survey_id);
        }

        // affectation
        $old_access_id = $this->getAccess($survey_id);
        if ($access_id == 0) // public
        {
            if ( ! $this->assignAllUsers($survey_id)) {
                return false;
            }
        }
        elseif ($access_id == 1 && $access_id != $old_access_id)
        {
            if ( ! $this->unassignAllUsers($survey_id)) {
                return false;
            }
        }
        return true;
    }

    
    private function createAffectations($survey_id)
    {
        // storing anonym user
        $row = $this->getTable('users_surveys');
        $row->id        = 0;
        $row->survey_id = $survey_id;
        $row->user_id   = 0;
        $row->is_active = false;
        if ( ! $row->store()) {
            return false;
        }

        // allowing access to all registered users
        $query = 'SELECT id FROM #__users';
        $this->_db->setQuery($query);
        $ids = $this->_db->loadResultArray();
        
        foreach ($ids as $user_id)
        {
            $row->id        = 0; // new row for insert query
            $row->survey_id = $survey_id;
            $row->user_id   = $user_id;
            $row->is_active = false;
            if ( ! $row->store()) {
                return false;
            }
        }
        return true;
    }

    
    private function unassignAllUsers($survey_id)
    {
        $query = 'UPDATE #__jquarks4s_users_surveys'.
        ' SET is_active = 0'.
        ' WHERE survey_id = '.$survey_id;

        $this->_db->setQuery($query);

        if ( ! $this->_db->query()) {
            return false;
        }

        return true;
    }


        private function assignAllUsers($survey_id)
    {
        $query = 'UPDATE #__jquarks4s_users_surveys'.
        ' SET is_active = 1'.
        ' WHERE survey_id = '.$survey_id;

        $this->_db->setQuery($query);

        if ( ! $this->_db->query()) {
            return false;
        }

        return true;
    }

    /**
     *
     * @param int $user_id
     * @return boolean
     */
    public function assignUser($user_id)
    {
        $query = 'UPDATE #__jquarks4s_users_surveys'.
                 ' SET is_active = 1'.
                 ' WHERE survey_id = '.$this->_id.
                 ' AND user_id = '.$user_id;
        $this->_db->setQuery($query);

        if ( ! $this->_db->query()) {
            return false;
        }

        return true;
    }

    
    /**
     *
     * @param int $user_id
     * @return boolean
     */
    public function unassignUser($user_id)
    {
        $query = 'UPDATE #__jquarks4s_users_surveys'.
                 ' SET is_active = 0'.
                 ' WHERE survey_id = '.$this->_id.
                 ' AND user_id = '.$user_id;
        $this->_db->setQuery($query);

        if ( ! $this->_db->query()) {
            return false;
        }

        return true;
    }


    /**
     * if user is affected survey_id = $survey_id else null
     * @return array_object
     */
    public function getUsers()
    {
        $query = 'SELECT u.id, u.name, us.is_active AS is_affected'.
        ' FROM #__jquarks4s_users_surveys us'.
        ' JOIN #__users u ON u.id = us.user_id'.
        ' WHERE us.survey_id = '.$this->_id;

        $this->_db->setQuery($query);

        return $this->_db->loadObjectList();
    }


    public function getSurveyPdf()
    {
        $query = ' ( SELECT s.id as survey_id, s.title, s.description, s.footer,'.
        '   sec.id as section_id, sec.name as section_name, ss.section_rank, '.
        '   sq.question_rank, q.id  as question_id, q.type_id, q.statement, q.is_compulsory,'.
        '   r.id as row_id, r.title as row_title, '.
        '   null as column_id, null as column_title, '.
        '   null as proposition_id, null as proposition, null as is_text_field'.
        ' FROM #__jquarks4s_surveys s '.
        '   LEFT JOIN #__jquarks4s_surveys_sections ss ON s.id = ss.survey_id'.
        '   LEFT JOIN #__jquarks4s_sections sec ON ss.section_id = sec.id'.
        '   LEFT JOIN #__jquarks4s_sections_questions sq ON sec.id = sq.section_id'.
        '   LEFT JOIN #__jquarks4s_questions q ON sq.question_id = q.id'.
        '   LEFT JOIN #__jquarks4s_mat_rows r ON q.id = r.question_id'.
        ' WHERE s.id = '.$this->_id.
        '   AND q.type_id = 4'.
        ' )'.
        ' UNION'.
        ' ( SELECT s.id as survey_id, s.title, s.description, s.footer,'.
        '   sec.id as section_id, sec.name as section_name, ss.section_rank, '.
        '   sq.question_rank, q.id  as question_id, q.type_id, q.statement, q.is_compulsory, '.
        '   null as row_id, null as row_title, '.
        '   c.id as title_id, c.title as column_title, '.
        '   null as proposition_id, null as proposition, null as is_text_field'.
        ' FROM #__jquarks4s_surveys s '.
        '   LEFT JOIN #__jquarks4s_surveys_sections ss ON s.id = ss.survey_id'.
        '   LEFT JOIN #__jquarks4s_sections sec ON ss.section_id = sec.id'.
        '   LEFT JOIN #__jquarks4s_sections_questions sq ON sec.id = sq.section_id'.
        '   LEFT JOIN #__jquarks4s_questions q ON sq.question_id = q.id'.
        '   LEFT JOIN #__jquarks4s_mat_columns c ON q.id = c.question_id'.
        ' WHERE s.id = '.$this->_id.
        '   AND q.type_id = 4'.
        ' )'.
        ' UNION'.
        ' ( SELECT s.id as survey_id, s.title, s.description, s.footer,'.
        '   sec.id as section_id, sec.name as section_name, ss.section_rank, '.
        '   sq.question_rank, q.id  as question_id, q.type_id, q.statement, q.is_compulsory,  '.
        '   null as row_id, null as row_title, '.
        '   null as column_id, null as title_column, '.
        '   p.id as proposition_id,  p.proposition, p.is_text_field'.
        ' FROM #__jquarks4s_surveys s '.
        '   LEFT JOIN #__jquarks4s_surveys_sections ss ON s.id = ss.survey_id'.
        '   LEFT JOIN #__jquarks4s_sections sec ON ss.section_id = sec.id'.
        '   LEFT JOIN #__jquarks4s_sections_questions sq ON sec.id = sq.section_id'.
        '   LEFT JOIN #__jquarks4s_questions q ON sq.question_id = q.id'.
        '   LEFT JOIN #__jquarks4s_propositions p ON q.id = p.question_id'.
        ' WHERE s.id = '.$this->_id.
        '   AND ( q.type_id <> 4 OR ISNULL(q.type_id) )'.
        ' )'.
        ' ORDER BY section_rank, question_rank, proposition_id, row_id, column_id';

        $this->_db->setQuery($query);
        $rows = $this->_db->loadObjectList();
        if ( ! $rows) {
            return $this->_db->getErrorMsg();
        }
        return $rows;
    }


    /**
     *
     * @return object
     */
    public function getSurvey()
    {
        $row = $this->getTable('surveys');
        $row->load($this->_id);

        $this->_data = $row;

        return  $this->_data;
    }

    
    public function setAccess()
    {
        $row = $this->getTable('surveys');
        $row->load($this->_id);
        
        if ($row->access_id == 0)
        {
            $row->access_id = 1; // make registered
            if ( ! $this->unassignAllUsers($this->_id)) {
                    return false;
                }
        }
        elseif ($row->access_id == 1)
        {
            $row->access_id = 0; // make public
            if ( ! $this->assignAllUsers($this->_id)) {
                    return false;
            }
        }

        if ( ! $row->store()) {
            return false;
        }

        return true;
    }


    public function setPublished()
    {
        $row = $this->getTable('surveys');
        $row->load($this->_id);

        if ($row->published == 0) {
            $row->published = 1;
        }
        elseif ($row->published == 1) {
            $row->published = 0;
        }

        if ( ! $row->store()) {
            return false;
        }

        return true;
    }

    
   public function getQuestions()
   {
       $query =  ' ( SELECT ss.section_rank, '.
           ' sq.question_rank, q.id  as question_id, q.type_id, q.statement,'.
           ' r.id as row_id, r.title as row_title, '.
           ' null as proposition_id, null as proposition'.
         ' FROM #__jquarks4s_surveys s '.
           ' LEFT JOIN #__jquarks4s_surveys_sections ss ON s.id = ss.survey_id'.
           ' LEFT JOIN #__jquarks4s_sections sec ON ss.section_id = sec.id'.
           ' LEFT JOIN #__jquarks4s_sections_questions sq ON sec.id = sq.section_id'.
           ' LEFT JOIN #__jquarks4s_questions q ON sq.question_id = q.id'.
           ' LEFT JOIN #__jquarks4s_mat_rows r ON q.id = r.question_id'.
         ' WHERE s.id = '.$this->_id.
           ' AND q.type_id = 4'.
         ' )'.
         ' UNION'.
         ' ( SELECT ss.section_rank, '.
           ' sq.question_rank, q.id  as question_id, q.type_id, q.statement,'.
           ' null as row_id, null as row_title, '.
           ' p.id as proposition_id,  p.proposition'.
         ' FROM #__jquarks4s_surveys s '.
           ' LEFT JOIN #__jquarks4s_surveys_sections ss ON s.id = ss.survey_id'.
           ' LEFT JOIN #__jquarks4s_sections sec ON ss.section_id = sec.id'.
           ' LEFT JOIN #__jquarks4s_sections_questions sq ON sec.id = sq.section_id'.
           ' LEFT JOIN #__jquarks4s_questions q ON sq.question_id = q.id'.
           ' LEFT JOIN #__jquarks4s_propositions p ON q.id = p.question_id'.
         ' WHERE s.id = '.$this->_id.
           ' AND q.type_id <> 4 '.
         ' )'.
         ' ORDER BY section_rank, question_rank, proposition_id, row_id';
       
       return $this->_getList($query);
   }


   public function getQuestionsOfSurvey($survey_id)
    {
        $query = 'SELECT q.id, q.alias'.
        ' FROM #__jquarks4s_surveys s '.
        '   LEFT JOIN #__jquarks4s_surveys_sections ss ON s.id = ss.survey_id'.
        '   LEFT JOIN #__jquarks4s_sections sec ON ss.section_id = sec.id'.
        '   LEFT JOIN #__jquarks4s_sections_questions sq ON sec.id = sq.section_id'.
        '   LEFT JOIN #__jquarks4s_questions q ON sq.question_id = q.id'.
        ' WHERE s.id = '.$survey_id;

        return $this->_getList($query);

    }

    
    public function getSessionsOfSurvey($survey_id)
    {
        $query = 'SELECT sess.id, u.name as user_name, sess.ip_address, sess.submit_date'.
        ' FROM #__jquarks4s_surveys srvy'.
        '   JOIN #__jquarks4s_users_surveys us ON srvy.id = us.survey_id'.
        '   JOIN #__jquarks4s_sessions sess ON sess.affected_id = us.id'.
        '   LEFT JOIN #__users u ON us.user_id = u.id'.
        ' WHERE srvy.id = '.$survey_id;

        return $this->_getList($query);
    }


    public function getPopulation($survey_id)
    {
        $query = ' SELECT COUNT(ses.id) AS population'.
                 ' FROM #__jquarks4s_surveys s1'.
                 '   LEFT JOIN #__jquarks4s_users_surveys us ON s1.id = us.survey_id'.
                 '   LEFT JOIN #__jquarks4s_sessions ses ON ses.affected_id = us.id'.
                 ' WHERE s1.id = '.(int)$survey_id;

       $this->_db->setQuery($query);
       return $this->_db->loadResult();
    }

    public function authorize($user_ids, $survey_id)
    {
        $values = implode(', ', $user_ids);
        $query = 'UPDATE #__jquarks4s_users_surveys'.
        ' SET is_active = 1'.
        ' WHERE survey_id = '.(int)$survey_id.
        ' AND user_id IN ('.$values.')';

        $this->_db->setQuery($query);

        if ( ! $this->_db->query()) {
            return false;
        }

        return true;
    }

    public function unauthorize($user_ids, $survey_id)
    {
        $values = implode(', ', $user_ids);
        $query = 'UPDATE #__jquarks4s_users_surveys'.
        ' SET is_active = 0'.
        ' WHERE survey_id = '.(int)$survey_id.
        ' AND user_id IN ('.$values.')';

        $this->_db->setQuery($query);

        if ( ! $this->_db->query()) {
            return false;
        }

        return true;
    }
}
