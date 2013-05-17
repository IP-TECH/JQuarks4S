<?php
/**
 * JQuarks4s Component Analysis Model
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

class JQuarks4sModelAnalysis extends JModel
{
    
    public function __construct()
    {
        parent::__construct();
    }


    /**
     * questions of choosen survey
     * @return array
     */
    public function getQuestions()
    {
        $survey_id = JRequest::getVar('id');

        $query = 'SELECT q.id, q.alias, r.title AS row_title, r.id AS row_id, q.nature, t.title AS type'.
        ' FROM #__jquarks4s_surveys s'.
        ' LEFT JOIN #__jquarks4s_surveys_sections ss ON s.id = ss.survey_id'.
        ' LEFT JOIN #__jquarks4s_sections sec ON sec.id = ss.section_id'.
        ' LEFT JOIN #__jquarks4s_sections_questions sq ON sec.id = sq.section_id'.
        ' LEFT JOIN #__jquarks4s_questions q ON q.id = sq.question_id'.
        ' LEFT JOIN #__jquarks4s_mat_rows r ON r.question_id = q.id'.
        ' JOIN #__jquarks4s_types t ON q.type_id = t.id'.
        ' WHERE s.id = '.$survey_id.
        '   AND q.type_id IN (2, 3, 4)'.
        ' ORDER BY q.id';

        return $this->_getList($query);
    }


    /**
     * sessions of matrix/single choice question
     * @return array
     */
    public function getSessionsForFrequency()
    {
        $survey_id   = JRequest::getInt('sid');
        //$question_id = JRequest::getInt('qid');
        $row_id      = JRequest::getInt('rid');
        
        if ($row_id != 0)
        {
            $query = ' SELECT s.id AS session_id, s.submit_date, col.id AS answer_id, col.title AS answer, null AS altanswer'.
            ' FROM #__jquarks4s_users_surveys us'.
            ' JOIN #__jquarks4s_sessions s ON s.affected_id = us.id'.
            ' LEFT JOIN #__jquarks4s_mat_answers mat_ans ON mat_ans.session_id = s.id'.
            ' LEFT JOIN #__jquarks4s_mat_columns col ON col.id = mat_ans.column_id '.
            ' WHERE us.survey_id = '.$survey_id.
            ' AND mat_ans.row_id = '.$row_id.
            ' ORDER BY s.submit_date';
        }
        else
        {
            $query = 'SELECT s.id AS session_id, s.submit_date, p.id AS answer_id, p.proposition AS answer, ans.altanswer'.
            ' FROM #__jquarks4s_users_surveys us'.
            ' JOIN #__jquarks4s_sessions s ON s.affected_id = us.id'.
            ' LEFT JOIN #__jquarks4s_answers ans ON ans.session_id = s.id'.
            ' LEFT JOIN #__jquarks4s_propositions p ON p.id = ans.proposition_id '.
            ' WHERE us.survey_id = '.$survey_id.
            ' ORDER BY s.submit_date';
        }
        
        return $this->_getList($query);
    }

    /**
     * sessions of matrix/single choice question
     * @param int $questionNbr
     * @return array
     */
    public function getSessionsForCrosstab($questionNbr)
    {
        $survey_id = JRequest::getVar('sid');

        if ($questionNbr == 1)
        {
            //$question_id = JRequest::getVar('qid1');
            $row_id = JRequest::getVar('rid1');
        }
        elseif ($questionNbr == 2)
        {
            //$question_id = JRequest::getVar('qid2');
            $row_id = JRequest::getVar('rid2');
        }
        
        $query = ' (SELECT s.id AS session_id, s.submit_date, p.id AS answer_id, p.proposition AS answer, ans.altanswer'.
        ' FROM #__jquarks4s_users_surveys us'.
        ' JOIN #__jquarks4s_sessions s ON s.affected_id = us.id'.
        ' LEFT JOIN #__jquarks4s_answers ans ON ans.session_id = s.id'.
        ' LEFT JOIN #__jquarks4s_propositions p ON p.id = ans.proposition_id '.
        ' WHERE us.survey_id = '.$survey_id.
        ' )'.
        ' UNION'.
        ' ('.
        ' SELECT s.id AS session_id, s.submit_date, col.id AS answer_id, col.title AS answer, null AS altanswer'.
        ' FROM #__jquarks4s_users_surveys us'.
        ' JOIN #__jquarks4s_sessions s ON s.affected_id = us.id'.
        ' LEFT JOIN #__jquarks4s_mat_answers mat_ans ON mat_ans.session_id = s.id'.
        ' LEFT JOIN #__jquarks4s_mat_columns col ON col.id = mat_ans.column_id '.
        ' WHERE us.survey_id = '.$survey_id.
        ' AND mat_ans.row_id = '.$row_id.
        ' )'.
        ' ORDER BY submit_date';

        return $this->_getList($query);
    }

    
    /**
     * 
     * @param int $survey_id
     * @return object
     */
    public function getSurveyStats($survey_id)
    {
        $query = ' SELECT s.id, s.title, COUNT(sq.id) AS nbr_questions,'.
                ' (SELECT COUNT(ses.id)'.
                    ' FROM #__jquarks4s_surveys s1'.
                    ' LEFT JOIN #__jquarks4s_users_surveys us ON s1.id = us.survey_id'.
                    ' LEFT JOIN #__jquarks4s_sessions ses ON ses.affected_id = us.id'.
                    ' WHERE s1.id = s.id'.
                    ' ) AS population'.
        ' FROM #__jquarks4s_surveys s'.
        ' LEFT JOIN #__jquarks4s_surveys_sections ss ON s.id = ss.survey_id '.
        ' LEFT JOIN #__jquarks4s_sections sec ON sec.id = ss.section_id'.
        ' LEFT JOIN #__jquarks4s_sections_questions sq ON sec.id = sq.section_id '.
        ' WHERE s.id = '.$survey_id.
        ' GROUP BY s.id, s.title, population';

        $this->_db->setQuery($query);
        return $this->_db->loadObject();
    }


    public function getPropositions($question_id)
    {
        $query = '( SELECT p.proposition, p.id AS proposition_id '.
        ' FROM #__jquarks4s_questions q'.
        ' JOIN #__jquarks4s_propositions p ON q.id = p.question_id'.
        ' WHERE q.id = '.$question_id.
        ' )'.
        ' UNION'.
        ' ( SELECT c.title AS proposition, c.id AS proposition_id '.
        ' FROM #__jquarks4s_questions q'.
        ' JOIN #__jquarks4s_mat_columns c ON q.id = c.question_id'.
        ' WHERE q.id = '.$question_id.
        ' )'.
        ' ORDER BY proposition_id';

        return $this->_getList($query);
    }

    /**
     * load question
     * @param int $question_id
     * @return object
     */
    public function getQuestion($question_id)
    {
        $query = 'SELECT q.id, q.statement, t.title AS type_id, q.nature, q.type_id AS type'.
        ' FROM #__jquarks4s_questions q'.
        ' JOIN #__jquarks4s_types t ON t.id = q.type_id'.
        ' WHERE q.id = '.$question_id;

        $this->_db->setQuery($query);
        return $this->_db->loadObject();
    }


    public function getNotAnsweredForCrossTab($questionNbr)
    {
        $survey_id = JRequest::getVar('sid');
        
        $query = ' (SELECT s.id AS session_id, SUM(ans.proposition_id) as n_a_n_a'.
        ' FROM #__jquarks4s_users_surveys us '.
        ' JOIN #__jquarks4s_sessions s ON s.affected_id = us.id '.
        ' JOIN #__jquarks4s_answers ans ON ans.session_id = s.id '.
        ' WHERE us.survey_id = '.$survey_id.
        ' GROUP BY s.id'.
        ' HAVING SUM(ans.proposition_id) = 0'.
        ' )'.
        ' UNION'.
        ' ('.
        ' SELECT s.id AS session_id, SUM(mat_ans.row_id)'.
        ' FROM #__jquarks4s_users_surveys us'.
        ' JOIN #__jquarks4s_sessions s ON s.affected_id = us.id'.
        ' LEFT JOIN #__jquarks4s_mat_answers mat_ans ON mat_ans.session_id = s.id'.
        ' WHERE us.survey_id = '.$survey_id.
        ' GROUP BY s.id'.
        ' HAVING SUM(mat_ans.column_id) = 0'.
        ' )';
        
        return $this->_getList($query);
    }


    public function storeFrequencySnapshot($snapshotName, $snapshotDescription, $sessionData)
    {
        // store analysis
        $snapshot_analysis = &$this->getTable('snapshot_analysis');
        $snapshot_analysis->id               = 0;
        $snapshot_analysis->name             = $snapshotName;
        $snapshot_analysis->description      = $snapshotDescription;
        $snapshot_analysis->analysis_type_id = 1;
        if ( ! $snapshot_analysis->store()) {
            return false;
        }

        // store survey stat
        $snapshot_analysis_id = $snapshot_analysis->_db->insertid(); // get last insert ID

        $snapshot_survey = &$this->getTable('snapshot_survey');
        $snapshot_survey->id                   = 0;
        $snapshot_survey->survey_id            = $sessionData['survey']['id'];
        $snapshot_survey->title                = $sessionData['survey']['title'];
        $snapshot_survey->nbrQuestions         = $sessionData['survey']['nbr_questions'];
        $snapshot_survey->nbrSessions          = $sessionData['survey']['nbr_sessions'];
        $snapshot_survey->snapshot_analysis_id = $snapshot_analysis_id;
        if ( ! $snapshot_survey->store()) {
            return false;
        }

        // store question
        $snapshot_question = &$this->getTable('snapshot_question');
        
        if ($sessionData['question']['type_id'] == 4) // matrix type
        {
            $snapshot_question->row_id    = $sessionData['question']['id'];
            $snapshot_question->row_title = $sessionData['question']['statement'];
        }
        elseif ($sessionData['question']['type_id'] == 2 || $sessionData['question']['type_id'] == 3) // single/multiple choice
        {
            $snapshot_question->question_id = $sessionData['question']['id'];
            $snapshot_question->statement   = $sessionData['question']['statement'];
        }
        $snapshot_question->id          = 0;
        $snapshot_question->type_id     = $sessionData['question']['type_id'];
        $snapshot_question->nature      = $sessionData['question']['nature'];
        $snapshot_question->snapshot_analysis_id = $snapshot_analysis_id;
        $snapshot_question->store();

        $snapshot_question_id = $snapshot_question->_db->insertid();

        // store propositions
        $snapshot_proposition = &$this->getTable('snapshot_proposition');
        foreach ($sessionData['propositions'] as $prop)
        {
            $snapshot_proposition->id                   = 0;
            $snapshot_proposition->proposition_id       = (int)$prop['id'];
            $snapshot_proposition->proposition          = $prop['proposition'];
            $snapshot_proposition->sample_size          = (int)$prop['sample_size'];
            $snapshot_proposition->frequency            = (float)$prop['frequency'];
            $snapshot_proposition->snapshot_question_id = $snapshot_question_id;
            if ( ! $snapshot_proposition->store()) {
                return false;
            }
        }
    return true;
    }


    /**
     * get analysis snapshots
     * @return array
     */
    public function getAnalysis()
    {
        $query = 'SELECT an.id, an.name, an.description, an.save_date, t.title AS type, s.title AS survey_title'.
        ' FROM #__jquarks4s_snapshot_analysis an'.
        ' JOIN #__jquarks4s_analysis_types t ON an.analysis_type_id = t.id'.
        ' JOIN #__jquarks4s_snapshot_survey s ON an.id = s.snapshot_analysis_id';

        return $this->_getList($query);
    }


    function delete()
    {
        $cids = JRequest::getVar( 'cid', array(0), 'post', 'array' );
        
        foreach($cids as $cid)
        {
            $query = 'DELETE  an, s, q, p, v1, v2'.
            ' FROM #__jquarks4s_snapshot_analysis             AS an'.
            ' LEFT JOIN #__jquarks4s_snapshot_survey          AS s  ON an.id = s.snapshot_analysis_id'.
            ' LEFT JOIN #__jquarks4s_snapshot_question        AS q  ON an.id = q.snapshot_analysis_id'.
            ' LEFT JOIN #__jquarks4s_snapshot_proposition     AS p  ON q.id  = p.snapshot_question_id'.
            ' LEFT JOIN #__jquarks4s_snapshot_cross_tab_value AS v1 ON p.id  = v1.snapshot_proposition1_id'.
            ' LEFT JOIN #__jquarks4s_snapshot_cross_tab_value AS v2 ON p.id  = v2.snapshot_proposition2_id'.
            ' WHERE an.id = ' . $cid;

            if ( ! $this->_db->execute($query)) {
                return false;
            }
        }

        return true;
    }


    /**
     *
     * @return object
     */
    public function getSnapshotAnalysis($snapshot_id)
    {
        $row = $this->getTable('snapshot_analysis');
        $row->load($snapshot_id);
        return $row;
    }


    /**
     * get snapshot survey by snapshot analysis ID
     * @param int $snapshot_id
     * @return object
     */
    public function getSnapshotSurvey($snapshot_id)
    {
        $query = 'SELECT  s.*'.
        ' FROM #__jquarks4s_snapshot_analysis AS an'.
        ' LEFT JOIN #__jquarks4s_snapshot_survey AS s  ON an.id = s.snapshot_analysis_id'.
        ' WHERE an.id = '.$snapshot_id;

        $this->_db->setQuery($query);
        return $this->_db->loadObject();
    }


    /**
     * get questions related to snapshot analysis
     * @param int $snapshot_id
     * @return array
     */
    public function getSnapshotQuestions($snapshot_id)
    {
        $query = 'SELECT  q.*'.
        ' FROM #__jquarks4s_snapshot_analysis AS an'.
        ' LEFT JOIN #__jquarks4s_snapshot_question AS q  ON an.id = q.snapshot_analysis_id'.
        ' WHERE an.id = '.$snapshot_id;

        return $this->_getList($query);
    }

    
    public function getSnapshotPropositions($snapshot_question_id)
    {
        $query = 'SELECT  p.*'.
        ' FROM #__jquarks4s_snapshot_question AS q'.
        ' LEFT JOIN #__jquarks4s_snapshot_proposition AS p  ON q.id  = p.snapshot_question_id'.
        ' WHERE p.snapshot_question_id = '.$snapshot_question_id;

        return $this->_getList($query);
    }


    /**
     *
     * @param int $row_id
     * @return string
     */
    public function getRowTitle($row_id)
    {
        $row = $this->getTable('mat_rows');
        $row->load($row_id);
        return $row->title;
    }

}
