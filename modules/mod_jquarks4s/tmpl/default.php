<?php
    defined('_JEXEC') or die('Restricted access');
?>
<style>
	.jquarks_mod ul {list-style: none;}
	.jquarks_mod img {vertical-align: bottom; }
	.view_all {margin-left: auto; width: 90%; display: block; text-align: right; }
</style>
<div>
<?php
    if ($publicSurveys || $privateSurveys) :
        $nbrMaxSurveys = $params->get('nbrMaxSurveys', 1) ;
        if (count($publicSurveys)) :
?>
<h4><?php echo JText::_('SURVEYS') ; ?></h4>
<ul>
<?php
        for ($i = 0 ; $i < $nbrMaxSurveys && $i < count($publicSurveys) ; $i++ ) :
        $link = JRoute::_('index.php?option=com_jquarks4s&controller=survey&id=' . $publicSurveys[$i]->id) ;
?>
    <li>
        <a href="<?php echo $link ; ?>"><?php echo $publicSurveys[$i]->title ; ?></a>
    </li>
<?php
        endfor;
?>
</ul>
<?php
        endif ;
    	if (count($privateSurveys)) :
?>
<h4><?php echo JText::_('PRIVATE_SURVEYS') ; ?></h4>
<ul>
<?php
        for ($i = 0 ; $i < $nbrMaxSurveys && $i < count($privateSurveys) ; $i++ ) :
            $link = JRoute::_('index.php?option=com_jquarks4s&controller=survey&id=' . $privateSurveys[$i]->id) ;
?>
    <li>
        <a href="<?php echo $link ; ?>"><?php echo $privateSurveys[$i]->title ; ?></a>
	</li>
<?php
        endfor;
?>
</ul>

<?php
        endif ;
        if ($nbrMaxSurveys < count($publicSurveys) || $nbrMaxSurveys < count($privateSurveys)) :
?>
<a href="index.php?option=com_jquarks4s&controller=surveys" class="view_all"><?php echo JText::_('VIEW_ALL') ; ?></a>
<?php
        endif ;
	endif ;
?>
</div>