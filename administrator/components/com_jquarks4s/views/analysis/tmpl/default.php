<?php
    defined('_JEXEC') or die('Restricted access');

    JHTML::_('behavior.tooltip');
?>

<form action="index.php" method="post" name="adminForm">

	<div id="editcell">
		<table class="adminlist">
		<thead>
			<tr>
				<th width="20">
						<?php echo JText::_( '#' ); ?>
				</th>
				<th width="20">
					<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $this->analysis ); ?>);" />
				</th>
                <th>
					<?php echo JText::_('NAME'); ?>
                </th>
				<th width="120">
					<?php echo JText::_('TYPE'); ?>
				</th>
				<th width="120">
					<?php echo JText::_('SAVE_DATE'); ?>
				</th>
                <th>
					<?php echo JText::_('SURVEY_TITLE'); ?>
				</th>
                <th width="10">
					<?php echo JText::_( 'ID');  ?>
				</th>
			</tr>
		</thead>
		<tbody>
		<?php
			if ( count($this->analysis)) :
				$k = 0;
				for ($i = 0, $n = count( $this->analysis ) ; $i < $n ; $i++) :
					$row =& $this->analysis[$i];
					$checked = JHTML::_( 'grid.id', $i, $row->id );
					$link = JRoute::_( 'index.php?option=com_jquarks4s&controller=analysis&task=viewAnalysis&cid[]='. $row->id );
					?>
					<tr class="<?php echo "row$k"; ?>">
						<td align="center">
                            <?php echo $i+1; ?>
						</td>
						<td>
							<?php echo $checked; ?>
						</td>
                        <td>
							<?php echo '<a href="' . $link . '">' . JHTML::tooltip($row->description, $row->name, '', $row->name) . '</a>' ?>
						</td>
						<td>
							<?php echo JText::_($row->type); ?>
						</td>
                        <td>
							<?php echo $row->save_date; ?>
						</td>
						<td>
							<?php echo $row->survey_title; ?>
						</td>
						<td>
							<?php echo $row->id; ?>
						</td>
					</tr>
					<?php
					$k = 1 - $k;
				endfor ;
			else :
				echo '<tr><td colspan="7">' . JText::_('THERE_ARE_NO_RECORDS') . '</td></tr>' ;
			endif ;
		?>
		</tbody>

		</table>
	</div>

	<input type="hidden" name="option"     value="com_jquarks4s" />
	<input type="hidden" name="controller" value="analysis" />
	<input type="hidden" name="task"       value="" />
	<input type="hidden" name="boxchecked" value="0" />
	<?php  echo JHTML::_( 'form.token' ); ?>
</form>
