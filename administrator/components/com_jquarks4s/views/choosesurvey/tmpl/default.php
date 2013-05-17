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
					<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $this->surveys ); ?>);" />
				</th>
				<th>
					<?php echo JText::_('TITLE'); ?>
				</th>
				<th width="10">
					<?php echo JText::_( 'ID');  ?>
				</th>
			</tr>
		</thead>
		<tbody>
<?php
if ( count($this->surveys)) :
    $k = 0;
	for ($i = 0, $n = count( $this->surveys ) ; $i < $n ; $i++) :
        $row =& $this->surveys[$i];
		$checked = JHTML::_( 'grid.id', $i, $row->id );
?>
					<tr class="<?php echo "row$k"; ?>">
						<td align="center">
							<?php echo $i+1; ?>
						</td>
						<td>
							<?php echo $checked; ?>
						</td>
						<td>
							<?php echo $row->title ; ?>
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
	<input type="hidden" name="controller" value="analysis" />
	<input type="hidden" name="task"       value="selectQuestions" />
	<input type="hidden" name="boxchecked" value="0" />
	<?php  echo JHTML::_( 'form.token' ); ?>
</form>
