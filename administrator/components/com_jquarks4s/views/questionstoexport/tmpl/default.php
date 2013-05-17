<?php
    defined('_JEXEC') or die('Restricted access');

    require_once (JPATH_COMPONENT . DS . 'helpers' . DS . 'grid.php');

    $currentSession = &JFactory::getSession();
    $checkedQuestions = $currentSession->get('questions_to_export', array());
    
?>
<form action="index.php" method="post" name="adminForm">
<div align="center">
<a href="javascript:submitform();">
    <img  width="32" height="32" src="components/com_jquarks4s/assets/images/place.png" alt="<?php echo JText::_('SELECT_QUESTIONS'); ?>" title="<?php echo JText::_('SELECT_QUESTIONS'); ?>" />
    <br /><?php echo JText::_('SELECT_QUESTIONS'); ?>
</a>
</div>
	<div id="editcell">
		<table class="adminlist">
		<thead>
			<tr>
				<th width="20">
						<?php echo JText::_( '#' ); ?>
				</th>
				<th width="20">
					<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $this->questions ); ?>);" />
				</th>
				<th>
					<?php echo JText::_('ALIAS'); ?>
				</th>
                <th width="20">
					<?php echo JText::_('ID'); ?>
				</th>
  			</tr>
		</thead>
		<tbody>
		<?php
			if ( count($this->questions)) :
				$k = 0;
				for ($i = 0, $n = count( $this->questions ) ; $i < $n ; $i++) :
					$row =& $this->questions[$i];
					if ( in_array($row->id, $checkedQuestions) ) {
                        $checked = Jquarks4sJHTMLGridHelper::id($i, $row->id, true );
                    }
                    else {
                        $checked = Jquarks4sJHTMLGridHelper::id($i, $row->id);
                    }
		?>
					<tr class="<?php echo "row$k"; ?>">
						<td align="center">
							<?php echo $i+1; ?>
						</td>
						<td>
							<?php echo $checked; ?>
						</td>
                        <td>
							<?php echo $row->alias ; ?>
						</td>
						<td>
							<?php echo $row->id; ?>
						</td>
					</tr>
					<?php
					$k = 1 - $k;
				endfor ;
			else :
				echo '<tr><td colspan="4">' . JText::_('THERE_ARE_NO_RECORDS') . '</td></tr>' ;
			endif ;
		?>
        </tbody>
    </table>
</div>
	<input type="hidden" name="option"     value="com_jquarks4s" />
	<input type="hidden" name="controller" value="datamanager" />
	<input type="hidden" name="task"       value="registerQuestions" />
	<input type="hidden" name="boxchecked" value="0" />
	<?php  echo JHTML::_( 'form.token' ); ?>
</form>
