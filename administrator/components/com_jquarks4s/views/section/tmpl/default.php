<?php
    defined('_JEXEC') or die('Restricted access');
?>

<form action="index.php" method="post" name="adminForm" id="adminForm">

<fieldset>
    <legend><?php echo JText::_('NAME'); ?></legend>
    <textarea cols="40" rows="3" name="name" ><?php echo $this->section->name; ?></textarea>
</fieldset>

	<input type="hidden" name="option"     value="com_jquarks4s" />
	<input type="hidden" name="controller" value="sections" />
    <input type="hidden" name="task" value="" />
    <input type="hidden" name="id"         value="<?php echo $this->section->id ; ?>" />
    <?php  echo JHTML::_( 'form.token' ); ?>

</form>
