<?php
    defined('_JEXEC') or die('Restricted access');
?>

<script type="text/javascript">

    function setSurveyID(sid)
    {
            new Ajax('index.php?option=com_jquarks4s&controller=datamanager&task=setSurveyID&id='+sid,
            {
                update:'',
                method:'get',
                data: '',
                onComplete : function()
                {
                }
            }).request();
    }

    window.addEvent('domready', function(){ setSurveyID(<?php echo $this->surveys[0]->id; ?>); });
</script>

<form action="index.php" method="post" name="adminForm">
<?php 
        //echo JText::_('EXPORT_ANSWERS_FROM_SURVEY');
        echo 'Pick a survey then select questions and sessions you want to export';
?>
	<div id="editcell">
		<table class="adminlist">
		<thead>
			<tr>
				<th width="20">
						<?php echo JText::_( '#' ); ?>
				</th>
				<th width="20">
					
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
?>
					<tr class="<?php echo "row$k"; ?>">
						<td align="center">
							<?php echo $i+1; ?>
						</td>
						<td>
                            <input type="radio" value="<?php echo $row->id; ?>" name="survey_id" <?php echo ($i == 0) ? 'checked' : ''; ?> onclick="setSurveyID(<?php echo $row->id; ?>)" />
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
	<input type="hidden" name="controller" value="datamanager" />
	<input type="hidden" name="task"       value="" />
	<input type="hidden" name="boxchecked" value="0" />
	<?php  echo JHTML::_( 'form.token' ); ?>
</form>
