<?php
/**
 * JQuarks4s Component ss_showset Model
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

class JQuarks4sModelSs_showset extends JModel
{

    public function __construct()
    {
        parent::__construct();
        
    }


    public function setSections()
    {
        $cids = JRequest::getVar( 'cid', array(0), 'post', 'array' );
        $survey_id = JRequest::getInt('id');

        $row =& $this->getTable('surveys_sections');

        foreach($cids as $cid)
        {
            $row->id = 0;
            $row->survey_id    = $survey_id;
            $row->section_id   = (int)$cid;
            $row->section_rank = 0;

            if ( ! $row->store())
            {
                $this->setError( $row->getErrorMsg() );
                return false;
            }
        }
        return true;
    }


    public function unsetSections()
    {
        $cids = JRequest::getVar( 'cid', array(0), 'post', 'array' );
        $survey_id = JRequest::getInt('id');

        foreach($cids as $cid)
        {
            $query = 'DELETE FROM #__jquarks4s_surveys_sections'.
            ' WHERE section_id = '.$cid.' AND survey_id = '.$survey_id;

            $this->_db->setQuery($query);
            if ( ! $this->_db->query()) {
                return false;
            }
        }

        return true;
    }


    public function getSectionsForSet($survey_id)
    {
        $query = 'SELECT s.id, s.name'.
        ' FROM #__jquarks4s_sections s'.
        ' WHERE s.id NOT IN ( SELECT ss.section_id'.
        '                     FROM #__jquarks4s_surveys_sections ss'.
        '                     WHERE ss.survey_id = ' . $survey_id . ' )';

        return $this->_getList($query);
    }


    public function getSectionsForUnset($survey_id)
    {
        $query = 'SELECT s.id, s.name'.
        ' FROM #__jquarks4s_sections s'.
        ' WHERE s.id IN ( SELECT ss.section_id'.
        '                 FROM #__jquarks4s_surveys_sections ss'.
        '                 WHERE ss.survey_id = ' . $survey_id . ' )';

        return $this->_getList($query);
    }


}
