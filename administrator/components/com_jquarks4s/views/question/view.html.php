<?php
/**
 * JQuarks4s Component question View
 *
 * @version     $Id$
 * @author      IP-Tech Labs <labs@iptech-offshore.com>
 * @copyright   2010 IP-Tech
 * @package     Expression package is undefined on line 8, column 19 in Templates/Scripting/New Folder/PHPClass_1_1_1.
 * @subpackage  Views
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

jimport('joomla.application.component.view');

class JQuarks4sViewQuestion extends JView
{
    function display($tpl = null)
    {
        $model =& $this->getModel('question');
        
        $question     =& $model->getQuestion();
        $types        =& $model->getTypes();

        $this->assignRef('question', $question);
        $this->assignRef('types', $types);
        
        if ($question->type_id == 2 || $question->type_id == 3)
        {
            $propositions =& $model->getPropositions();
            $propositionsNbr =& $model->getPropositionsNbr();
            $this->assignRef('propositions', $propositions);
            $this->assignRef('propositionsNbr', $propositionsNbr);
        }
        elseif ($question->type_id == 4)
        {
            $rows    = $model->getRows($question->id);
            $columns = $model->getColumns($question->id);

            $this->assignRef('rows',    $rows);
            $this->assignRef('columns', $columns);

        }
        
        
        $isNew = ($question->id < 1);
		$text = $isNew ? JText::_( 'NEW' ) : JText::_( 'EDIT' );
  		JToolBarHelper::title(   JText::_( 'QUESTION' ).': <small><small>[ ' . $text .' ]</small></small>' );
		
        
        $bar=& JToolBar::getInstance( 'toolbar' );
        // appendButton method parameters
        // 1- button type from JButton
        // 2- css class - image of the button
        // 3- text to display on the button
        // 4- the task to set
        // 5- whether a selection must be made from an admin list before continuing.
        
        if ($question->id == 0) {
            $bar->appendButton( 'link', 'publish', JText::_('PLACE_IN_SECTION'), 'index.php?option=com_jquarks4s&amp;controller=qs&amp;task=showPlace&amp;id='.$question->id, false );
        }
        else {
            $bar->appendButton( 'Popup', 'publish', JText::_('PLACE_IN_SECTION'), 'index.php?option=com_jquarks4s&amp;controller=qs&amp;task=showPlace&amp;id='.$question->id, 550, 400 );
        }
        
        $bar->appendButton( 'Popup', 'unpublish', JText::_('UNPLACE_FROM_SECTION'), 'index.php?option=com_jquarks4s&amp;controller=qs&amp;task=showUnplace&amp;id='.$question->id, 550, 400 );
        $bar->appendButton( 'Popup', 'preview', 'preview', 'index.php?option=com_jquarks4s&amp;controller=questions&amp;tmpl=component&amp;task=preview', 550, 400 );
        
        
        JToolBarHelper::save() ;
		JToolBarHelper::custom( 'saveContinue', 'save.png', 'save.png', JText::_('SAVECONTINUE'), false, false);
        JToolBarHelper::apply() ;

        if ($isNew) {
                JToolBarHelper::cancel();
        } else {
                JToolBarHelper::cancel( 'cancel', 'Close' ) ;
        }
        
        // get the user preferred editor, otherwise from global configuration
        $user = JFactory::getUser();
        $editorName = $user->getParam('editor');
        if (is_null($editorName)) {
            $editor =& JFactory::getEditor();
        } else {
            $editor =& JFactory::getEditor($editorName);
        }

        switch ($editor->_name)
        {
            case 'tinymce':
                $params = array( 'smilies'=> '0' ,
                'style'  => '1' ,
                'layer'  => '0' ,
                'table'  => '0' ,
                'clear_entities'=>'0',
                'relative_urls'=>'0',
                'extended_elements' => "pre[name|class]",
                );
                break;

            default:
                $params = array();
        }
        $this->assignRef('editor', $editor) ;
        $this->assignRef('editor_params', $params) ;

        parent::display($tpl);
    }

}