<?php

define('VIDEO_FOR_APPROVAL',		  	 0);
define('VIDEO_STATUS_TRANSLATING',   1);
define('VIDEO_STATUS_SYNCHRONIZING', 2);
define('VIDEO_STATUS_PROOFREADING',  3);
define('VIDEO_STATUS_FINISHED',  		 4);
define('VIDEO_STATUS_REJECTED',  		 5);

define('VIDEO_STATUS_LABEL', serialize(array('Need to be approved',
										     'Open for translations', 
										     'Synchronizing',									   
										     'Open for proofreading',
										     'Finished',
										     'Rejected')));

define('USER_NOT_AUTHORIZED',  	0);
define('USER_AUTH_OPERATOR',  	1);
define('USER_AUTH_ADMIN',  		  2);
define('USER_AUTH_OWNER',       3);

define('TASK_SUGGESTED_VIDEO', 	0); // VIDEO_FOR_APPROVAL
define('TASK_IS_TRANSLATING', 	1); // VIDEO_STATUS_TRANSLATING
define('TASK_IS_SYNCHRONIZING', 2); // VIDEO_STATUS_SYNCHRONIZING
define('TASK_IS_PROOFREADING', 	3); // VIDEO_STATUS_PROOFREADING
define('TASK_IS_FINISHED', 	    4); // VIDEO_STATUS_FINISHED

define('TASK_REJECTED_VIDEO', 	5); // VIDEO_STATUS_REJECTED
define('TASK_APPROVED_VIDEO', 	6);

define('TASK_ADVANCE_TO_SYNC', 	7);
define('TASK_ADVANCE_TO_PROOF', 8);
define('TASK_FINISHED_VIDEO',   9);

define('TASK_BACK_TO_TRANS', 	10);
define('TASK_BACK_TO_SYNC', 	11);
define('TASK_BACK_TO_PROOF', 	12);

define('TASKS_TYPE_LABEL', serialize(
                              array('suggested the video.',
                                    'is translating.',
                                    'is helping to sync.',
                                    'is proofreading the video.',
                                    'finished the video.',
                                    'rejected the video',
                                    'approved the video.',
                                    'move the video to synchronization.',
                                    'move the video to proofreading.',
                                    'finish the video.',
                                    'return the video to translating.',
                                    'return the video to synchronization',
                                    'return the video to proofreading')));

define('TASKS_TYPE_LABEL_DASHBOARD', serialize(
                                       array('suggested',
                                             'is translating',
                                             'is helping to sync',
                                             'is proofreading',
                                             'finished',
                                             'rejected',
                                             'approved',
                                             'moved to synchronization',
                                             'moved to proofreading',
                                             'finished',
                                             'returned to translating',
                                             'returned to synchronization',
                                             'returned to proofreading')));


define('IMG_VIDEO_STATUS', serialize(
                              array('fa-star', 
              										  'fa-text-width',										   
              										  'fa-clock-o',
              										  'fa-eye',
              										  'fa-check',
              										  'fa-thumbs-down',
              										  'fa-thumbs-up',
                                    'fa-arrow-right',
                                    'fa-arrow-right',
                                    'fa-arrow-check',
                                    'fa-arrow-left',
                                    'fa-arrow-left',
                                    'fa-arrow-left')));

define('VIDEO_MARKS', serialize(
                          array('I need some assistence!', 
                                'It need a review!',                       
                                'I do not know how to synchronize!',
                                'It deserves a better synchronization!',
                                'It needs one more review!',
                                'It needs two more reviews!',
                                'I believe it is done!')));


define('VIDEO_MARK_I_NEED_SOME_ASSISTENCE',   0);




define('USER_SCORE_TRANSLATED',   0);
define('USER_SCORE_SYNCHRONIZED', 1);
define('USER_SCORE_PROOFREADED',  2);
define('USER_SCORE_SUGGESTED',    3);
define('USER_SCORE_OPENED',       4);
define('USER_SCORE_WORKED_IN',    5);
define('USER_SCORE_TOTAL',        6);

define('SCORE_TRANSLATED',        30);
define('SCORE_SYNCHRONIZED',      20);
define('SCORE_PROOFREADED',       15);



function time_elapsed_string($datetime, $full = false) 
{
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
        'y' => 'year',
        'm' => 'month',
        'w' => 'week',
        'd' => 'day',
        'h' => 'hour',
        'i' => 'minute',
        's' => 'second',
    );
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' ago' : 'just now';
}

function format_json($json, $html = false, $tabspaces = null)
{
    $tabcount = 0;
    $result = '';
    $inquote = false;
    $ignorenext = false;

    if ($html) {
        $tab = str_repeat("&nbsp;", ($tabspaces == null ? 4 : $tabspaces));
        $newline = "<br/>";
    } else {
        $tab = ($tabspaces == null ? "\t" : str_repeat(" ", $tabspaces));
        $newline = "\n";
    }

    for($i = 0; $i < strlen($json); $i++) {
        $char = $json[$i];

        if ($ignorenext) {
            $result .= $char;
            $ignorenext = false;
        } else {
            switch($char) {
                case ':':
                    $result .= $char . (!$inquote ? " " : "");
                    break;
                case '{':
                    if (!$inquote) {
                        $tabcount++;
                        $result .= $char . $newline . str_repeat($tab, $tabcount);
                    }
                    else {
                        $result .= $char;
                    }
                    break;
                case '}':
                    if (!$inquote) {
                        $tabcount--;
                        $result = trim($result) . $newline . str_repeat($tab, $tabcount) . $char;
                    }
                    else {
                        $result .= $char;
                    }
                    break;
                case ',':
                    if (!$inquote) {
                        $result .= $char . $newline . str_repeat($tab, $tabcount);
                    }
                    else {
                        $result .= $char;
                    }
                    break;
                case '"':
                    $inquote = !$inquote;
                    $result .= $char;
                    break;
                case '\\':
                    if ($inquote) $ignorenext = true;
                    $result .= $char;
                    break;
                default:
                    $result .= $char;
            }
        }
    }

    return $result;
}