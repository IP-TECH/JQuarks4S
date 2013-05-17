<?php //defined('_JEXEC') or die('restricted access'); ?>

<?php
$headerDisplayed = false;
$sectionId    = null;
$questionId   = null;

$footer = null;;

$questionNbr = 0;

$arrayRows = array();
$arrayColumns = array();

$is_previous_question_matrix = false;


    foreach ($this->rows as $row):

?>



<!-- HEADER -->
<?php
        if ( ! $headerDisplayed) :
            $headerDisplayed =true;
?>
<!-- SURVEY TITLE -->
<div class="componentheading">
    <?php echo $row->title; ?>
</div>
<!-- SURVEY DESCRIPTION -->
<div class="contentdescription">
    <?php echo $row->description; ?>
</div>
<?php endif; ?>

<!-- BODY -->

    <!-- SECTION -->
<?php
    if ($sectionId != $row->section_id) : // if new section
            $sectionId = $row->section_id;

            $questionNbr = 0;

?>
<br />
<h4>
    <?php echo nl2br($row->section_name); ?>
</h4>
<?php endif; ?>

    <!-- QUESTION -->
        <!-- MATRIX PREVIOUS QUESTION -->
<?php
    if ($questionId != $row->question_id && ! is_null($row->question_id)) : // if new question

            $previousQuestionId = $questionId;
            $questionId = $row->question_id;

            $questionNbr++;

            if ($is_previous_question_matrix) // display matrix of previous question
            {
                $nbrl = count($arrayRows);
                $nbrc = count($arrayColumns);

                reset($arrayRows);
                reset($arrayColumns);
                $fields = '<table style="margin-left:10px;" border="1">';
                for ($i = -1; $i < $nbrl; $i++)
                {
                    if ($i != -1) {
                        $currentRow = each($arrayRows);
                    }
                    $fields .= '<tr>';
                    for ($j = -1; $j < $nbrc; $j++)
                    {
                        if ($i == -1)
                        {
                            if ($j == -1) { //remplissage des colonnes
                                $fields .= '<td></td>';
                            }
                            else {
                                $currentColumn = each($arrayColumns);
                                $fields .= '<td>' . $currentColumn[value] . '</td>';
                            }
                        }
                        else
                        {
                            if ($j == -1) {
                                $fields .= '<td>' . $currentRow[value] . '</td>';
                            }
                            else {
                                $currentColumn = each($arrayColumns);
                                $fields .= '<td></td>';
                            }

                        }

                    }
                    reset($arrayColumns);
                    $fields .= '</tr>';
                }
                $fields .= '</table>';

                echo $fields;
            }

            $arrayRows = array();
            $arrayColumns = array();
            $is_previous_question_matrix = false;
?>
<br />
    <!-- QUESTION STATEMENT -->
<div class="question">
<?php
        $questionEcho = $questionNbr.'.'.$row->statement;
        if ( ! $row->is_compulsory) {
            $questionEcho .= '<i> ('.JText::_('OPTIONAL').')</i>';
        }
        echo $questionEcho;
?>
</div>
<?php endif; ?>

        <!-- PROPOSITIONS -->
<?php
switch ($row->type_id) :
    case 1: // text
        ?>
        <br /><br /><br /><br />
        <?php
        break;

    case 2: //single choice
    case 3: // multiple choice
        
        echo '- '.nl2br($row->proposition); ?>
        <br />
        <?php
        if ($row->is_text_field):
        ?>
        <br /><br />
        <?php
        endif;
        break;

    case 4: //matrix
        $is_previous_question_matrix = true;
        if ( ! is_null($row->row_id)) {
            $arrayRows[$row->row_id] = $row->row_title;
        }
        if ( ! is_null($row->column_id)) {
            $arrayColumns[$row->column_id] = $row->column_title;
        }

endswitch;
?>


<?php
    if ( ! $footer) {
        $footer = $row->footer;
    }

    endforeach;

?>

<!-- FOOTER -->
<br /><br />

<!-- FOOTER -->
<br />
<div>
<?php
    echo $footer;
?>
</div>

<br /><br />

<!-- JQUARKS footer -->
<div>Powered by JQuarks</div>


