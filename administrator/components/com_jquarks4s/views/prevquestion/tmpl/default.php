<?php
    defined('_JEXEC') or die('Restricted access');
?>
<script>
    var i;

    // NATURE
    var nature = "<?php echo JText::_('QUALITATIVE'); ?>";
    var nat = parent.document.getElementsByName('nature');
    if (nat.item(0).checked == false)
    {
        nature = "<?php  echo JText::_('QUANTITATIVE'); ?>";
    }
    
    
    // IS COMPULSORY
    var is_compulsory = false;
    var com = parent.document.getElementsByName('is_compulsory');
    if (com.item(0).checked == false) {
            is_compulsory = true;
    }

    // STATEMENT
    var statement = parent.tinyMCE.activeEditor.getContent();
    if (is_compulsory == true)
    {
        var lst = statement.lastIndexOf("</p>");
        var asterisk = ' *';
        statement = statement.substring(0, lst);
        statement += asterisk.fontcolor("red")+ '</p>';
    }
    
    
    //TYPE
    var type_id = null;
    var fields = null; //html content
    var textarea = '<textarea cols="35" rows="3"></textarea>';
    var props = null;
    var is_text_field;
    var lines;
    var columns;

    var t = parent.document.getElementById('type_id');
    type_id = t.options[t.selectedIndex].value;

    switch (type_id)
    {
        case '1': //textarea
            fields = textarea;
            break;

        case '2': // radio
        case '3': //checkboxes
            var typeinput;
            if (type_id == '2') {
                typeinput = 'radio';
            }
            else {
                typeinput = 'checkbox';
            }
            fields = '';
            props         = parent.document.getElements('textarea[id^=proptext]');
            var i =0;
            for (i=0; i<props.length; i++)
            {
                fields += '<input name="prev_prop" type="'+ typeinput +'">' + props[i].value;
                is_text_field = parent.document.getElementsByName('is_text_field['+ i +']');
                if (is_text_field.item(0).checked) {
                    fields += '<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input name="prev_prop_txtfld" type="text" />';
                }
                fields += '<br />'
            }

            break;

        case '4': //table
            
            var j;
            lines   = parent.document.getElementsByName('lines_select[]');
            columns = parent.document.getElementsByName('columns_select[]');
            nbrl = lines[0].length   ;
            nbrc = columns[0].length ;
            fields = '<table border="1">';
            for (i=-1; i<nbrl; i++)
            {
                fields += '<tr>';
                for (j=-1; j < nbrc; j++)
                {
                    if (i == -1)
                    {
                        if (j == -1) { //remplissage des colonnes
                            fields += '<td></td>';
                        }
                        else {
                            fields += '<td>' + columns[0].options.item(j).value + '</td>';
                        }
                    }
                    else
                    {
                        if (j == -1) {
                            fields += '<td>' + lines[0].options.item(i).value + '</td>';
                        }
                        else {
                            fields += '<td><input type="radio" name="group[' + i +  ']" /></td>';
                        }

                    }

                }
                fields += '</tr>';
            }
            fields += '</table>'

    }
    
    //BUILDING HTML PREVIEW
    document.write('<html><body>');
    document.write(statement);
    document.write(fields);
    document.write('</body></html>');
</script>
