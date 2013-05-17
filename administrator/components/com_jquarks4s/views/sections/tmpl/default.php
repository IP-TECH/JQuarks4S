<?php defined('_JEXEC') or die('Restricted access'); ?>

<form action="index.php" method="post" name="adminForm">

	<div id="editcell">
		<table class="adminlist">
		<thead>
			<tr>
				<th width="20">
						<?php echo JText::_( '#' ); ?>
				</th>
				<th width="20">
					<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $this->sections ); ?>);" />
				</th>
				<th width="320">
					<?php echo JText::_('SECTION'); ?>
				</th>
				<th>
					<?php echo JText::_('QUESTIONS'); ?>
				</th>
                <th width="60">
					<?php echo JText::_('ORDER'); ?>
                    <!-- default task = saveOrder - find it in the end of this template - index.php?option=com_jquarks4s&controller=sections&task=saveOrder -->
                    <a onclick="submitform();" href="#" >
                        <img title="<?php echo JText::_('SAVE_ORDER'); ?>" alt="<?php echo JText::_('SAVE_ORDER'); ?>" width="16" height="16" src="components/com_jquarks4s/assets/images/saveorder.png">
                    </a>
				</th>
				<th width="20">
					<?php echo JText::_( 'ID');  ?>
				</th>
			</tr>
		</thead>
		<tbody>
<?php
	if ( count($this->sections)) :
				
    	for ($i = 0, $num=1, $k = 0, $ids =0, $n = count( $this->sections ); $i < $n; $i++, $ids = $row->section_id) :

        $row =& $this->sections[$i];
        $nbrq = -1;
        if($row->rowspan == 0)
        {
            $nbrq = 0;
            $row->rowspan = 1;
        }
        $checked = JHTML::_( 'grid.id', $num-1, $row->section_id );
		$link = JRoute::_( 'index.php?option=com_jquarks4s&controller=sections&task=edit&cid[]=' . $row->section_id );
        $linkQuestion = JRoute::_( 'index.php?option=com_jquarks4s&controller=questions&task=edit&cid[]=' . $row->question_id );
?>

     <!-- generating table lines -->

<tr class="row<?php echo $k; ?>" id="line<?php echo $num; ?>" >
<?php
    if($row->section_id != $ids)
    {
        echo '<td align="center" rowspan="' . $row->rowspan . '">'.$num.'</td>' ;
        echo '<td rowspan="' . $row->rowspan . '">'.$checked.'</td>' ;
        echo '<td rowspan="' . $row->rowspan . '"><a href="'.$link.'">'.$row->section.'</a></td>' ;
    }
?>
<td><a href="<?php echo $linkQuestion; ?>" ><?php if ($row->question) echo $row->question; ?></a></td>
<td>
    <input type="text" style="text-align: center;"
           class="text_area"
           value="<?php if ($row->question_rank) echo $row->question_rank; ?>" size="5"
           name="rank[<?php echo $row->section_id; ?>][<?php echo ($nbrq == 0) ? 0 : $row->question_id ; ?>]" />
</td>

<?php
    if($row->section_id != $ids) {
        echo '<td align="center" rowspan="' . $row->rowspan . '">'.$row->section_id.'</td>' ;
    }
?>

</tr> <!-- finishing line -->

<?php
    if($row->section_id != $ids)
    {
        $k = 1 - $k;
        $num++;
    }


    endfor ;
else :
    echo '<tr><td colspan="6">' . JText::_('THERE_ARE_NO_RECORDS') . '</td></tr>' ;
endif ;
?>

        </tbody>
    </table>
</div>

	<input type="hidden" name="option"     value="com_jquarks4s" />
	<input type="hidden" name="controller" value="sections" />
	<input type="hidden" name="task"       value="saveOrder" />
	<input type="hidden" name="boxchecked" value="0" />
	<?php  echo JHTML::_( 'form.token' ); ?>
</form>