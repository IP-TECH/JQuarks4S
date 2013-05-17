<?php
    defined('_JEXEC') or die('Restricted access');

    $cssUrl = JRoute::_("components/com_jquarks4s/assets/css/default.css");
    $document =& JFactory::getDocument();
    $document->addStyleSheet($cssUrl);
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
                                <th width="140">
					<?php echo JText::_('ALIAS'); ?>
				</th>
				<th>
					<?php echo JText::_('STATEMENT'); ?>
				</th>
				<th width="120">
					<?php echo JText::_('TYPE'); ?>
				</th>
                                <th width="120">
					<?php echo JText::_('IS_COMPULSORY'); ?>
				</th>
                                <th width="140">
					<?php echo JText::_('NATURE');  ?>
				</th>
				<th width="10">
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
					$checked = JHTML::_( 'grid.id', $i, $row->id );
					$link           = JRoute::_( 'index.php?option=com_jquarks4s&controller=questions&task=edit&cid[]='. $row->id );

                                        $linkCompulsory = JRoute::_( 'index.php?option=com_jquarks4s&controller=questions&task=setCompulsory&cid[]='. $row->id );
                                        if ($row->is_compulsory)
                                        {
                                            $textCompulsory  = JText::_('IS_COMPULSORY');
                                            $classCompulsory = 'red';
                                        }
                                        else
                                        {
                                            $textCompulsory  = JText::_('OPTIONAL');
                                            $classCompulsory = 'green';
                                        }

                                        $linkNature = JRoute::_( 'index.php?option=com_jquarks4s&controller=questions&task=setNature&cid[]='. $row->id );
                                        if ($row->nature)
                                        {
                                            $textNature  = JText::_('QUANTITATIVE');
                                            $classNature = 'black';
                                        }
                                        else
                                        {
                                            $textNature = JText::_('QUALITATIVE');
                                            $classNature = 'maroon';
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
							<a href="<?php echo $link ; ?>">
                                                            <?php echo $row->alias ; ?>
                                                        </a>
						</td>
						<td>
							<a href="<?php echo $link ; ?>">
                                                            <?php echo $row->statement ; ?>
                                                        </a>
						</td>
                                                <td>
							<?php echo JText::_($row->type); ?>
						</td>
                                                <td>
							<a class="<?php echo $classCompulsory ?>" href="<?php echo $linkCompulsory ?>">
                                                            <?php echo $textCompulsory ?>
                                                        </a>
						</td>
						<td>
							<a class="<?php echo $classNature ?>" href="<?php echo $linkNature ?>">
                                                            <?php echo $textNature ?>
                                                        </a>
						</td>
						<td>
							<?php echo $row->id; ?>
						</td>
					</tr>
					<?php
					$k = 1 - $k;
				endfor ;
			else :
				echo '<tr><td colspan="8">' . JText::_('THERE_ARE_NO_RECORDS') . '</td></tr>' ;
			endif ;
		?>
		</tbody>
	
		</table>
	</div>

	<input type="hidden" name="option"     value="com_jquarks4s" />
	<input type="hidden" name="controller" value="questions" />
	<input type="hidden" name="task"       value="" />
	<input type="hidden" name="boxchecked" value="0" />
	<?php  echo JHTML::_( 'form.token' ); ?>
</form>
