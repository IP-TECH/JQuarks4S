<?php
/**
 * JQuarks4s Component survey Model
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

defined('_JEXEC') or die('');

jimport('joomla.application.component.model');


class JQuarks4sModelJQuarks4s extends JModel
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * check whether user has access to survey
     * @param  int $survey_id
     * @param  int $user_id
     * @return int
     */
    public function isAllowedUser($survey_id, $user_id)
    {
        $query = 'SELECT is_active'.
        ' FROM #__jquarks4s_users_surveys'.
        ' WHERE user_id = '.(int)$user_id.
        ' AND survey_id = '.(int)$survey_id;

        $this->_db->setQuery($query);
        return (boolean)$this->_db->loadResult();
    }

    
    public function getSurvey($survey_id)
    {
        $config =& JFactory::getConfig();
        $jnow   =& JFactory::getDate();
        $jnow->setOffset( $config->getValue('config.offset' ));
        $now = $jnow->toMySQL(true);

        $query = ' ( SELECT s.id as survey_id, s.title, s.description, s.footer,'.
        '   sec.id as section_id, sec.name as section_name, ss.section_rank, '.
        '   sq.question_rank, q.id  as question_id, q.type_id, q.statement, q.is_compulsory,'.
        '   r.id as row_id, r.title as row_title, '.
        '   null as column_id, null as column_title, '.
        '   null as proposition_id, null as proposition, null as is_text_field'.
        ' FROM #__jquarks4s_surveys s '.
        '   LEFT JOIN #__jquarks4s_surveys_sections ss ON s.id = ss.survey_id'.
        '   LEFT JOIN #__jquarks4s_sections sec ON ss.section_id = sec.id'.
        '   LEFT JOIN #__jquarks4s_sections_questions sq ON sec.id = sq.section_id'.
        '   LEFT JOIN #__jquarks4s_questions q ON sq.question_id = q.id'.
        '   LEFT JOIN #__jquarks4s_mat_rows r ON q.id = r.question_id'.
        ' WHERE s.id = '.(int)$survey_id.
        '   AND s.published = 1'.
		'   AND s.published_up < "'.$now.'"'.
		'   AND ( s.published_down = "0000-00-00 00:00:00" OR s.published_down > "'.$now.'" )'.
        '   AND q.type_id = 4'.
        ' )'.
        ' UNION'.
        ' ( SELECT s.id as survey_id, s.title, s.description, s.footer,'.
        '   sec.id as section_id, sec.name as section_name, ss.section_rank, '.
        '   sq.question_rank, q.id  as question_id, q.type_id, q.statement, q.is_compulsory, '.
        '   null as row_id, null as row_title, '.
        '   c.id as title_id, c.title as column_title, '.
        '   null as proposition_id, null as proposition, null as is_text_field'.
        ' FROM #__jquarks4s_surveys s '.
        '   LEFT JOIN #__jquarks4s_surveys_sections ss ON s.id = ss.survey_id'.
        '   LEFT JOIN #__jquarks4s_sections sec ON ss.section_id = sec.id'.
        '   LEFT JOIN #__jquarks4s_sections_questions sq ON sec.id = sq.section_id'.
        '   LEFT JOIN #__jquarks4s_questions q ON sq.question_id = q.id'.
        '   LEFT JOIN #__jquarks4s_mat_columns c ON q.id = c.question_id'.
        ' WHERE s.id = '.(int)$survey_id.
        '   AND q.type_id = 4'.
        '   AND s.published = 1'.
		'   AND s.published_up < "'.$now.'"'.
		'   AND ( s.published_down = "0000-00-00 00:00:00" OR s.published_down > "'.$now.'" )'.
        ' )'.
        ' UNION'.
        ' ( SELECT s.id as survey_id, s.title, s.description, s.footer,'.
        '   sec.id as section_id, sec.name as section_name, ss.section_rank, '.
        '   sq.question_rank, q.id  as question_id, q.type_id, q.statement, q.is_compulsory,  '.
        '   null as row_id, null as row_title, '.
        '   null as column_id, null as title_column, '.
        '   p.id as proposition_id,  p.proposition, p.is_text_field'.
        ' FROM #__jquarks4s_surveys s '.
        '   LEFT JOIN #__jquarks4s_surveys_sections ss ON s.id = ss.survey_id'.
        '   LEFT JOIN #__jquarks4s_sections sec ON ss.section_id = sec.id'.
        '   LEFT JOIN #__jquarks4s_sections_questions sq ON sec.id = sq.section_id'.
        '   LEFT JOIN #__jquarks4s_questions q ON sq.question_id = q.id'.
        '   LEFT JOIN #__jquarks4s_propositions p ON q.id = p.question_id'.
        ' WHERE s.id = '.(int)$survey_id.
        '   AND s.published = 1'.
		'   AND s.published_up < "'.$now.'"'.
		'   AND ( s.published_down = "0000-00-00 00:00:00" OR s.published_down > "'.$now.'" )'.
        '   AND ( q.type_id <> 4 OR ISNULL(q.type_id) )'.
        ' )'.
        ' ORDER BY section_rank, question_rank, question_id, proposition_id, row_id, column_id';

        $this->_db->setQuery($query);
        $rows = $this->_db->loadObjectList();
        if ( ! $rows) {
            return $this->_db->getErrorMsg();
        }
        return $rows;
    }

    public function getPublicSurveys()
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

    
    public function getPrivateSurveys($user_id)
    {
        $privateSurveys = null;

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
                '   AND us.user_id = ' . (int)$user_id .
                '   AND s.published_up < "' . $now . '"' .
                '   AND ( s.published_down > "' . $now . '" OR s.published_down = "0000-00-00 00:00:00" )';

        $db = & JFactory::getDBO();
        $db->setQuery($query);

        $privateSurveys = $db->loadObjectList();

        if ($db->getErrorNum()) {
            return false;
        }
        return $privateSurveys;
    }
}
