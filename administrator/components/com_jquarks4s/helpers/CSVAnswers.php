<?php
defined('_JEXEC') or die('Restricted access');

require_once (JPATH_COMPONENT . DS . 'helpers' . DS . 'csv.php');

class Jquarks4sCsvAnswersHelper
{
    /**
     *
     * @param array selected questions
     */
    function addHeader($questions)
    {
        $nbrQuestions = count($questions);

        // session header
        Jquarks4sCsvHelper::addValue('survey_id');
        Jquarks4sCsvHelper::addValue('session_id');
        Jquarks4sCsvHelper::addValue('submit_date');
        Jquarks4sCsvHelper::addValue('ip_address');
        Jquarks4sCsvHelper::addValue('user_name');
        Jquarks4sCsvHelper::addValue('user_id');

        // questions
        for ($i = 0; $i < $nbrQuestions; $i++)
        {
            Jquarks4sCsvHelper::addValue('question_alias');
            Jquarks4sCsvHelper::addValue('question_id');
            Jquarks4sCsvHelper::addValue('question_type_id');
            Jquarks4sCsvHelper::addValue('answer');
            Jquarks4sCsvHelper::addValue('altanswer');
            Jquarks4sCsvHelper::addValue('answer_id');
            Jquarks4sCsvHelper::addValue('row_title');
            Jquarks4sCsvHelper::addValue('row_id');
        }
    }


    function addSession($answer)
    {
        Jquarks4sCsvHelper::addValue($answer->survey_id);
        Jquarks4sCsvHelper::addValue($answer->session_id);
        Jquarks4sCsvHelper::addValue($answer->submit_date);
        Jquarks4sCsvHelper::addValue($answer->ip_address);
        Jquarks4sCsvHelper::addValue($answer->user_name);
        Jquarks4sCsvHelper::addValue($answer->user_id);
    }

    
    function addAnswer($answer)
    {
        Jquarks4sCsvHelper::addValue($answer->question_alias);
        Jquarks4sCsvHelper::addValue($answer->question_id);
        Jquarks4sCsvHelper::addValue($answer->question_type_id);
        Jquarks4sCsvHelper::addValue($answer->answer);
        Jquarks4sCsvHelper::addValue($answer->altanswer);
        Jquarks4sCsvHelper::addValue($answer->answer_id);
        Jquarks4sCsvHelper::addValue($answer->row_title);
        Jquarks4sCsvHelper::addValue($answer->row_id);
    }


   /**
     *
     * @param array $answers
     * @param array $questions
     * @param array $sessions
     */
    function write($answers, $questions, $sessions)
    {
        // unset $_content
        Jquarks4sCsvHelper::initContent();

        // write CSV header
        self::addHeader($questions);

        // write lines
        $current_s_id = null;
        foreach ($answers AS $answer)
        {
            if ( in_array($answer->session_id, $sessions) )
            {
                if ($answer->session_id != $current_s_id)
                {
                    Jquarks4sCsvHelper::endLine();
                    self::addSession($answer);
                    $current_s_id = $answer->session_id;
                }
                if ( in_array($answer->question_id, $questions) ){
                    self::addAnswer($answer);
                }
            }

        }

    }


    function getContent()
    {
        return Jquarks4sCsvHelper::getContent();
    }

}
