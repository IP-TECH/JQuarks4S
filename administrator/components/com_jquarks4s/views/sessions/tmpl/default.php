<?php
    defined('_JEXEC') or die('Restricted access');
?>
<form action="index.php" method="post" name="adminForm">

	<div id="editcell">

<table class="adminlist">
    <thead>
  <tr>
    <th width="20">#</th>
    <th width="20">
        <input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $this->sessions ); ?>);" />
    </th>
    <th width="100"><?php  echo JText::_('VIEW_ANSWERS'); ?></th>
    <th width="220"><?php echo JText::_('USER'); ?></th>
    <th width="120"><?php echo JText::_('SUBMIT_DATE'); ?></th>
    <th width="120"><?php echo JText::_('IP_ADDRESS'); ?></th>
    <th width="50"><?php  echo JText::_('SURVEY_ID'); ?></th>
    <th width="120"><?php echo JText::_('EDIT_SURVEY'); ?></th>
    <th width="120"><?php echo JText::_('VIEW_SURVEY'); ?></th>
    <th width="50"><?php echo JText::_('SESSION_ID'); ?></th>
  </tr>
    </thead>
    <tbody>
  <?php
			if ( count($this->sessions)) :
				$k = 0;
				for ($i = 0, $n = count( $this->sessions ) ; $i < $n ; $i++) :
					$row =& $this->sessions[$i];
					$checked = JHTML::_( 'grid.id', $i, $row->id );
					$linkSurvey  = JRoute::_( 'index.php?option=com_jquarks4s&controller=surveys&task=edit&cid[]='. $row->id );
                                        $linkSession = JRoute::_( 'index.php?option=com_jquarks4s&controller=sessions&task=viewSession&id='. $row->id . '&cid[]=' . $row->survey_id );
                                        $linkGoToSurvey = JURI::root().'index.php?option=com_jquarks4s&controller=survey&id='.$row->survey_id;
                    
					?>
					<tr class="<?php echo "row$k"; ?>">
						<td align="center">
							<?php echo $i+1; ?>
						</td>
						<td>
							<?php echo $checked; ?>
						</td>
						<td>
							<a href="<?php echo $linkSession; ?>" ><?php echo JText::_('VIEW_ANSWERS'); ?></a>
						</td>
                        <td>
							<?php echo ( ! is_null($row->user_name)) ? $row->user_name : JText::_('ANONYMOUS'); ?>
						</td>
						<td>
							<?php echo $row->submit_date; ?>
						</td>
                        <td>
							<?php echo $row->ip_address; ?>
						</td>
                        <td>
							<?php echo $row->survey_id; ?>
						</td>
						<td>
                            <a href="<?php echo $linkSurvey; ?>" ><?php echo JText::_('EDIT_SURVEY'); ?></a>
						</td>
                        <td>
                            <a target="_blank" href="<?php echo $linkGoToSurvey; ?>" ><?php echo JText::_('GO_TO_SURVEY'); ?></a>
						</td>
                        <td>
							<?php echo $row->id; ?>
						</td>
					</tr>
					<?php
					$k = 1 - $k;
				endfor ;
			else :
				echo '<tr><td colspan="10">' . JText::_('THERE_ARE_NO_RECORDS') . '</td></tr>' ;
			endif ;
		?>
    </tbody>
</table>

</div>

	<input type="hidden" name="option"     value="com_jquarks4s" />
	<input type="hidden" name="controller" value="sessions" />
	<input type="hidden" name="task"       value="" />
	<input type="hidden" name="boxchecked" value="0" />
    <?php  echo JHTML::_( 'form.token' ); ?>
</form>