<?php
/**
 * JQuarks4s Component sections Model
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

class JQuarks4sModelSections extends JModel
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

    
    /**
     * get all sections
     * @return object_list
     */
    public function getSections()
    {
        if (empty( $this->_data ))
        {
            $query = 'SELECT s.id, s.name'.
            ' FROM #__jquarks4s_sections s';

            $this->_data = $this->_getList($query);
        }

            return $this->_data;
    }


    /**
     * return sections with their propositions for the sections view
     * @return
     */
    public function getSectionsWithQuestions()
    {
        if (empty( $this->_data ))
        {
            $query = 'SELECT s.id AS section_id, s.name AS section,'.
            '   sq.question_id AS question_id, q.statement AS question, sq.question_rank,'.
            '   (   SELECT COUNT(jsq.question_id)'.
            '       FROM #__jquarks4s_sections_questions jsq' .
            '       WHERE jsq.section_id = s.id) AS rowspan'.
            ' FROM #__jquarks4s_sections s'.
            '   LEFT JOIN #__jquarks4s_sections_questions sq ON s.id = sq.section_id'.
            '   LEFT JOIN #__jquarks4s_questions q ON q.id = sq.question_id'.
            ' ORDER BY s.id, sq.question_rank';

            $this->_data = $this->_getList($query);
        }

        return $this->_data;
    }

    
    private function deleteAssociatedSurveys($section_id)
    {
        $query = 'DELETE FROM #__jquarks4s_surveys_sections'.
        ' WHERE section_id = '. $section_id;

        $this->_db->setQuery($query);

        $this->_db->query();
    }


    /**
     * delete table sections_questions rows for a given section
     * @param int $section_id
     */
    private function deleteAssociatedQuestions($section_id)
    {
        $query = 'DELETE FROM #__jquarks4s_sections_questions'.
        ' WHERE section_id = '. $section_id;

        $this->_db->setQuery($query);

        $this->_db->query();
    }


    public function delete()
    {
        $cids = JRequest::getVar( 'cid', array(0), 'post', 'array' );
        $row =& $this->getTable('sections');

        foreach($cids as $cid) {
            $this->deleteAssociatedQuestions($cid);
            $this->deleteAssociatedSurveys($cid);

            //delete section
            if ( ! $row->delete( $cid ))
            {
                $this->setError( $row->getErrorMsg() );
                return false;
            }
        }

        return true;
    }


    public function updateRank($survey_id, $section_id, $section_rank)
    {
        $query = 'UPDATE #__jquarks4s_surveys_sections'.
        ' SET section_rank='.$section_rank.
        ' WHERE survey_id  = '.$survey_id.
        '   AND section_id = '.$section_id;

        $this->_db->setQuery($query);

        if ( ! $this->_db->query()) {
            return false;
        }
        return true;
    }
    


}
