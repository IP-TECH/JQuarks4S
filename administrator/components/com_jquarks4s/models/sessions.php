<?php
/**
 * JQuarks4s Component sessions Model
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

class JQuarks4sModelSessions extends JModel
{
    
    public function __construct()
    {
        parent::__construct();
    }

    
    /**
     *
     * @return arrayObject
     */
    public function getSessions()
    {
        $query = 'SELECT s.id, u.name as user_name, s.ip_address, s.submit_date, us.survey_id'.
        ' FROM #__jquarks4s_sessions s'.
        '   LEFT JOIN #__jquarks4s_users_surveys us ON s.affected_id = us.id'.
        '   LEFT JOIN #__users u ON u.id = us.user_id'.
        ' ORDER BY s.submit_date';

        return $this->_getList($query);
    }


    public function delete()
    {
        $cids = JRequest::getVar( 'cid', array(0), 'post', 'array' );
        
        foreach($cids as $cid) {
            $query = 'DELETE s, ans, text_ans, mat_ans '.
            ' FROM #__jquarks4s_sessions AS s'.
            '   LEFT JOIN #__jquarks4s_answers AS ans ON s.id = ans.session_id '.
            '   LEFT JOIN #__jquarks4s_text_answers AS text_ans ON s.id = text_ans.session_id'.
            '   LEFT JOIN #__jquarks4s_mat_answers AS mat_ans ON mat_ans.session_id = s.id'.
            ' WHERE s.id = ' . $cid;
            
            $this->_db->setQuery($query);
            if ( ! $this->_db->query()) {
                return false;
            }
        }
        return true;
    }

}
