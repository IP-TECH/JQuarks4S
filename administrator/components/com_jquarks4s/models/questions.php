<?php
/**
 * JQuarks4s Component questions Model
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

class JQuarks4sModelQuestions extends JModel
{
    /**
     * @var int
     */
    private $_id;

    /**
     *
     * @var ObjectList
     */
    private $_data;
    
    
    public function __construct()
    {
        parent::__construct();
    }

    
    public function getId()
    {
        return $this->_id;
    }


    /**
     *
     * @return objectList
     */
    public function getQuestions()
    {
        if (empty( $this->_data ))
        {
            $query = 'SELECT q.* , t.title AS type'.
            ' FROM #__jquarks4s_questions q'.
            ' JOIN #__jquarks4s_types t ON q.type_id = t.id'.
            ' ORDER BY q.id';
            
            $this->_data = $this->_getList($query);
        }

        return $this->_data;
    }

    
    /**
     *
     * @param int $question_id
     * @return boolean
     */
    function deletePropositions($question_id)
    {
        $query = 'SELECT type_id'.
        ' FROM #__jquarks4s_questions'.
        ' WHERE id = '.$question_id;

        $this->_db->setQuery($query);
        $type_id = $this->_db->loadResult();

        switch ($type_id)
        {
            case 2: // single choice
            case 3: // multiple choice
                $query = 'DELETE FROM #__jquarks4s_propositions'.
                ' WHERE question_id = '.$question_id;
                $this->_db->setQuery($query);
                if ( ! $this->_db->query()) {
                    return false;
                }
                break;
                
            case 4: // matrix
                $query = 'DELETE FROM #__jquarks4s_mat_rows'.
                ' WHERE question_id = '.$question_id;
                $this->_db->setQuery($query);
                if ( ! $this->_db->query()) {
                    return false;
                }
                $query = 'DELETE FROM #__jquarks4s_mat_columns'.
                ' WHERE question_id = '.$question_id;
                $this->_db->setQuery($query);
                if ( ! $this->_db->query()) {
                    return false;
                }
        }
        return true;
    }


    /**
     * delete questions and their propositions
     * @return boolean
     */
    function delete()
    {
        $cids = JRequest::getVar( 'cid', array(0), 'post', 'array' );
        $row =& $this->getTable();
        
        foreach($cids as $cid) {
            if ( ! $this->deletePropositions($cid)) {
                return false;
            }

            //delete question
            if ( ! $row->delete($cid))
            {
                $this->setError( $row->getErrorMsg() );
                return false;
            }
        }
        
        return true;
    }



    /**
     *
     * @param int $section_id
     * @param int $question_id
     * @param int $question_rank
     * @return boolean
     */
    public function updateRank($section_id, $question_id, $question_rank)
    {
        $query = 'UPDATE #__jquarks4s_sections_questions'.
        ' SET question_rank='.$question_rank.
        ' WHERE section_id  = '.$section_id.
        '   AND question_id = '.$question_id;

        $this->_db->setQuery($query);
        
        if ( ! $this->_db->query()) {
            return false;
        }
        return true;
    }

}
