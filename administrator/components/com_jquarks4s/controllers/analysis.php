<?php
/**
 * JQuarks4s Component Analysis Controller
 *
 * @version     $Id$
 * @author      IP-Tech Labs <labs@iptech-offshore.com>
 * @copyright   2010 IP-Tech
 * @package     JQuarks4s-Back-End
 * @subpackage  Controllers
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

jimport('joomla.application.component.controller');

class JQuarks4sControllerAnalysis extends JQuarks4sController
{
    /**
     *
     * @var JModel
     */
    private $_model;

  
    function __construct()
    {
        parent::__construct();

        $this->_model = $this->getModel('analysis') ;
    }

    function display()
    {
        JRequest::setVar('view', 'analysis');
        JRequest::setVar('layout', 'default');

        parent::display() ;
    }


    /**
     * set choose a survey view
     */
    function makeAnalysis()
    {
        $view = & $this->getView( 'choosesurvey', 'html' );
        $view->setModel( $this->getModel( 'surveys' ), true );

        JRequest::setVar('view', 'choosesurvey');
        JRequest::setVar('layout', 'default');


        parent::display() ;
    }


    /**
     * set choose questions view
     */
    function selectQuestions()
    {
        $cids = JRequest::getVar( 'cid', array(0) ) ;
        $survey_id = $cids[0];

        

        $view = & $this->getView( 'choosequestions', 'html' );
        $view->setModel( $this->getModel( 'analysis' ), true );
        //$view->setModel( $questionModel );
        //$view->setLayout('default');
        
        JRequest::setVar('id', $survey_id);
        JRequest::setVar('view', 'choosequestions');
        JRequest::setVar('layout', 'default');

        $view->display() ;
    }


    function frequencyAnalysis()
    {
        $survey_id = JRequest::getInt('survey_id');
        $cids = JRequest::getVar( 'cid', array(0) ) ;

        if (count($cids) != 1)
        {
            $url = 'index.php?option=com_jquarks4s&controller=analysis&task=selectQuestions&cid[]='.$survey_id;
            $msg = JText::_('FREQUENCY_WARNING_ONE_SINGLE_QUESTION_MUST_BE_CHECKED');
            $type = 'notice';

            $this->setRedirect($url, $msg, $type);
        }

        $tab = explode('-', $cids[0]);
        $question_id = (int)$tab[0];
        $row_id      = (int)$tab[1];
        // row_id is the row ID if it is a matrix question (which line of the matrix is the question)
        // else 0
        
        $view = & $this->getView( 'frequencyanalysis', 'html' );
        $view->setModel( $this->getModel( 'analysis' ), true );

        JRequest::setVar('sid', $survey_id);
        JRequest::setVar('qid', $question_id);
        JRequest::setVar('rid', $row_id);
        JRequest::setVar('view', 'frequencyanalysis');
        JRequest::setVar('layout', 'default');

        parent::display() ;
    }

    
    function crossTabAnalysis()
    {
        $survey_id = JRequest::getInt('survey_id');
        $cids = JRequest::getVar( 'cid', array(0) ) ;

        if (count($cids) != 2)
        {
            $url = 'index.php?option=com_jquarks4s&controller=analysis&task=selectQuestions&cid[]='.$survey_id;
            $msg = JText::_('CROSS_TAB_WARNING_TWO_QUESTIONS_MUST_BE_CHECKED');
            $type = 'notice';
            
            $this->setRedirect($url, $msg, $type);
        }
        
        $tab1 = explode('-', $cids[0]);
        $question_id1 = $tab1[0];
        $row_id1      = $tab1[1];
        
        $tab2 = explode('-', $cids[1]);
        $question_id2 = $tab2[0];
        $row_id2      = $tab2[1];

        $view = & $this->getView( 'crosstabanalysis', 'html' );
        $view->setModel( $this->getModel( 'analysis' ), true );

        JRequest::setVar('sid', $survey_id);
        JRequest::setVar('qid1', $question_id1);
        JRequest::setVar('rid1', $row_id1);
        JRequest::setVar('qid2', $question_id2);
        JRequest::setVar('rid2', $row_id2);
        JRequest::setVar('view', 'crosstabanalysis');
        JRequest::setVar('layout', 'default');

        parent::display() ;
    }


    /**
     * set snapshot details view
     */
    function snapshotDetails()
    {
        JRequest::setVar('view', 'snapshotdetails');
        JRequest::setVar('tmpl', 'component');
        JRequest::setVar('layout', 'default');

        parent::display() ;
    }

    
    /**
     * save snapshot analysis
     */
    function snapshot()
    {
        $data = JRequest::get('post');
        $name        = $data['name'];
        $description = $data['description'];

        $currentSession = &JFactory::getSession();
        $analysis = $currentSession->get('analysis', 'none');
        $analysis_type = $analysis['analysis_type'];

        switch ($analysis_type)
        {
            case 1: // snapshot for frequency analysis
                if ( ! $this->_model->storeFrequencySnapshot($name, $description, $analysis)) {
                    
                }
                break;

            case 2: // TODO - snapshot for frequency analysis
                
                break;
        }
        
        $this->setRedirect('index.php?option=com_jquarks4s&view=nothing&tmpl=component&redir_type=1');
    }

    
    /**
     * remove snapshot analysis
     */
    function remove()
    {
        if ( ! $this->_model->delete())
        {
            $msg = JText::_( 'ERROR_ONE_OR_MORE_RECORDS_COULD_NOT_BE_DELETED' );
            $type = 'error';
        }
        else
        {
            $msg = JText::_( 'RECORDS(S)_DELETED' );
            $type = 'message';
        }

        $url = 'index.php?option=com_jquarks4s&controller=analysis';
        
        $this->setRedirect($url, $msg, $type);
    }


    /**
     * view snapshot analysis
     */
    function viewAnalysis()
    {
        $view = & $this->getView( 'snapshotanalysis', 'html' );
        $view->setModel( $this->getModel( 'analysis' ), true );

        JRequest::setVar('view', 'snapshotanalysis');
        parent::display() ;
    }

    /* TODO  */
    //function customAnalysis() {}

}
