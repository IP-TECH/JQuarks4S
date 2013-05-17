<?php
    defined('_JEXEC') or die('Restricted access');

    $cssUrl = JRoute::_("components/com_jquarks4s/assets/css/default.css");
    $document =& JFactory::getDocument();
    $document->addStyleSheet($cssUrl);
?>

<form action="index.php" method="post" name="adminForm">

<div class="editcell">

<table  class="adminlist">

  <thead>
	<tr>
        <th width="20">
			<?php echo JText::_( '#' ); ?>
        </th>
	<th width="20">
        <input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $this->surveys ); ?>);" />
	</th>
    <th><?php echo JText::_( 'SURVEY');  ?></th>
    <th width="60"><?php echo JText::_( 'PUBLISHED');  ?></th>
    <th width="60"><?php echo JText::_( 'ACCESS');  ?></th>
    <th><?php echo JText::_( 'SECTIONS');  ?></th>
    <th width="60">
		<?php echo JText::_('ORDER'); ?>
        <a onclick="submitform();" href="#" >
          <img title="<?php echo JText::_('SAVE_ORDER'); ?>" 
               alt="<?php echo JText::_('SAVE_ORDER'); ?>" width="16" height="16"
               src="components/com_jquarks4s/assets/images/saveorder.png">
        </a>
	</th>
    <th width="20">
        <?php echo JText::_( 'ID');  ?>
    </th>
  </tr>
  </thead>
  <tbody>
<?php
	if ( count($this->surveys)) :

    	for ($i = 0, $num=1, $k = 0, $ids =0, $n = count( $this->surveys ); $i < $n; $i++, $ids = $row->survey_id) :
            $row =& $this->surveys[$i];
            $nbrq = -1;
            if($row->rowspan == 0)
            {
                $nbrq = 0;
                $row->rowspan = 1;
            }
            $checked = JHTML::_( 'grid.id', $num-1, $row->survey_id );
            $link = JRoute::_( 'index.php?option=com_jquarks4s&controller=surveys&task=edit&cid[]=' . $row->survey_id );
            $linkSection = JRoute::_( 'index.php?option=com_jquarks4s&controller=sections&task=edit&cid[]=' . $row->section_id );

            $linkPublished = JRoute::_( 'index.php?option=com_jquarks4s&controller=surveys&task=setPublished&cid[]=' . $row->survey_id );
            if ($row->published == 0) //non published
            {
                $imgPublished = 'components/com_jquarks4s/assets/images/unpublished.png';
                $titlePublished = JText::_('PUBLISH');
            }
            else
            {
                $imgPublished = 'components/com_jquarks4s/assets/images/published.png';
                $titlePublished = JText::_('UNPUBLISH');
            }

            $linkAccess = JRoute::_( 'index.php?option=com_jquarks4s&controller=surveys&task=setAccess&cid[]=' . $row->survey_id );
            if ($row->access_id == 0) // public survey
            {
                $echoAccess = JText::_('PUBLIC');
                $classAccess = 'green';
            }
            else
            {
                $echoAccess = JText::_('PRIVATE');
                $classAccess = 'red';
            }
        
?>
  <!-- building lines -->

<tr class="row<?php echo $k; ?>" id="line<?php echo $num; ?>" >
<?php
     if($row->survey_id != $ids) :
?>
        <td align="center" rowspan="<?php echo $row->rowspan; ?>"><?php echo $num; ?></td>
        <td rowspan="<?php echo $row->rowspan; ?>"><?php echo $checked; ?></td>
        <td rowspan="<?php echo $row->rowspan; ?>">
            <a href="<?php echo $link; ?>"><?php echo $row->survey; ?></a>
        </td>
        <td rowspan="<?php echo $row->rowspan; ?>">
            <a href="<?php echo $linkPublished; ?>">
                <img alt="<?php echo $titlePublished; ?>" title="<?php echo $titlePublished; ?>" src="<?php echo $imgPublished; ?>" />
            </a>
        </td>
        <td rowspan="<?php echo $row->rowspan; ?>">
            <a class="<?php echo $classAccess; ?>" href="<?php echo $linkAccess; ?>">
                <?php echo $echoAccess; ?>
            </a>
        </td>
<?php
     endif;
?>
<td>
    <a href="<?php echo $linkSection; ?>" ><?php if ($row->section) echo $row->section; ?></a>
</td>
<td>
    <div align="center">
    <input type="text" style="text-align: center;"
           class="text_area"
           value="<?php if ($row->section_rank) echo $row->section_rank; ?>" size="5"
           name="rank[<?php echo $row->survey_id; ?>][<?php echo ($nbrq == 0) ? 0 : $row->section_id ; ?>]" />
    </div>
</td>

<?php
    if($row->survey_id != $ids) {
        echo '<td align="center" rowspan="' . $row->rowspan . '">'.$row->survey_id.'</td>' ;
    }
?>
  </tr> <!-- end building lines -->
<?php
    if($row->survey_id != $ids)
    {
        $k = 1 - $k;
        $num++;
    }


    endfor ;
else :
    echo '<tr><td colspan="8">' . JText::_('THERE_ARE_NO_RECORDS') . '</td></tr>' ;
endif ;
?>
  </tbody>
</table>

</div>

	<input type="hidden" name="option"     value="com_jquarks4s" />
	<input type="hidden" name="controller" value="surveys" />
	<input type="hidden" name="task"       value="saveOrder" />
	<input type="hidden" name="boxchecked" value="0" />
	<?php  echo JHTML::_( 'form.token' ); ?>
</form>