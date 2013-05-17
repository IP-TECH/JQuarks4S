<?php
    defined('_JEXEC') or die('Restricted access');
?>

<form action="index.php" method="post" name="adminForm" id="adminForm">
<div align="center">
    <?php if ($this->action == 'place') { ?>
    <a href="javascript:submitform();">
    <img  width="32" height="32" src="components/com_jquarks4s/assets/images/place.png" alt="<?php echo JText::_('PLACE_IN_SECTION'); ?>" title="<?php echo JText::_('PLACE_IN_SECTION'); ?>" />
    <br /><?php echo JText::_('PLACE_IN_SECTION'); ?>
    </a>
    <?php } else { ?>
    <a href="javascript:submitform();">
    <img  width="32" height="32" src="components/com_jquarks4s/assets/images/unplace.png" alt="<?php echo JText::_('UNPLACE_FROM_SECTION'); ?>" title="<?php echo JText::_('UNPLACE_FROM_SECTION'); ?>" />
    <br /><?php echo JText::_('UNPLACE_FROM_SECTION'); ?>
    </a>
    <?php } ?>
</div>

<div class="editcell">
    <table class="adminlist">
        <thead>
        <th width="20">
			<?php echo JText::_( 'NUM' ); ?>
		</th>
        <th width="20">
         <input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $this->sections ); ?>);" />
        </th>
        <th><?php echo JText::_('SECTION'); ?></th>
        <th width="20"><?php echo JText::_('ID'); ?></th>
        </thead>
        <tbody>
        <?php
                if (count($this->sections)) :
                $k = 0;
                $i = 0;
                foreach ($this->sections as $section) :
                        $checked = JHTML::_( 'grid.id', $i, $section->id );
        ?>
        <tr class="<?php echo "row$k"; ?>">
            <td><?php echo $i+1; ?></td>
            <td><?php echo $checked; ?></td>
            <td><?php echo $section->name; ?></td>
            <td><?php echo $section->id; ?></td>
        </tr>
        <?php
                $k = 1 - $k;
                $i++;
                endforeach;
        ?>
        <?php else :
				echo '<tr><td colspan="6">' . JText::_('THERE_ARE_NO_RECORDS') . '</td></tr>' ;
			endif ;
        ?>
        </tbody>

    </table>
</div>


    <input type="hidden" name="option"     value="com_jquarks4s" />
	<input type="hidden" name="controller" value="qs" />
    <input type="hidden" name="task"       value="<?php echo $this->action ; ?>" />
    <input type="hidden" name="id"         value="<?php echo $this->question_id ; ?>" />
    <input type="hidden" name="boxchecked" value="0" />
    <?php  echo JHTML::_( 'form.token' ); ?>
</form>
