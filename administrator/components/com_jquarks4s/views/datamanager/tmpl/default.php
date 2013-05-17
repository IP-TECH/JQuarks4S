<?php
    defined('_JEXEC') or die('Restricted access');
?>
<table border="0" width="100%">
    <tr>
        <td width="50%">
<fieldset>
    <legend><?php echo JText::_('EXPORT'); ?></legend>
    <?php echo JText::_('SELECT_DATA_TYPE_YOU_WANT_TO_EXPORT'); ?>
    <form action="index.php" method="post" name="adminForm">
    <table class="adminform">
        <tr>
            <td>
                
            <input type="radio" name="export_type" value="1"><?php echo JText::_('ANSWERS'); ?><br />
            <input type="radio" name="export_type" value="2"><?php echo JText::_('QUESTIONS').' (not available)'; ?><br />
            <input type="radio" name="export_type" value="3"><?php echo JText::_('SURVEYS').' (not available)'; ?><br />
            <input type="radio" name="export_type" value="4"><?php echo JText::_('SURVEY_AND_ITS_SECTIONS_AND_QUESTIONS').' (not available)'; ?><br />
            </td>
            <td>
                <?php
                    jimport('joomla.html.toolbar');
                    $barExport = new JToolBar( 'toolbarExport' );
                    $barExport->appendButton( 'standard', 'apply', JText::_('GO'), 'export', false );
                    echo $barExport->render();
                ?>
                
            </td>
        </tr>
    </table>
                <input type="hidden" name="option"     value="com_jquarks4s" />
                <input type="hidden" name="controller" value="datamanager" />
                <input type="hidden" name="task"       value="" />
                </form>
</fieldset>

        </td>
        <td>
<fieldset>
    <legend><?php echo JText::_('IMPORT').' (not available)'; ?></legend>
    <div style="height: 120px"></div>
</fieldset>
        </td>

</tr>
</table>
