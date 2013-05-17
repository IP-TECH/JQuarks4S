<?php
    defined('_JEXEC') or die('Restricted access');
?>

<?php
        // including JS charts with flot plugin for jquery
        global $mainframe;
        
        $jq   = JRoute::_("components/com_jquarks4s/assets/js/flot/jquery.js");
        $flot = JRoute::_("components/com_jquarks4s/assets/js/flot/jquery.flot.js");
        $pie  = JRoute::_("components/com_jquarks4s/assets/js/flot/jquery.flot.pie.js");
        $exc  = JRoute::_("components/com_jquarks4s/assets/js/flot/excanvas.js");

        $tag = '<script src="'.$jq.'" type="text/javascript"></script>'.
        '<script src="'.$flot.'" type="text/javascript"></script>'.
        '<script src="'.$pie.'" type="text/javascript"></script>'.
        '<!--[if IE]><script language="javascript" type="text/javascript" src="'.$exc.'"></script><![endif]-->';

        $mainframe->addCustomHeadTag($tag);
?>

<?php
        // calcul des valeurs pour une variable quantitative
        if ($this->question->nature)
        {
            $moyenne;
        }
?>
<?php
        // sizes calculation
        $propositionSampleSize = array();
        foreach ($this->propositions AS $proposition)
        {
            $propositionSampleSize[$proposition->proposition_id] = 0;
            foreach ($this->sessions AS $session)
            {
                if ($session->answer_id == $proposition->proposition_id) {
                    $propositionSampleSize[$proposition->proposition_id] ++;
                }
            }
            reset($this->sessions);
        }
        reset($this->propositions);

        // calcul du mode - NOT USED FOR THE MOMENT
        $mode = 0;
        $mode_id = null;
        foreach ($propositionSampleSize as $key => $prop)
        {
            if ($prop >= $mode) {
                $mode = $prop;
                $mode_id = $key;
            }
        }
?>

<!-- DISPLAYING SURVEY STATS -->
<fieldset>
    <legend><?php echo JText::_('SURVEY'); ?></legend>
    <table class="adminlist">
        <thead>
        <th width="20"><?php echo JText::_('ID'); ?></th>
        <th><?php echo JText::_('TITLE'); ?></th>
        <th width="100"><?php echo '# '.JText::_('QUESTIONS'); ?></th>
        <th width="100"><?php echo JText::_('SAMPLE_SIZE'); ?></th>
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

<!-- PREPARING SURVEY STATS FOR SAVE TO SESSION -->
<?php
        $surveyRegister = array();

        $surveyRegister['id']            = $this->survey->id;
        $surveyRegister['title']         = $this->survey->title;
        $surveyRegister['nbr_questions'] = $this->survey->nbr_questions;
        $surveyRegister['nbr_sessions']  = $this->survey->population;
?>


<!-- DISPLAYING QUESTION STATS -->
<fieldset>
    <legend><?php echo JText::_('QUESTION'); ?></legend>
    <table class="adminlist">
        <thead>
        <th width="20"><?php echo JText::_('ID'); ?></th>
        <th><?php echo JText::_('STATEMENT'); ?></th>
        <th width="100"><?php echo JText::_('TYPE'); ?></th>
        <th width="100"><?php echo JText::_('NATURE'); ?></th>
        </thead>
        <tbody>
        <tr>
            <td><?php echo $this->question->id; ?></td>
                    
            <td><?php echo $this->question->statement.'<br />'.$this->row_title; ?></td>
                    
            <td><?php echo JText::_($this->question->type_id); ?></td>
                    
            <td><?php echo ( ! $this->question->nature) ? JText::_('QUALITATIVE') : JText::_('QUANTITATIVE'); ?></td>
                    
        </tr>
        </tbody>
    </table>
</fieldset>

<!-- PREPARING QUESTION STATS FOR SAVE TO SESSION -->
<?php
        $questionRegister = array();

        $questionRegister['id']        = $this->question->id;
        $questionRegister['statement'] = $this->question->statement.'<br />'.$this->row_title;
        $questionRegister['type_id']   = $this->question->type;
        $questionRegister['nature']    = $this->question->nature;
?>

<!-- DISPLAYING STATS FOR ONLY QUANTITATIVE QUESTION -->
<?php if ($this->question->nature): ?>
<fieldset>
    <legend><?php echo JText::_('VALUES'); ?></legend>
</fieldset>
<?php endif; ?>


<!-- DISPLAYING PROPOSITIONS STATS -->

<fieldset>
    <legend><?php echo JText::_('PROPOSITIONS'); ?></legend>
    <table class="adminlist">
        <thead>
        <th><?php echo JText::_('PROPOSITION'); ?></th>
        <th width="158px"><?php echo JText::_('FREQUENCY'); ?></th>
        <th width="77px"><?php echo JText::_('SIZE'); ?></th>
    </thead>
    <tbody>

        <?php $propositionRegister = array(); ?>
        
        <?php
            $cumulatedSampleSize = 0;
            $regIndex = 0;
            foreach ($this->propositions AS $proposition):
                    $frequency = ($propositionSampleSize[$proposition->proposition_id] / $this->survey->population)*100;
        ?>
        <?php // PREPARING EACH PROPOSITION STAT FOR SAVE
            $propositionRegister[$regIndex]['id']          = $proposition->proposition_id;
            $propositionRegister[$regIndex]['proposition'] = $proposition->proposition;
            $propositionRegister[$regIndex]['frequency']   = round($frequency, 2);
            $propositionRegister[$regIndex]['sample_size'] = $propositionSampleSize[$proposition->proposition_id];
        ?>
        <tr>
            <td><?php echo $proposition->proposition; ?></td>
            <td><?php echo round($frequency, 2).' %'; ?></td>
            <td><?php echo $propositionSampleSize[$proposition->proposition_id]; ?></td>
        </tr>
        <?php
                    $cumulatedSampleSize += $propositionSampleSize[$proposition->proposition_id];
                    $regIndex++;
                endforeach;

                $noAnswerSampleSize = ($this->survey->population - $cumulatedSampleSize);
                $noAnswerFrequency = ($noAnswerSampleSize / $this->survey->population)*100;
        ?>
        
        <!-- DISPLAYING NO_ANSWER PROPOSITION -->
        <tr>
            <th><?php echo JText::_('NO_ANSWER'); ?></th>
            <td><?php echo round($noAnswerFrequency, 2).' %'; ?></td>
            <td><?php echo $noAnswerSampleSize; ?></td>
        </tr>
        <?php // PREPARING NO_ANSWER PROPOSITION STATS FOR SAVE
            $propositionRegister[$regIndex]['id']          = -1;
            $propositionRegister[$regIndex]['proposition'] = 'no_answer';
            $propositionRegister[$regIndex]['frequency']   = round($noAnswerFrequency, 2);
            $propositionRegister[$regIndex]['sample_size'] = $noAnswerSampleSize;
        ?>
        
        <tr>
            <th><?php echo JText::_('TOTAL'); ?></th>
            <td><?php echo '100 %'; ?></td>
            <td><?php echo $this->survey->population; ?></td>
        </tr>
    </tbody>
    </table>
</fieldset>

        
<!-- DISPLAYING JAVASCRIPT CHART -->
<fieldset>
    <legend><?php echo JText::_('CHART'); ?></legend>
    
    <!-- CHART CONTAINER -->
    
    <div align="center" id="chartplaceholder" style="width: 500px; height:400px; margin-left:auto; margin-right:auto;"></div>
<script language="javascript" type="text/javascript">
jQuery.noConflict();

// Put all your code in your document ready area
jQuery(document).ready(function($){
// Do jQuery stuff using $


$(function () {

var nature = <?php echo $this->question->nature; ?>;

if (nature == 1) {

var data = {
    "data":{
        "metrics":[
            {
                "label":"",
                "data":[

            <?php
            $i = 1;
            foreach ($this->propositions as $proposition)
            {
                $frequency = ($propositionSampleSize[$proposition->proposition_id] / $this->survey->population)*100;
                echo '['.$i.', '.round($frequency, 2).'],';
                $i++;
            }
            $noAnswerSampleSize = ($this->survey->population - $cumulatedSampleSize);
            $noAnswerFrequency = ($noAnswerSampleSize / $this->survey->population)*100;
            echo '['.$i.', '.round($noAnswerFrequency, 2).']';
            

            ?>

                ]
            }
        ],
        "ticks":[
            <?php
                $i = 1;
                foreach ($this->propositions as $prop)
                {
                    echo '['.$i.', "'.substr($prop->proposition, 0, 20).'"],';
                    $i++;
                }
                echo '['.$i.', "'.JText::_('NO_ANSWER').'"]';
            ?>
        ]
    }
};

            $.plot($("#chartplaceholder"), data.data.metrics, {
			bars: {show: true, autoScale: true, fillOpacity: 1, barWidth: 0.7 },
            //legend : {margin: 20},
			xaxis: {
				ticks: data.data.ticks
                
			}
		});

} // end if
else { // PIE

    var data = [];
    var series = 100;

<?php // RAW DATA for chart
    $i = 0;
    foreach($this->propositions as $prop) {
        echo 'data['.$i.'] = { label: "'.substr($prop->proposition, 0, 25).'",  data: '.$propositionSampleSize[$prop->proposition_id].'},';
        $i++;
    }
    echo 'data['.$i.'] = { label: "'.JText::_('NO_ANSWER').'",  data: '.$noAnswerSampleSize.'}';
?>

    $.plot($("#chartplaceholder"), data,
	{
        series: {
            pie: {
                show: true,
                radius: 3/4,
                label: {
                    show: true,
                    radius: 3/4,
                    formatter: function(label, series){
                        return '<div style="font-size:8pt;text-align:center;padding:2px;color:white;">'+label+'<br/>'+Math.round(series.percent)+'%</div>';
                    },
                    background: {
                        opacity: 0.5,
                        color: '#000'
                    }
                }
            },
            legend: {
                show: false
            }
        }
    });
           
} // end else

});


}); // end jquery
</script>

</fieldset>

<!-- /END HTML TEMPLATE -->

<!-- SAVING ANALYSIS STATS TO SESSION -->
<?php
    // store stats to session
    $currentRegister = array();
    $currentRegister['survey']        = $surveyRegister;
    $currentRegister['question']      = $questionRegister;
    $currentRegister['propositions']  = $propositionRegister;
    $currentRegister['analysis_type'] = 1;

    $currentSession = &JFactory::getSession();
    $currentSession->set('analysis', $currentRegister);


?>
