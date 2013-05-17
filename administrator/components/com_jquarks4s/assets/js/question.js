//question view js file

// prop line
var PROP;
var PROPINDEX;


function setPropIndex()
{
    PROPINDEX = $('propositionsNbr').getProperty('value');
}


// override js method for custom button saveContinue
function submitbutton(pressbutton)
{
    // Some conditions
    submitForm();
    //document.adminForm.task.value = 'saveContinue';
    // More conditions
    submitform(pressbutton);
}

function submitForm()
{
    var elSel = $('type_id');
    var i;
    for (i = elSel.length - 1; i>=0; i--)
    {
        if (elSel.options[i].selected)
        {
            if (elSel.options[i].value == '2' || elSel.options[i].value == '3')
            { //single/multiple choice

            }
            else if  (elSel.options[i].value == '4')
            { //matrix
               $('lines_select').getElements('option').each(function setSelected(item) {
                    item.setProperty('selected', 'selected');
               });
               $('columns_select').getElements('option').each(function setSelected(item) {
                    item.setProperty('selected', 'selected');
               });
            }
        }
   }

}


function setProp () {
    PROP = $('prop_table').getElements('div').getLast().clone();
    PROP.getElements('input[type=checkbox]').setProperty('checked', '');
    PROP.getElements('textarea').empty();
}

function delProposition(prop)
{
    var pere = document.getElementById('prop_table');
    pere.removeChild(prop.parentNode);
    
}


function addProposition()
{
    var lastProp = $('prop_table').getElements('div').getLast();
    var lastPropClone = PROP.clone();
    
    PROPINDEX++;
    lastPropClone.getElements('textarea').setProperty('name', 'proptext[' + PROPINDEX + ']');
    lastPropClone.getElements('input[type=checkbox]').setProperty('name', 'is_text_field[' + PROPINDEX + ']');
    lastPropClone.getElements('input[type=hidden]').setProperty('name', 'prop_id[' + PROPINDEX + ']');
    lastPropClone.getElements('input[type=hidden]').setProperty('value', '0');
    if ($chk(lastProp)) {
        lastPropClone.inject(lastProp, 'after');
    }
    else {
        lastPropClone.inject($('prop_table'));
    }
    
}

function addLine(event)
{
    var textadd = document.getElementById('add_line');
    if (event.keyCode == 13 && textadd.value != '') {
        appendOptionLast('l');
    }
}

function addColumn(event) {
    var textadd = document.getElementById('add_column');
    if (event.keyCode == 13 && textadd.value != '') {
        appendOptionLast('c');
    }
}


function setdiv()
{
    //var value = $('type_id').getSelected().getLast().get('value');
    //alert(value);
    var elSel = $('type_id');
    var i;
    for (i = elSel.length - 1; i>=0; i--)
    {
        if (elSel.options[i].selected)
        {
            if (elSel.options[i].value == '1')
            { //text
                $('prop_table').setStyle('display','none');
                $('matrix_table').setStyle('display','none');
                $('add_prop_div').setStyle('display','none');
            }
            else if (elSel.options[i].value == '2' || elSel.options[i].value == '3')
            { //single/multiple choice
                $('prop_table').setStyle('display','');
                $('add_prop_div').setStyle('display','');
                $('matrix_table').setStyle('display','none');
            }
            else if  (elSel.options[i].value == '4')
            { //matrix
                $('prop_table').setStyle('display','none');
                $('add_prop_div').setStyle('display','none');
                $('matrix_table').setStyle('display','');
            }
        }
   }
}


function insertOptionBefore(list)
{
    var textadd;
    var elSel;
    
    if (list == 'c')
    {
        textadd = document.getElementById('add_column');
        elSel   = document.getElementById('columns_select');
    }
    else if (list == 'l')
    {
        textadd = document.getElementById('add_line');
        elSel   = document.getElementById('lines_select');
    }
  
    if (elSel.selectedIndex >= 0)
    {
        var elOptNew   = document.createElement('option');
        elOptNew.text  = textadd.value;
        elOptNew.value = textadd.value;
        textadd.value = '';
        var elOptOld   = elSel.options[elSel.selectedIndex];
        try
        {
            elSel.add(elOptNew, elOptOld); // standards compliant; doesn't work in IE
        }
        catch(ex)
        {
            elSel.add(elOptNew, elSel.selectedIndex); // IE only
        }
    }
}


function removeOptionSelected(list)
{
    var elSel;
    if (list == 'c') {
        elSel = document.getElementById('columns_select');
    }
    else if (list == 'l')
    {
        elSel = document.getElementById('lines_select');
    }
    var i;
    for (i = elSel.length - 1; i>=0; i--)
    {
        if (elSel.options[i].selected)
        {
            elSel.remove(i);
        }
   }
}


function appendOptionLast(list)
{
    var textadd;
    var elSel;

    if (list == 'c')
    {
        textadd = document.getElementById('add_column');
        elSel = document.getElementById('columns_select');
    }
    else if (list == 'l')
    {
        textadd = document.getElementById('add_line');
        elSel = document.getElementById('lines_select');
    }

  var elOptNew = document.createElement('option');
  elOptNew.text  = textadd.value;
  elOptNew.value = textadd.value;
  textadd.value  = '';

  try
  {
    elSel.add(elOptNew, null); // standards compliant; doesn't work in IE
  }
  catch(ex)
  {
    elSel.add(elOptNew); // IE only
  }
}
