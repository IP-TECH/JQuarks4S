<?php
    defined('_JEXEC') or die('Restricted access');
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
					<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $this->questions ); ?>);" />
				</th>
				<th>
					<?php echo JText::_('QUESTION'); ?>
				</th>
				<th width="200">
					<?php echo JText::_('NATURE'); ?>
				</th>
                <th width="200">
					<?php echo JText::_('TYPE'); ?>
				</th>
                <th width="20">
					<?php echo JText::_( 'ID');  ?>
				</th>
			</tr>
		</thead>
		<tbody>
<?php
if ( count($this->questions)) :
    $k = 0;
	for ($i = 0, $n = count( $this->questions ) ; $i < $n ; $i++) :
        $row =& $this->questions[$i];
        $row_id = (is_null($row->row_id)) ? 0 : $row->row_id;
		$checked = JHTML::_( 'grid.id', $i, $row->id.'-'.$row_id );

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
                            <?php if ($row->row_title) echo '<br />'.$row->row_title ; ?>
						</td>
						<td>
							<?php echo ( ! $row->nature) ? JText::_('QUALITATIVE') : JText::_('QUANTITATIVE'); ?>
						</td>
                        <td>
							<?php echo JText::_($row->type) ; ?>
						</td>
                        <td>
							<?php echo $row->id; ?>
						</td>
					</tr>
<?php
        $k = 1 - $k;
	endfor ;
else :
    echo '<tr><td colspan="6">' . JText::_('THERE_ARE_NO_RECORDS') . '</td></tr>' ;
endif ;
?>
    </tbody>
</table>
</div>

	<input type="hidden" name="option"     value="com_jquarks4s" />
	<input type="hidden" name="controller" value="analysis" />
	<input type="hidden" name="task"       value="" />
	<input type="hidden" name="boxchecked" value="0" />
    <input type="hidden" name="survey_id"  value="<?php echo $this->survey_id; ?>" />
	<?php  echo JHTML::_( 'form.token' ); ?>
</form>
