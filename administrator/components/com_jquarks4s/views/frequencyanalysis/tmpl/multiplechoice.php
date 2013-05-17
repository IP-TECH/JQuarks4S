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

            <td><?php echo $this->question->statement ?></td>

            <td><?php echo JText::_($this->question->type_id); ?></td>

            <td><?php echo ( ! $this->question->nature) ? JText::_('QUALITATIVE') : JText::_('QUANTITATIVE'); ?></td>

        </tr>
        </tbody>
    </table>
</fieldset>

<!-- DISPLAYING PROPOSITIONS STATS -->

<fieldset>
    <legend><?php echo JText::_('PROPOSITIONS'); ?></legend>
    <table class="adminlist">
        <thead>
            <th><?php echo JText::_('PROPOSITION'); ?></th>
            <th width="158px"><?php echo JText::_('VOTE_PERCENTAGE'); ?></th>
            <th width="77px"><?php echo '# '.JText::_('VOTES'); ?></th>
        </thead>
        <tbody>
            <?php
                $totalVotes = 0;
                foreach ($this->propositionsStats as $prop): ?>
            <tr>
                <td><?php echo $prop->proposition ?></td>
                <td><?php echo round($prop->percent, 2) . ' %' ?></td>
                <td><?php echo $prop->size; $totalVotes += (int)$prop->size; ?></td>
            </tr>
            <?php endforeach; ?>
            <tr>
                <th><?php echo JText::_('TOTAL') ?></th>
                <td>100 %</td>
                <td><?php echo $totalVotes; ?></td>
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
                foreach ($this->propositionsStats as $prop)
                {
                    echo '['.$i.', '.round($prop->percent, 2).'],';
                    $i++;
                }
            ?>

                ]
            }
        ],
        "ticks":[
            <?php
                $i = 1;
                foreach ($this->propositionsStats as $prop)
                {
                    echo '['.$i.', "'.substr($prop->proposition, 0, 20).'"],';
                    $i++;
                }
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
    foreach($this->propositionsStats as $prop)
    {
        echo 'data['.$i.'] = { label: "'.substr($prop->proposition, 0, 25).'",  data: '.$prop->size.'},';
        $i++;
    }
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
