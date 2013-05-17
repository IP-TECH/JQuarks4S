<?php
/**
 * JQuarks4s Component Surveys Model
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

class JQuarks4sModelSurveys extends JModel
{
    /**
     * @var int
     */
    private $_id;

    
    public function __construct()
    {
        parent::__construct();
    }

    
    public function getId()
    {
        return $this->_id;
    }


    /**
     * surveys with their sections ordered
     * @return
     */
    public function getSurveysWithSections()
    {
        if (empty( $this->_data ))
        {
            $query = 'SELECT srvy.id AS survey_id, srvy.title AS survey,'.
                '   srvy.published, srvy.access_id,'.
                '   ss.section_id AS section_id, s.name AS section, ss.section_rank,'.
                '   (   SELECT COUNT(jss.section_id)'.
                '       FROM #__jquarks4s_surveys_sections jss' .
                '       WHERE jss.survey_id = srvy.id) AS rowspan, '.
                '    (SELECT COUNT(ses.id)'.
                '     FROM #__jquarks4s_surveys s1'.
                '     LEFT JOIN #__jquarks4s_users_surveys us ON s1.id = us.survey_id'.
                '     LEFT JOIN #__jquarks4s_sessions ses ON ses.affected_id = us.id'.
                '     WHERE s1.id = srvy.id'.
                '     ) AS population'.
                ' FROM #__jquarks4s_surveys srvy'.
                '   LEFT JOIN #__jquarks4s_surveys_sections ss ON srvy.id = ss.survey_id'.
                '   LEFT JOIN #__jquarks4s_sections s ON s.id = ss.section_id'.
                ' ORDER BY srvy.id, ss.section_rank';

            $this->_data = $this->_getList($query);
        }

        return $this->_data;
    }

    

    public function delete()
    {
        $cids = JRequest::getVar( 'cid', array(0), 'post', 'array' );
        $row =& $this->getTable('surveys');

        foreach($cids as $cid)
        {
            $this->deleteAssociatedSections($cid);

            //delete survey
            if ( ! $row->delete( $cid ))
            {
                $this->setError( $row->getErrorMsg() );
                return false;
            }
        }

        return true;
    }


    /**
     * delete table surveys_sections rows for a given survey
     * @param int survey_id
     */
    private function deleteAssociatedSections($survey_id)
    {
        $query = 'DELETE FROM #__jquarks4s_surveys_sections'.
        ' WHERE survey_id = '. $survey_id;

        $this->_db->setQuery($query);

        $this->_db->query();
    }

    
    public function getSurveys()
    {
        $query = 'SELECT s.id, s.title'.
        ' FROM #__jquarks4s_surveys s';

        return $this->_getList($query);
    }

    
    public function getSurveysForAnalysis()
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
        ' GROUP BY s.id, s.title'.
        ' HAVING population >0'.
        ' AND nbr_questions > 0'.
        ' ORDER BY s.id DESC';

        return $this->_getList($query);
    }

}
