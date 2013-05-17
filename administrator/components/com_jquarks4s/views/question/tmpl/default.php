<?php defined('_JEXEC') or die('Restricted access'); ?>

<?php
        $url = JRoute::_("components/com_jquarks4s/assets/js/question.js");
        $document =& JFactory::getDocument();
        $document->addScript($url);

        JHTML::_('behavior.tooltip');
        JHTML::_('behavior.formvalidation');

?>

<script type="text/javascript">window.addEvent('domready', function(){setdiv();setProp();setPropIndex();});</script>
<script type="text/javascript">
    function checkAlias()
    {
        if ($('alias').value == '' && pressbutton != 'cancel') {
          //alert('<?php echo JText::_('ALIAS_FIELD_IS_REQUIRED') ?>');
          //exit;
        } 
    }
</script>
<form action="index.php" method="post" name="adminForm" id="adminForm" onsubmit="submitForm();" class="form-validate">

    <table width="100%" border="0">
  <tr>
      <td width="50%" valign="top">
          <table class="adminform">
              <tr>
                <td width="">
                    <?php echo JHTML::tooltip('Alias is a label for the question. It is not shown in the survey!', JText::_('QUESTION_ALIAS'),
                    '', JText::_('ALIAS').' *'); ?>
                </td>
                <td width=""><input size="70" type="text" id="alias" class="required"
                                    name="alias" value="<?php echo $this->question->alias; ?>" /></td>
              </tr>
          </table>

            	<fieldset class="adminfrom" style="float:left;">
				<legend><?php echo JHTML::tooltip('Question here', JText::_('CONTENT'),'', JText::_( 'CONTENT' )); ?>
                 </legend>
                    <?php
                        echo $this->editor->display('statement', $this->question->statement ,
                                '600', '350', '20', '20',
                                array('image', 'pagebreak', 'readmore'), $this->editor_params);
                    ?>
                </fieldset>
            
    </td>

    <td width="50%" style="vertical-align:top;">
        <table class="adminform" >
        	<tr>
            	<td>
                	<label><?php echo JText::_('NATURE'); ?></label>
                </td>
                <td>
                    <input type="radio" name="nature" value="0" <?php if( ! $this->question->nature) echo 'checked'; ?> ><?php echo JText::_('QUALITATIVE'); ?>
                    <br />
                    <input type="radio" name="nature" value="1" <?php if($this->question->nature) echo 'checked'; ?> ><?php  echo JText::_('QUANTITATIVE'); ?>
                </td>
        	</tr>
            <tr>
            	<td>
                	<label><?php echo JText::_('IS_COMPULSORY'); ?></label>
                </td>
                <td>
                	<input type="radio" name="is_compulsory" value="0" <?php if(!$this->question->is_compulsory) echo 'checked'; ?> ><?php echo JText::_('OPTIONAL'); ?>
                    <br />
                    <input type="radio" name="is_compulsory" value="1" <?php if($this->question->is_compulsory) echo 'checked'; ?> ><?php  echo JText::_('OBLIGATORY'); ?>
                </td>
        	</tr>
            </table>
    	<table class="adminform"  id="question_type_table">
        	<tr>
            	<td><label><?php echo JText::_( 'TYPE' ); ?></label></td>
                <td>
                    <select size="1" name="type_id" id="type_id" onchange="setdiv();" >
                    <?php //generating question types
                    
                    foreach ($this->types as $type) {
                        if ($type->id == $this->question->type_id) {
                            echo  '<option value="' . $type->id . '" selected >' . JText::_($type->title) . '</option>';
                        } else {
                            echo  '<option value="' . $type->id . '" >' . JText::_($type->title) . '</option>';
                        }
                    }
                    
                    ?>
                    </select>
                </td>
        	</tr>
        </table>

        <div id="add_prop_div" align="right">
            <a title="add" href="javascript:void(0)" onclick="addProposition();"><?php echo JHTML::image('administrator/components/com_jquarks4s/assets/images/add_prop.png', 'add'); ?></a>
        </div>

        <div id="prop_table">
<?php
        
        if (isset($this->propositions) && $this->propositions)
        {
            $propindex = 0;
            foreach ($this->propositions as $proposition)
            {

?>
<div  id="prop">
    <textarea id="proptext" name="proptext[<?php echo $propindex; ?>]" cols="35" rows="3"><?php echo $proposition->proposition ; ?></textarea>
    <input id="is_text_field" type="checkbox" name="is_text_field[<?php echo $propindex; ?>]" <?php if ($proposition->is_text_field) echo 'checked'; ?>  /><?php echo JText::_('ADD_TEXT_FIELD'); ?>
    <a title="delete" href="javascript:void(0)" onclick="delProposition(this);"><?php echo JHTML::image('administrator/components/com_jquarks4s/assets/images/del_prop.png', 'del'); ?></a>
    <input type="hidden" name="prop_id[<?php echo $propindex; ?>]" value="<?php echo $proposition->id; ?>" />
</div>
<?php
            $propindex++;
            }
        }
        else
        {

?>
<div id="prop">
    <textarea id="proptext" name="proptext[0]" cols="35" rows="3"></textarea>
    <input id="is_text_field" type="checkbox" name="is_text_field[0]" /><?php echo JText::_('ADD_TEXT_FIELD'); ?>
    <a title="delete" href="javascript:void(0)" onclick="delProposition(this);"><?php echo JHTML::image('administrator/components/com_jquarks4s/assets/images/del_prop.png', 'del'); ?></a>
    <input type="hidden" name="prop_id[0]" value="0" />
</div>
<?php
        }
?>
        </div>

        <div id="matrix_table">
        <table class="adminform">
            <tr id="matrix_prop">
                <td>
                    <fieldset>
                        <legend><?php echo JText::_('LINES'); ?></legend>
                        <table border="0">
                            <tr>
                                <td>
                                    <input onkeypress="addLine(event);" type="text" name="add_line" id="add_line" style="width: 200px">
                                    <a title="<?php echo JText::_('APPEND_LAST'); ?>" href="javascript:void(0)" onclick="appendOptionLast('l');" ><img src="components/com_jquarks4s/assets/images/append.png" ></a>
                        
                        <a title="<?php echo JText::_('INSERT_BEFORE'); ?>" href="javascript:void(0)" onclick="insertOptionBefore('l');" ><img src="components/com_jquarks4s/assets/images/insert.png" ></a>
                                </td>
                            </tr>
                            <tr>
                                <td>
                    <select onkeydown="if (event.keyCode == 46) removeOptionSelected('l');" multiple size="15" id="lines_select" name="lines_select[]" style="width: 200px">
                        <?php
                            if (isset($this->rows) && $this->rows) :
                                foreach ($this->rows AS $row) :
                        ?>
                        <option value="<?php echo $row->title; ?>"><?php echo $row->title; ?></option>
                        <?php
                                endforeach;
                            endif;
                        ?>
                    </select>
                                    <a title="<?php echo JText::_('DELETE_SELECTED'); ?>" style="vertical-align: top" href="javascript:void(0)" onclick="removeOptionSelected('l');" ><img src="components/com_jquarks4s/assets/images/del_prop.png" ></a>
                                </td>
                            </tr>
                        </table>
                    </fieldset>
                </td>
                <td>
                    <fieldset>
                        <legend><?php echo JText::_('COLUMNS'); ?></legend>
                        <table border="0">
                            <tr>
                                <td>
                              <input onkeypress="addColumn(event);" type="text" name="add_column" id="add_column" style="width: 200px" />
                        <a title="<?php echo JText::_('APPEND_LAST'); ?>" href="javascript:void(0)" onclick="appendOptionLast('c');" ><img src="components/com_jquarks4s/assets/images/append.png" ></a>
                        
                        <a title="<?php echo JText::_('INSERT_BEFORE'); ?>" href="javascript:void(0)" onclick="insertOptionBefore('c');" ><img src="components/com_jquarks4s/assets/images/insert.png" ></a>
                                </td>
                            </tr>
                            <tr>
                                <td>
                    <select onkeydown="if (event.keyCode == 46) removeOptionSelected('c');" multiple size="15" id="columns_select" name="columns_select[]" style="width: 200px">
                        <?php
                            if (isset($this->columns) && $this->columns) :
                                foreach ($this->columns AS $col) :
                        ?>
                            <option value="<?php echo $col->title; ?>"><?php echo $col->title; ?></option>
                        <?php
                            endforeach;
                            endif;
                       ?>
                    </select>
                                    <a title="<?php echo JText::_('DELETE_SELECTED'); ?>" style="vertical-align: top" href="javascript:void(0)" onclick="removeOptionSelected('c');" ><img src="components/com_jquarks4s/assets/images/del_prop.png" ></a>
                                </td>
                            </tr>
                        </table>
                    </fieldset>
                </td>
            </tr>

        </table>
        </div>
    </td>
  </tr>
</table>

	<input type="hidden" name="option"        value="com_jquarks4s" />
	<input type="hidden" name="controller"    value="questions" />
    <input type="hidden" name="task"          value="" />
    <input type="hidden" name="id"            value="<?php echo $this->question->id ; ?>" />
    <input type="hidden" id="propositionsNbr" value="<?php echo (isset($this->propositionsNbr)) ? $this->propositionsNbr : 0; ?>"  />
    <?php  echo JHTML::_( 'form.token' ); ?>
    
</form>
