<?php
/**
 * JQuarks4s User Plugin
 *
 * @version	$Id$
 * @author	IP-Tech Labs <labs@iptech-offshore.com>
 * @copyright   2010 IP-Tech
 * @package	JQuarks-Plugin
 * @subpackage	User
 * @link	http://www.iptechinside.com/labs/projects/show/jquarks
 * @since	1.1.0
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

defined('_JEXEC') or die('=;)');

jimport('joomla.plugin.plugin');


class plgUserjquarks4s extends JPlugin {

    /**
     * Constructor
     *
     * For php4 compatability we must not use the __constructor as a constructor for plugins
     * because func_get_args ( void ) returns a copy of all passed arguments NOT references.
     * This causes problems with cross-referencing necessary for the observer design pattern.
     *
     * @param object $subject The object to observe
     * @param 	array  $config  An array that holds the plugin configuration
     * @since 1.5
     */
    function plgUserjquarks4s(& $subject, $config)
    {
        parent::__construct($subject, $config);
    }

    /**
     * assign new user to all public surveys
     *
     * Method is called after user data is stored in the database
     *
     * @param 	array		holds the new user data
     * @param 	boolean		true if a new user is stored
     * @param	boolean		true if user was succesfully stored in the database
     * @param	string		message
     */
    function onAfterStoreUser($user, $isnew, $success, $msg)
    {
        if ($isnew && $success)
        {
            $query = 'SELECT id' .
            ' FROM #__jquarks4s_surveys';
            $db = &JFactory::getDBO();
            $db->setQuery($query);
            $publicSurveys = $db->loadResultArray();

            JTable::addIncludePath(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_jquarks4s'.DS.'tables');
            $row =& JTable::getInstance('users_surveys', 'Table');
            foreach ($publicSurveys as $survey)
            {
                $row->id        = 0;
                $row->survey_id = $survey['id'];
                $row->user_id   = $user['id'];
                $row->is_active = false;
                if ( ! $row->store()) {
                    JError::raiseError(500, $row->getError() );
                }
            }
        }
        return true;
    }
}
