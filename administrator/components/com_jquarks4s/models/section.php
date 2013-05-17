<?php
/**
 * JQuarks4s Component section Model
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

class JQuarks4sModelSection extends JModel
{
    /**
     * @var int
     */
    private $_id;

    /**
     *
     * @var Object
     */
    private $_data;


    public function __construct()
    {
        parent::__construct();

        $cids = JRequest::getVar( 'cid', array(0) ) ;
        $this->_id = (int)$cids[0];
    }

    
    public function getId()
    {
        return $this->_id;
    }


    /**
     *
     * @return object
     */
    public function getSection()
    {
        $row = $this->getTable('sections');
        $row->load($this->_id);

        $this->_data = $row;
        
        return  $this->_data;
    }


    /**
     * ID oh the last saved section
     * @return int
     */
    public function getLastSectionId()
    {
        $query = 'SELECT MAX(id)'.
        ' FROM #__jquarks4s_sections';

        $this->_db->setQuery($query);
        return $this->_db->loadResult();
    }


    public function store()
    {
        $row =& $this->getTable('sections');

        $data = JRequest::get('post');

        if ( ! $row->bind($data))
        {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }

        // Make sure the record is valid
        if ( ! $row->check())
        {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }

        // Store the web link table to the database
        if ( ! $row->store())
        {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }

        return true;

    }

}
