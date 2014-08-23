<?php

define('VIDEO_FOR_APPROVAL',		  	0);
define('VIDEO_STATUS_TRANSLATING',  	1);
define('VIDEO_STATUS_SYNCHRONIZING',  	2);
define('VIDEO_STATUS_PROOFREADING',  	3);
define('VIDEO_STATUS_FINISHED',  		4);
define('VIDEO_STATUS_REJECTED',  		5);

define('VIDEO_STATUS_LABEL', serialize(array('Need to be approved',
										     'Open for translations', 
										     'Synchronizing',									   
										     'Open for proofreading',
										     'Finished',
										     'Rejected')));

define('USER_NOT_AUTHORIZED',  	0);
define('USER_AUTH_OPERATOR',  	1);
define('USER_AUTH_ADMIN',  		2);
define('USER_AUTH_SUPERADMIN',  3);

define('TASK_SUGGESTED_VIDEO', 	0); // VIDEO_FOR_APPROVAL
define('TASK_IS_TRANSLATING', 	1); // VIDEO_STATUS_TRANSLATING
define('TASK_IS_SYNCHRONIZING', 2); // VIDEO_STATUS_SYNCHRONIZING
define('TASK_IS_PROOFREADING', 	3); // VIDEO_STATUS_PROOFREADING
define('TASK_IS_FINISHED', 	    4); // VIDEO_STATUS_FINISHED

define('TASK_REJECTED_VIDEO', 	5); // VIDEO_STATUS_REJECTED

define('TASK_APPROVED_VIDEO', 	6);

define('TASK_ADVANCE_TO_SYNC', 	7);
define('TASK_ADVANCE_TO_PROOF', 8);
define('TASK_FINISH_VIDEO', 	9);

define('TASK_BACK_TO_TRANS', 	10);
define('TASK_BACK_TO_SYNC', 	11);
define('TASK_BACK_TO_PROOF', 	12);


define('IMG_VIDEO_STATUS', serialize(array('menu-icon fa fa-star', 
										   'menu-icon fa fa-text-width',										   
										   'menu-icon fa fa-clock-o',
										   'menu-icon fa fa-eye',
										   'menu-icon fa fa-define',
										   'menu-icon fa fa-thumbs-down',
										   'menu-icon fa fa-thumbs-up')));
