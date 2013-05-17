<?php 
    defined('_JEXEC') or die('Restricted access');

    JHTML::_('behavior.tooltip');
?>

<table width="100%">
    <tr>
        <td width="35%">
<a href="http://www.jquarks.org/" title="JQuarks">
    <img src="<?php echo JURI::root() ; ?>administrator/components/com_jquarks4s/assets/images/jquarks4s_logo.png"
         alt="JQuarks4s Logo" title="JQuarks4s">
</a>
<br />
<h2><?php echo 'version '.JQUARKS4S_VERSION; ?></h2>
<ul>
    <li>Create your questions and place them into sections</li>
    <li>Lay out your survey and arrange its sections</li>
    <li>Visualize stats, charts and Save Snapshots</li>
    <li>Export answers in data tables to easy processsing in MS Excel</li>
</ul>

<p>
    Feel free to report to our <a href="http://www.iptechinside.com/labs/projects/jquarks-for-surveys/boards">forum</a> for any bug, question or feature request you may have !
</p>

<p>
    <strong>JQuarks4S is powered by</strong><br />
    <a href="http://www.iptechinside.com/labs/projects/show/jquarks-for-surveys" title="IP-Tech"><img src="<?php echo JURI::root() ; ?>administrator/components/com_jquarks4s/assets/images/iptech.png" alt="IP-Tech Logo" title="IP-Tech"></a>
</p>
</td>
<td width="265px">
    <fieldset>
       
        <table class="adminfrom">
        <tr>
            <td>
                
<?php
jimport('joomla.html.toolbar');

$mainframe->addCustomHeadTag('<link rel="stylesheet" href="'.$this->baseurl.'/components/com_jquarks4s/assets/css/toolbar.css" type="text/css" media="screen" />');

$bar1 = new JToolBar( 'toolbarTop' );
// appendButton method parameters
        // 1- button type from JButton
        // 2- css class - image of the button
        // 3- text to display on the button
        // 4- the task to set
        // 5- whether a selection must be made from an admin list before continuing.
$bar1->appendButton( 'link', 'question', JText::_('NEW_QUESTION'), 
        'index.php?option=com_jquarks4s&controller=questions&task=edit&cid[]=0', false );
$bar1->appendButton( 'link', 'section',  JText::_('NEW_SECTION'),  
        'index.php?option=com_jquarks4s&controller=sections&task=edit&cid[]=0', false );
$bar1->appendButton( 'link', 'survey',   JText::_('NEW_SURVEY'),   
        'index.php?option=com_jquarks4s&controller=surveys&task=edit&cid[]=0', false );
echo $bar1->render();
?>

            </td>
        </tr>
        <tr>
            <td>
<?php

$bar2 = new JToolBar( 'toolbarBottom' );
$bar2->appendButton( 'link', 'session', JText::_('ANSWERS'),
        'index.php?option=com_jquarks4s&controller=sessions', false );
$bar2->appendButton( 'link', 'chart',  JText::_('MAKE_ANALYSIS'),
        'index.php?option=com_jquarks4s&controller=analysis&task=makeAnalysis', false );
$bar2->appendButton( 'link', 'export',   JText::_('EXPORT_ANSWERS'),
        'index.php?option=com_jquarks4s&controller=datamanager&task=export&export_type=1', false );
echo $bar2->render();
?>
                
            </td>
            
        </tr>
    </table>
        
    </fieldset>
</td>
<td >
        <?php

        jimport('joomla.html.pane');

        $pane =& JPane::getInstance('sliders', array('startOffset' => 0, 'startTransition' => 0));
        echo $pane->startPane( 'surveyParamPane' );

        echo $pane->startPanel( JText::_('LAST_SURVEYS'), 'lastSurveys' );
        echo '<table class="adminlist">';
        echo '<thead><tr>';
        echo '<th>'.JText::_('SURVEY').'</th><th width="20%"># '.JText::_('QUESTIONS').'</th><th width="20%">'.JText::_('SAMPLE_SIZE').'</th>';
        echo '</thead>';
        echo '<tbody>';
        if (count($this->lastSurveys) == 0) {
                echo '<tr><td colspan="3">' . JText::_('THERE_ARE_NO_RECORDS') . '</td></tr>' ;
        }
        else
        {
            foreach ($this->lastSurveys AS $survey)
            {
                $linkSurvey = JRoute::_( 'index.php?option=com_jquarks4s&controller=surveys&task=edit&cid[]=' . $survey->id );
                echo '<tr>';
                echo '<td><a href="'.$linkSurvey.'">'.$survey->title.'</a></td>';
                echo '<td>'.$survey->nbr_questions.'</td>';
                echo '<td>'.$survey->population.'</td>';
                echo '</tr>';
            }
        }
        echo '</tbody>';
        echo '</table>';
        echo $pane->endPanel();

        echo $pane->startPanel( JText::_('LAST_ANALYSIS'), 'lastAnalysis' );
        echo '<table class="adminlist">';
        echo '<thead><tr>';
        echo '<th>'.JText::_('ANALYSIS').'</th>';
        echo '<th width="20%">'.JText::_('TYPE').'</th>';
        echo '<th width="30%">'.JText::_('SAVE_DATE').'</th>';
        echo '</tr></thead>';

        echo '<tbody>';
        if (count($this->lastAnalysis) == 0) {
                echo '<tr><td colspan="3">' . JText::_('THERE_ARE_NO_RECORDS') . '</td></tr>' ;
        }
        else
        {
            foreach ($this->lastAnalysis AS $analysis)
            {
                $linkAnalysis = JRoute::_( 'index.php?option=com_jquarks4s&controller=analysis&task=viewAnalysis&cid[]=' . $analysis->id );

                echo '<tr>';
                echo '<td><a href="'.$linkAnalysis.'" >'. JHTML::tooltip($analysis->description, $analysis->name, '', $analysis->name) .'</a></td>';
                echo '<td>'.JText::_($analysis->type).'</td>';
                echo '<td>'.$analysis->save_date.'</td>';
                echo '</tr>';
            }
        }
        echo '</tbody>';
        echo '</table>';
        echo $pane->endPanel();

        
        echo $pane->startPanel( JText::_('LAST_SESSIONS'), 'lastSessions' );
        echo '<table class="adminlist">';
        echo '<thead><tr>';
        echo '<th width="20%">'.JText::_('VIEW_ANSWERS').'</th>';
        echo '<th width="30%">'.JText::_('USER').'</th>';
        echo '<th width="30%">'.JText::_('SUBMIT_DATE').'</th>';
        echo '<th width="20%">'.JText::_('IP_ADDRESS').'</th>';
        echo '</tr></thead>';

        echo '<tbody>';
        if (count($this->lastSessions) == 0) {
                echo '<tr><td colspan="4">' . JText::_('THERE_ARE_NO_RECORDS') . '</td></tr>' ;
        }
        else
        {
            foreach ($this->lastSessions AS $session)
            {
                $linkSession = JRoute::_( 'index.php?option=com_jquarks4s&controller=sessions&task=viewSession&id='. $session->id . '&cid[]=' . $session->survey_id );

                echo '<tr>';
                echo '<td><a href="'.$linkSession.'" >'.JText::_('VIEW_ANSWERS').'</a></td>';
                echo '<td>';
                echo ( ! is_null($session->user_name)) ? $session->user_name : JText::_('ANONYMOUS');
                echo '</td>';
                echo '<td>'.$session->submit_date.'</td>';
                echo '<td>'.$session->ip_address.'</td>';
                echo '</tr>';
            }
        }
        echo '</tbody>';

        echo '</table>';
        echo $pane->endPanel();

        echo $pane->endPane();

        ?>
</td>
</tr>
</table>
