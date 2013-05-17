<?php
/**
 * JQuarks4s Component question Model
 *
 * @version     $Id$
 * @author      IP-Tech Labs <labs@iptech-offshore.com>
 * @copyright   2010 IP-Tech
 * @package     BackEnd
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

class JQuarks4sModelQuestion extends JModel
{
    /**
     * @var int
     */
    private $_id;

    /**
     *
     * @var Object
     */
    private $_data;

    
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
     *
     * @return ObjectList
     */
    public function getTypes()
    {
        $query = 'SELECT *'.
        ' FROM #__jquarks4s_types';

        return $this->_getList($query);
    }

    
    public function &getQuestion()
    {
        if (empty( $this->_data ))
        {
            $query = 'SELECT q.id, q.alias, q.statement, q.nature, q.is_compulsory, q.type_id'.
            ' FROM #__jquarks4s_questions q'.
            ' WHERE q.id = ' . $this->_id ;

            $this->_db->setQuery( $query );
            $this->_data = $this->_db->loadObject();
        }
        if ( ! $this->_data )
        {
            $this->_data = new stdClass();
            $this->_data->id = 0;
            $this->_data->alias = '';
            $this->_data->statement = '';
            $this->_data->nature = false;
            $this->_data->is_compulsory = false;
            $this->_data->type_id = 1;
        }

        return $this->_data;
    }

    
    /**
     *
     * @param int $question_id
     * @return array_of_ids
     */
    private function getPropositionsIds($question_id)
    {
        $query = 'SELECT p.id'.
        ' FROM #__jquarks4s_propositions p'.
        ' WHERE p.question_id = ' . $question_id .
        ' ORDER BY p.id';

        $this->_db->setQuery($query);
        return $this->_db->loadResultArray();
    }



    /**
     *
     * @param int $question_id
     * @return ObjectList
     */
    public function getPropositionsStats($question_id)
    {
        $propositions = $this->getPropositionsIds($question_id);
        $inClause = implode(', ', $propositions);

        $query = 'SELECT p.proposition, a.proposition_id, count(a.proposition_id) AS size, '.
                 ' ( count(proposition_id) * 100 / (SELECT count(*)
                                                    FROM #__jquarks4s_answers
                                                    WHERE proposition_id IN ('.$inClause.'))) as percent'.
                 ' FROM #__jquarks4s_answers a'.
                 ' LEFT JOIN #__jquarks4s_propositions p ON a.proposition_id = p.id'.
                 ' WHERE a.proposition_id IN ('.$inClause.')'.
                 ' GROUP BY a.proposition_id';
        $this->_db->setQuery($query);
        $props =  $this->_db->loadObjectList();
        return $props;
    }


    /**
     *
     * @param int $question_id
     * @return array_of_boolean
     */
    private function getPropositionsIsTextField($question_id)
    {
        $query = 'SELECT p.is_text_field'.
        ' FROM #__jquarks4s_propositions p'.
        ' WHERE p.question_id = ' . $question_id .
        ' ORDER BY p.id';

        $this->_db->setQuery($query);
        return $this->_db->loadResultArray();
    }


    /**
     *
     * @return Propositions_Number_mins_1
     */
    public function getPropositionsNbr()
    {
        if ( ! $this->_data->type_id) {
            return false;
        }

        $type = $this->_data->type_id;
        $query = 'SELECT p.id'.
        ' FROM #__jquarks4s_propositions p'.
        ' WHERE p.question_id = ' . $this->_id ;

        $nbr = $this->_getListCount($query);
        return ($nbr - 1);
    }


    public function getPropositions()
    {
        if ( ! $this->_data->type_id) {
            return false;
        }

        $type = $this->_data->type_id;
        $query = 'SELECT p.*'.
        ' FROM #__jquarks4s_propositions p'.
        ' WHERE p.question_id = ' . $this->_id .
        ' ORDER BY p.id';
        
        return $this->_getList($query);
    }

    /**
     *
     * @param string $title
     * @param int $question_id
     * @return int
     */
    private function getRowId($title, $question_id)
    {
        $query = 'SELECT id'.
        ' FROM #__jquarks4s_mat_rows'.
        ' WHERE title = "' . $title . '"' .
        ' AND question_id = ' . $question_id;

        $this->_db->setQuery($query);
        return $this->_db->loadResult();
    }


    public function getRows($question_id)
    {
        if ( ! $this->_data->type_id) {
            return false;
        }
        
        $query = 'SELECT id, title'.
        ' FROM #__jquarks4s_mat_rows'.
        ' WHERE question_id = ' . $question_id .
        ' ORDER BY id';

        return $this->_getList($query);
    }

    
    /**
     *
     * @param string $title
     * @param int $question_id
     * @return int
     */
    private function getColumnId($title, $question_id)
    {
        $query = 'SELECT id'.
        ' FROM #__jquarks4s_mat_columns'.
        ' WHERE title = "' . $title . '"' .
        ' AND question_id = ' . $question_id;

        $this->_db->setQuery($query);
        return $this->_db->loadResult();
    }

    
    public function getColumns($question_id)
    {
        if ( ! $this->_data->type_id) {
            return false;
        }

        $query = 'SELECT id, title'.
        ' FROM #__jquarks4s_mat_columns '.
        ' WHERE question_id = ' . $question_id .
        ' ORDER BY id';

        return $this->_getList($query);
    }


    /**
     * the id of the last stored question (the max id)
     * @return int
     */
    public function getLastQuestionId()
    {
        $query = 'SELECT MAX(id)'.
        ' FROM #__jquarks4s_questions';

        $this->_db->setQuery($query);
        return $this->_db->loadResult();
    }


    public function store()
    {
        // STORE QUESTION RECORD
        $row =& $this->getTable('questions');
        
        $data = JRequest::get('post', JREQUEST_ALLOWHTML);
        if ($data['alias'] == '') {
            $data['alias'] = strip_tags($data['statement']);
        }
        
        
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

        //
        $question_id = (int)$data['id'];
        $type_id     = (int)$data['type_id'];

        //get the id of the new stored question
        if ($question_id == 0) {
            $question_id = $this->getLastQuestionId();
        }
        else
        {
            $row->load($question_id);
            $current_type_id = (int)$row->type_id;
            if ($type_id != $current_type_id) // delete old propositions if question type has changed
            {
                $questionsModel =& JModel::getModel('questions');
                $questionsModel->deletePropositions($current_type_id);
            }
        }
        
        // STORE PROPS OR MATRIX
        switch($type_id)
        {
            case 2: //single choice
            case 3: //multiple choice
                // get propositions from post
                $post_id         = $data['prop_id'];
                $post_prop       = $data['proptext'];
                $post_text_field = $data['is_text_field'];

                // get ids and is_text_field from db
                $db_id = $this->getPropositionsIds($question_id);
                $db_is_text_field = $this->getPropositionsIsTextField($question_id);

                // get JTable to use for insert / delete
                $propTable = $this->getTable('propositions');

                //updating is_text_field column - intersection des db_ids et post_ids
                $istextfieldUpdateArray = array_intersect($post_id, $db_id);
                if (count($istextfieldUpdateArray) != 0)
                {
                    foreach ($istextfieldUpdateArray as $key => $val)
                    {
                        $propTable->load($val);
                        $propTable->proposition = $post_prop[$key];
                        $propTable->is_text_field = ($post_text_field[$key] == 'on') ? TRUE : FALSE ;
                        $propTable->store();
                    }
                }


                // DB - POST = DELETE ** removing propositions from DB
                $propDeleteArray = array_diff($db_id, $post_id);
                if (count($propDeleteArray) != 0)
                {
                    foreach ($propDeleteArray as $propId) {
                        $propTable->delete($propId);
                    }
                }

                // storing new propositions (those with id = 0)
                function isnew($val){ return (0 == (int)$val); }
                $newIds = array_filter($post_id, "isnew");

                foreach ($newIds as $key => $val)
                {
                    $propTable->id = 0;
                    $propTable->proposition = $post_prop[$key];
                    $propTable->is_text_field = ($post_text_field[$key] == 'on') ? TRUE : FALSE ;
                    $propTable->question_id = $question_id;
                    if ( ! $propTable->store())
                    {
                        $this->setError($this->_db->getErrorMsg());
                        return false;
                    }
                }
                break;
                
            case 4: //matrix
                // get lines and columns from post array
                $post_lines   = $data['lines_select'];
                $post_columns = $data['columns_select'];

                // get lines and columns from db
                $this->_data->type_id = 4; // necessary to call getRows() and getColumns()
                
                $lines = $this->getRows($question_id);
                $db_lines = array();
                foreach ($lines as $l) {
                    $db_lines[] = $l->title;
                }

                $columns = $this->getColumns($question_id);
                $db_columns = array();
                foreach ($columns as $c) {
                    $db_columns[] = $c->title;
                }

                // POST - DB = TO INSERT ** inserting new lines and columns
                $arrInsertLines   = array_diff($post_lines, $db_lines);
                if (count($arrInsertLines) != 0)
                {
                    $tableLines = $this->getTable('mat_rows');
                    foreach ($arrInsertLines as $line)
                    {
                        $tableLines->id = 0;
                        $tableLines->title = $line;
                        $tableLines->question_id = $question_id;
                        $tableLines->store();
                    }
                }
                
                $arrInsertColumns = array_diff($post_columns, $db_columns);
                if (count($arrInsertColumns) != 0)
                {
                    $tableColumns = $this->getTable('mat_columns');
                    foreach ($arrInsertColumns as $column)
                    {
                        $tableColumns->id = 0;
                        $tableColumns->title = $column;
                        $tableColumns->question_id = $question_id;
                        $tableColumns->store();
                    }
                }

                // DB - POST = TO DELETE ** deleting removed lines and columns
                $arrDeleteLines = array_diff($db_lines, $post_lines);
                if (count($arrDeleteLines) != 0)
                {
                    $tableLines = $this->getTable('mat_rows');
                    foreach ($arrDeleteLines as $line)
                    {
                        $loid = $this->getRowId($line, $question_id);
                        $tableLines->delete($loid);
                    }
                }

                $arrDeleteColumns = array_diff($db_columns, $post_columns);
                if (count($arrDeleteColumns) != 0)
                {
                    $tableColumns = $this->getTable('mat_columns');
                    foreach ($arrDeleteColumns as $column)
                    {
                        $coid = $this->getColumnId($column, $question_id);
                        $res = $tableColumns->delete($coid);
                    }
                }
                
        }
        return true;
    }


    public function setCompulsory()
    {
        $row = $this->getTable('questions');
        $row->load($this->_id);
        $row->is_compulsory = !$row->is_compulsory;
        if ( ! $row->store()) {
            return false;
        }
        return true;
    }

    public function setNature()
    {
        $row = $this->getTable('questions');
        $row->load($this->_id);
        $row->nature = !$row->nature;
        if ( ! $row->store()) {
            return false;
        }
        return true;
    }

}
