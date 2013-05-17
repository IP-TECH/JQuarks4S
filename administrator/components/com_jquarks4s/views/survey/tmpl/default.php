<?php
defined('_JEXEC') or die('Restricted access');

jimport('joomla.html.pane');
JHTML::_('behavior.tooltip');
?>

<form action="index.php" method="post" name="adminForm" id="adminForm">
    <table width="100%" border="0">
        <tr>
            <td valign="top">
                <table class="adminform">
                    <tr>
                        <td width="10%"><?php echo JText::_('TITLE'); ?></td>
                        <td width="50%"><input size="70" type="text" name="title" value="<?php echo $this->survey->title; ?>" /></td>
                        <td width="10%"><?php echo JText::_('published'); ?></td>
                        <td width="20%">
                            <input type="radio" name="published" value="0"
                                   <?php echo ($this->survey->published) ? '' : 'checked'; ?> /><?php echo JText::_('NO'); ?>
                            <input type="radio" name="published" value="1"
                                   <?php echo ($this->survey->published) ? 'checked' : ''; ?> /><?php echo JText::_('YES'); ?>
                        </td>
                    </tr>
                </table>

                <fieldset>
                    <legend><?php echo JText::_('HEADER'); ?></legend>
                    <?php
                        echo $this->editor->display('description', $this->survey->description ,
                                '670', '200', '20', '20',
                                array('image', 'pagebreak', 'readmore'), $this->editor_params);
                    ?>
                               </fieldset>
                               <fieldset>
                                   <legend><?php echo JText::_('FOOTER'); ?></legend>
                    <?php
                            echo $this->editor->display('footer', $this->survey->footer,
                                    '670', '200', '20', '20',
                                    array('image', 'pagebreak', 'readmore'), $this->editor_params);

                    ?>

                               </fieldset>

                           </td>
                           <td valign="top" width="45%">
<?php
                                   $selectedPublic = ($this->survey->access_id == 0) ? 'selected' : '';
                                   $selectedRegistered = ($this->survey->access_id == 1) ? 'selected' : '';

                                   $pane = & JPane::getInstance('sliders', array('startOffset' => 0, 'startTransition' => 0));
                                   echo $pane->startPane('surveyParamPane');

                                   echo $pane->startPanel(JText::_('PARAMETERS'), 'surveyParams');

                                   echo '<table width="100%" border="0">';
                                   echo '<tr>';
                                   echo '<td width="30%">' . JText::_('ACCESS') . '</td>';
                                   echo '<td>';
                                   echo '  <select name="access_id">';
                                   echo '      <option value="0" ' . $selectedPublic . ' >' . JText::_('PUBLIC') . '</option>';
                                   echo '      <option value="1" ' . $selectedRegistered . ' >' . JText::_('PRIVATE') . '</option>';
                                   echo '  </select>';
                                   echo '</td>';
                                   echo '</tr>';

                                   echo '<tr>';
                                   echo '<td>' . JText::_('PUBLISHED_UP') . '</td>';
                                   echo '<td>' . JHTML::calendar($this->survey->published_up, 'published_up', 'published_up_id') . '</td>';
                                   echo '</tr>';

                                   echo '<tr>';
                                   echo '<td>' . JText::_('PUBLISHED_DOWN') . '</td>';
                                   echo '<td>' . JHTML::calendar($this->survey->published_down, 'published_down', 'published_down_id') . '</td>';
                                   echo '</tr>';

                                   echo '<tr>';
                                   echo '<td>' . JText::_('UNIQUE_SESSION') . '</td>';
                                   echo '<td><input type="radio" name="unique_session" value="0"';
                                   echo ($this->survey->unique_session) ? '' : 'checked';
                                   echo '/>' . JText::_('NO');
                                   echo '<input type="radio" name="unique_session" value="1"';
                                   echo ($this->survey->unique_session) ? 'checked' : '';
                                   echo '/>' . JText::_('YES') . '</td>';
                                   echo '</tr>';

                                   echo '<tr>';
                                   echo '<td>' . JHTML::tooltip('(exp: http://www.jquarks.com or index.php?option=com_jquarks4s)', JText::_('REDIRECT_URL'), '', JText::_('REDIRECT_URL')) . '</td>';
                                   
                                   echo '<td><input name="redirect_url" type="text" size="75" value="'.$this->survey->redirect_url.'" /></td>';
                                   echo '</tr>';

                                   echo '</table><br/><br/>';
                                   echo $pane->endPanel();
                                   echo $pane->endPane();
?>
                               </td>
                           </tr>
                       </table>

                       <input type="hidden" name="option"     value="com_jquarks4s" />
                       <input type="hidden" name="controller" value="surveys" />
                       <input type="hidden" name="task"       value="" />
                       <input type="hidden" name="id"         value="<?php echo $this->survey->id; ?>" />
<?php echo JHTML::_('form.token'); ?>

</form>