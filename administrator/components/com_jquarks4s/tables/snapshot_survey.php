<?php
/**
 * JQuarks4s Component snapshot_survey Table
 *
 * @version     $Id$
 * @author      IP-Tech Labs <labs@iptech-offshore.com>
 * @copyright   2010 IP-Tech
 * @package     JQuarks4s-Back-End
 * @subpackage  Tables
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


class TableSnapshot_survey extends JTable
{
    /**
	 *
	 * @var int
	 */
    var $id = null;

    /**
	 *
	 * @var int
	 */
    var $survey_id = null;

    /**
	 *
	 * @var string
	 */
    var $title = null;

    /**
	 *
	 * @var int
	 */
    var $nbrQuestions = null;

    /**
	 *
	 * @var int
	 */
    var $nbrSessions = null;

    /**
	 *
	 * @var int
	 */
    var $snapshot_analysis_id = null;
    

    function TableSnapshot_survey(& $db)
	{
		parent::__construct('#__jquarks4s_snapshot_survey', 'id', $db) ;
	}
}
