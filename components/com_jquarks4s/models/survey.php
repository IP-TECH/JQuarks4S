<?php
/**
 * JQuarks4s Component Survey Model
 *
 * @version     $Id$
 * @author      IP-Tech Labs <labs@iptech-offshore.com>
 * @copyright   2010 IP-Tech
 * @package     JQuarks4s-Front-End
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

    public function __construct()
    {
        parent::__construct();
    }


    public function getUniqueSession($survey_id)
    {
        $survey = $this->getTable('surveys');
        $survey->load((int)$survey_id);
        return $survey->unique_session;
    }

    public function unauthorize($user_id, $survey_id)
    {
        $query = 'UPDATE #__jquarks4s_users_surveys'.
        ' SET is_active = 0'.
        ' WHERE survey_id = '.(int)$survey_id.
        ' AND user_id = '.(int)$user_id;

        $this->_db->setQuery($query);

        if ( ! $this->_db->query()) {
            return false;
        }
        return true;
    }

    public function getRedirectUrl($survey_id)
    {
        $survey = $this->getTable('surveys');
        $survey->load((int)$survey_id);
        return $survey->redirect_url;
    }

    public function getAccessId($survey_id)
    {
        $survey = $this->getTable('surveys');
        $survey->load((int)$survey_id);
        return $survey->access_id;
    }

    public function hasAccess($user_id, $survey_id)
    {
        $query = 'SELECT is_active'.
        ' FROM #__jquarks4s_users_surveys'.
        ' WHERE survey_id = '.$survey_id.
        ' AND user_id = '.$user_id;
        
        $this->_db->setQuery($query);
        return $this->_db->loadResult();
    }
}
