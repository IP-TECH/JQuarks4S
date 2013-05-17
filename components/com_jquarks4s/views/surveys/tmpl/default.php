<?php defined('_JEXEC') or die('restricted access'); ?>
<style type="text/css" >
    .jq4s_powered_by {
        text-align: right;
    }
</style>
<div class="contentheading"><?php echo JText::_('PUBLIC_SURVEYS'); ?></div>
<ul>
    <?php foreach ($this->public_surveys as $survey): ?>
    <li>
        <a href="index.php?option=com_jquarks4s&controller=survey&id=<?php echo $survey->id ?>"><?php echo $survey->title ?></a>
    </li>
    <?php endforeach; ?>
</ul>

<?php if (count($this->private_surveys)):  ?>
<div class="contentheading"><?php echo JText::_('PRIVATE_SURVEYS'); ?></div>
<?php endif; ?>
<ul>
    <?php foreach ($this->private_surveys as $survey): ?>
    <li class="jq4s_survey_li">
        <a href="index.php?option=com_jquarks4s&controller=survey&id=<?php echo $survey->id ?>"><?php echo $survey->title ?></a>
    </li>
    <?php endforeach; ?>
</ul>
<div class="jq4s_powered_by">
    <a href="http://www.iptechinside.com/labs/projects/show/jquarks-for-surveys"><strong>powered by JQuarks4S</strong></a>
</div>