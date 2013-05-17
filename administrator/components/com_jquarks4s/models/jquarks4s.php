<?php
/**
 * JQuarks4s Component jquarks4s Model
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

class JQuarks4sModeljquarks4s extends JModel
{
    
    public function __construct()
    {
        parent::__construct();
    }

    public function getLastSurveys()
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
        ' GROUP BY s.id, s.title, population'.
        ' ORDER BY s.id DESC'.
        ' LIMIT 5';

        return $this->_getList($query);
    }


    public function getLastAnalysis()
    {
        $query = 'SELECT an.id, an.name, an.description, an.save_date, t.title AS type'.
        ' FROM #__jquarks4s_snapshot_analysis an'.
        ' JOIN #__jquarks4s_analysis_types t ON an.analysis_type_id = t.id'.
        ' ORDER BY an.id DESC'.
        ' LIMIT 5';
        return $this->_getList($query);
    }

    
    public function getLastSessions()
    {
        $query = 'SELECT s.id, u.name as user_name, s.ip_address, s.submit_date, us.survey_id'.
        ' FROM #__jquarks4s_sessions s'.
        ' LEFT JOIN #__jquarks4s_users_surveys us ON s.affected_id = us.id'.
        ' LEFT JOIN #__users u ON u.id = us.user_id'.
        ' ORDER BY s.submit_date DESC'.
        ' LIMIT 5';

        return $this->_getList($query);
    }




}
