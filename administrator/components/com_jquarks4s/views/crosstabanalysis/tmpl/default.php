<?php
    defined('_JEXEC') or die('Restricted access');

    JHTML::_('behavior.tooltip');
?>
<?php
        // initializing matrix
        $sampleSize = array();
        foreach ($this->propositions1 AS $prop1)
        {
            foreach ($this->propositions2 AS $prop2)
            {
                $sampleSize[$prop1->proposition_id][$prop2->proposition_id] = 0;
                $sampleSize['no_answer'][$prop2->proposition_id] = 0;
                $answered2[$prop2->proposition_id] = 0;
            }
            reset($this->propositions2);
            $sampleSize[$prop1->proposition_id]['no_answer'] = 0;
            $answered1[$prop1->proposition_id] = 0;
        }
        reset($this->propositions1);
        reset($this->propositions2);

        //all who answered prop x in question 1
        $allAnswered1 = array();
        foreach ($this->propositions1 as $prop1)
        {
            $allAnswered1[$prop1->proposition_id] = 0;
            foreach ($this->sessions1 as $session1)
            {
                if ($session1->answer_id == $prop1->proposition_id) {
                    $allAnswered1[$prop1->proposition_id] ++;
                }
            }
            reset($this->sessions1);
        }
        reset($this->propositions1);

        //all who answered prop x in question 2
        $allAnswered2 = array();
        foreach ($this->propositions2 as $prop2)
        {
            $allAnswered2[$prop2->proposition_id] = 0;
            foreach ($this->sessions2 as $session2)
            {
                if ($session2->answer_id == $prop2->proposition_id) {
                    $allAnswered2[$prop2->proposition_id] ++;
                }
            }
        }

        // calculating sizes
        foreach ($this->propositions1 as $prop1)
        {
            foreach ($this->propositions2 as $prop2)
            {
                foreach ($this->sessions1 AS $session1)
                {
                    foreach ($this->sessions2 AS $session2)
                    {
                        if ($session1->session_id == $session2->session_id
                                && $session1->answer_id == $prop1->proposition_id
                                && $session2->answer_id == $prop2->proposition_id) {
                            $sampleSize[$prop1->proposition_id][$prop2->proposition_id] ++;
                            $answered1[$prop1->proposition_id] ++;
                            $answered2[$prop2->proposition_id] ++;
                        }
                    }
                    reset($this->sessions2);
                }
                reset($this->sessions1);
            }
            reset($this->propositions2);
            // no answer
            // sil ny pas de session 2 qui a repondu a question 2
            if ($session1->session_id == $session2->session_id
                                && $session1->answer_id == $prop1->proposition_id)
            $sampleSize[$prop1->proposition_id]['no_answer'] ++;
        }
        reset($this->propositions1);

?>

<fieldset>
    <legend><?php echo JText::_('SURVEY'); ?></legend>
    <table class="adminlist">
        <thead>
        <th width="20"><?php echo JText::_('ID'); ?></th>
        <th><?php echo JText::_('TITLE'); ?></th>
        <th width="100"><?php echo '# '.JText::_('QUESTIONS'); ?></th>
        <th width="100"><?php echo '# '.JText::_('SESSIONS'); ?></th>
        </thead>
        <tbody>
        <tr>
            <td><?php echo $this->survey->id; ?></td>
            <td><?php echo $this->survey->title; ?></td>
            <td><?php echo $this->survey->nbr_questions; ?></td>
            <td><?php echo $this->survey->population; ?></td>
        </tr>
        </tbody>
    </table>
</fieldset>
<table width="100%" border="0">
    <tr>
        <td width="50%" valign="top">
<fieldset>
    <legend><?php echo JText::_('QUESTION').' 1'; ?></legend>
    <table class="adminlist">
        <thead>
        <th width="20"><?php echo JText::_('ID'); ?></th>
        <th><?php echo JText::_('STATEMENT'); ?></th>
        <th width="100"><?php echo JText::_('TYPE'); ?></th>
        <th width="100"><?php echo JText::_('NATURE'); ?></th>
        </thead>
        <tbody>
        <tr>
            <td><?php echo $this->question1->id; ?></td>
            <td><?php echo $this->question1->statement; ?></td>
            <td><?php echo JText::_($this->question1->type_id); ?></td>
            <td><?php echo ( ! $this->question1->nature) ? JText::_('QUALITATIVE') : JText::_('QUANTITATIVE'); ?></td>
        </tr>
        </tbody>
    </table>
</fieldset>
            <fieldset>
                <legend><?php echo JText::_('PROPOSITIONS'); ?></legend>
            <table class="adminlist">
        <thead>
        <th width="20"><?php echo JText::_('ID'); ?></th>
        <th width="80"><?php echo JText::_('ABBREVIATION'); ?></th>
        <th><?php echo JText::_('PROPOSITION'); ?></th>
            </thead>
        <tbody>
            <?php 
                $i = 1;
                foreach ($this->propositions1 as $proposition1) :
            ?>
        <tr>
            <td><?php echo $proposition1->proposition_id; ?></td>
            <td><?php echo 'Q1-P'.$i; $i++; ?></td>
            <td><?php echo $proposition1->proposition; ?></td>
        </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
            </fieldset>
        </td>
        <td valign="top">
<fieldset>
    <legend><?php echo JText::_('QUESTION').' 2'; ?></legend>
    <table class="adminlist">
        <thead>
        <th width="20"><?php echo JText::_('ID'); ?></th>
        <th><?php echo JText::_('STATEMENT'); ?></th>
        <th width="100"><?php echo JText::_('TYPE'); ?></th>
        <th width="100"><?php echo JText::_('NATURE'); ?></th>
        </thead>
        <tbody>
        <tr>
            <td><?php echo $this->question2->id; ?></td>
            <td><?php echo $this->question2->statement; ?></td>
            <td><?php echo JText::_($this->question2->type_id); ?></td>
            <td><?php echo ( ! $this->question2->nature) ? JText::_('QUALITATIVE') : JText::_('QUANTITATIVE'); ?></td>
        </tr>
        </tbody>
    </table>
</fieldset>
            <fieldset>
                <legend><?php echo JText::_('PROPOSITIONS'); ?></legend>
            <table class="adminlist">
        <thead>
        <th width="20"><?php echo JText::_('ID'); ?></th>
        <th width="80"><?php echo JText::_('ABBREVIATION'); ?></th>
        <th><?php echo JText::_('PROPOSITION'); ?></th>
            </thead>
        <tbody>
            <?php
                $i = 1;
                foreach ($this->propositions2 as $proposition2) :
            ?>
        <tr>
            <td><?php echo $proposition2->proposition_id; ?></td>
            <td><?php echo 'Q2-P'.$i; $i++; ?></td>
            <td><?php echo $proposition2->proposition; ?></td>
        </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
            </fieldset>
        </td>
    </tr>
</table>

<fieldset>
        <legend><?php echo JText::_('CROSS_TAB'); ?></legend>
        <table width="100%" class="adminlist">

        <thead>
        <tr>
        <th></th>
        <?php
            $i = 1;
            foreach ($this->propositions2 as $prop2) :
        ?>
            <th><?php echo JHTML::tooltip($prop2->proposition, '', '', 'Q2-P'.$i); ?></th>
        <?php
                $i++;
            endforeach;
        ?>
        <th><?php echo JText::_('NO_ANSWER'); ?></th>
        <th><?php echo JText::_('TOTAL'); ?></th>
        </tr>
        </thead>
        <?php
        $i = 1;
        foreach ($this->propositions1 as $prop1) :
        ?>
            <tr>
            <th><?php echo JHTML::tooltip($prop1->proposition, '', '', 'Q1-P'.$i); ?></th>
            <?php
                $i++;
                foreach ($this->propositions2 as $prop2):
            ?>
                <td><?php echo $sampleSize[$prop1->proposition_id][$prop2->proposition_id]; ?></td>
            <?php
                endforeach;
            ?>
            <td><?php echo $allAnswered1[$prop1->proposition_id] - $answered1[$prop1->proposition_id]; ?></td>
            <td><?php echo $allAnswered1[$prop1->proposition_id] ; ?></td>
            </tr>
            <?php
        endforeach;
        ?>
        <tr>
        <th><?php echo JText::_('NO_ANSWER'); ?></th>
        <?php
            foreach ($this->propositions2 as $prop2) :
        ?>
            <td><?php echo $allAnswered2[$prop2->proposition_id] - $answered2[$prop2->proposition_id]; ?></td>
        <?php
            endforeach;
        ?>
            <td><?php echo $this->nbrNANA; ?></td>
            <td><?php
                        $sumTot = 0;
                        foreach ($this->propositions2 as $prop2) {
                            $sumTot += $allAnswered2[$prop2->proposition_id] - $answered2[$prop2->proposition_id];
                        }
                        $sumTot += $this->nbrNANA;
                        echo $sumTot;
                ?></td>
        </tr>
        <tr>
            <th><?php echo JText::_('TOTAL'); ?></th>
            <?php
            foreach ($this->propositions2 as $prop2) :
        ?>
            <td><?php echo $allAnswered2[$prop2->proposition_id]; ?></td>
        <?php
            endforeach;
        ?>
            <td>
                <?php
                        $sumTot = 0;
                        foreach ($this->propositions1 as $prop1) {
                            $sumTot += $allAnswered1[$prop1->proposition_id] - $answered1[$prop1->proposition_id];
                        }
                        $sumTot += $this->nbrNANA;
                        echo $sumTot;
                ?>
            </td>
            <td><?php echo $this->survey->population; ?></td>
        </tr>
            </table>
</fieldset>
