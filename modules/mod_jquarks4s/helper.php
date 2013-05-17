<?php
/**
 * JQuarks4s Module Helper Class
 *
 * @version     $Id$
 * @author      IP-Tech Labs <labs@iptech-offshore.com>
 * @copyright   2010 IP-Tech
 * @package     JQuarks4s-module
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

defined('_JEXEC') or die('Restricted access');

class ModJQuarks4sHelper
{

    function getPublicSurveys($params)
    {
        $publicSurveys = null;

        $db = & JFactory::getDBO();

        $config = & JFactory::getConfig();
        $jnow = & JFactory::getDate();
        $jnow->setOffset($config->getValue('config.offset'));
        $now = $jnow->toMySQL(true);

        $query = 'SELECT s.id, s.title' .
                ' FROM #__jquarks4s_surveys s' .
                ' WHERE s.published = 1' .
                '   AND s.access_id = 0' .
                '   AND s.published_up < "' . $now . '"' .
                '   AND ( s.published_down = "0000-00-00 00:00:00" OR s.published_down > "' . $now . '" )';

        $db->setQuery($query);

        $publicSurveys = $db->loadObjectList();

        if ($db->getErrorNum()) {
            return false;
        }

        return $publicSurveys;
    }

    function getPrivateSurveys($params)
    {
        $privateSurveys = null;

        $db = & JFactory::getDBO();
        $user = & JFactory::getUser();

        $config = & JFactory::getConfig();
        $jnow = & JFactory::getDate();
        $jnow->setOffset($config->getValue('config.offset'));
        $now = $jnow->toMySQL(true);

        $query = 'SELECT s.id, s.title' .
                ' FROM #__jquarks4s_surveys s' .
                '   LEFT JOIN #__jquarks4s_users_surveys us ' .
                '       ON s.id = us.survey_id' .
                ' WHERE s.published = 1' .
                '   AND s.access_id = 1' .
                '   AND us.is_active = 1'.
                '   AND us.user_id = ' . $user->id .
                '   AND s.published_up < "' . $now . '"' .
                '   AND ( s.published_down > "' . $now . '" OR s.published_down = "0000-00-00 00:00:00" )';


        $db->setQuery($query);

        $privateSurveys = $db->loadObjectList();

        if ($db->getErrorNum()) {
            return false;
        }

        return $privateSurveys;
    }
}
