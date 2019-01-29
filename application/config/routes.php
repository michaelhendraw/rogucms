<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'login';
$route['404_override'] = 'error';
$route['translate_uri_dashes'] = FALSE;

// Topic (Topik)
$route['subject/(:num)/topic'] = 'subject/topic_index/$1';
$route['subject/(:num)/topic/index'] = 'subject/topic_index/$1';
$route['subject/(:num)/topic/check_name'] = 'subject/topic_check_name';
$route['subject/(:num)/topic/add'] = 'subject/topic_add/$1';
$route['subject/(:num)/topic/insert'] = 'subject/topic_insert/$1';
$route['subject/(:num)/topic/edit/(:num)'] = 'subject/topic_edit/$1/$2';
$route['subject/(:num)/topic/update/(:num)'] = 'subject/topic_update/$1/$2';
$route['subject/(:num)/topic/delete/(:num)'] = 'subject/topic_delete/$1/$2';

// Material (Materi)
$route['subject/(:num)/topic/(:num)/material'] = 'subject/material_index/$1/$2';
$route['subject/(:num)/topic/(:num)/material/index'] = 'subject/material_index/$1/$2';
$route['subject/(:num)/topic/(:num)/material/check_name'] = 'subject/material_check_name/$1/$2';
$route['subject/(:num)/topic/(:num)/material/add'] = 'subject/material_add/$1/$2';
$route['subject/(:num)/topic/(:num)/material/insert'] = 'subject/material_insert/$1/$2';
$route['subject/(:num)/topic/(:num)/material/edit/(:num)'] = 'subject/material_edit/$1/$2/$3';
$route['subject/(:num)/topic/(:num)/material/update/(:num)'] = 'subject/material_update/$1/$2/$3';
$route['subject/(:num)/topic/(:num)/material/delete/(:num)'] = 'subject/material_delete/$1/$2/$3';

// Quiz (Latihan UN)
$route['subject/(:num)/quiz'] = 'subject/quiz_index/$1';
$route['subject/(:num)/quiz/index'] = 'subject/quiz_index/$1';
$route['subject/(:num)/quiz/check_name'] = 'subject/quiz_check_name';
$route['subject/(:num)/quiz/add'] = 'subject/quiz_add/$1';
$route['subject/(:num)/quiz/insert'] = 'subject/quiz_insert/$1';
$route['subject/(:num)/quiz/edit/(:num)'] = 'subject/quiz_edit/$1/$2';
$route['subject/(:num)/quiz/update/(:num)'] = 'subject/quiz_update/$1/$2';
$route['subject/(:num)/quiz/delete/(:num)'] = 'subject/quiz_delete/$1/$2';

// Class (Kelas)
$route['classes/(:num)/student'] = 'classes/student_index/$1';
$route['classes/(:num)/student/index'] = 'classes/student_index/$1';
$route['classes/(:num)/student/log/(:num)'] = 'classes/student_log/$1/$2';

$route['classes/(:num)/subject'] = 'classes/subject_index/$1';
$route['classes/(:num)/subject/index'] = 'classes/subject_index/$1';

$route['classes/(:num)/subject/(:num)/topic'] = 'classes/topic_index/$1/$2';
$route['classes/(:num)/subject/(:num)/topic/index'] = 'classes/topic_index/$1/$2';

$route['classes/(:num)/subject/(:num)/topic/(:num)/material'] = 'subject/material_index/$2/$3';
$route['classes/(:num)/subject/(:num)/topic/(:num)/discussion'] = 'classes/topic_discussion/$1/$2/$3';
$route['classes/(:num)/subject/(:num)/topic/(:num)/discussion/add/(:num)'] = 'classes/topic_discussion_add/$1/$2/$3/$4';

$route['classes/(:num)/subject/(:num)/quiz'] = 'classes/quiz_index/$1/$2';
$route['classes/(:num)/subject/(:num)/quiz/index'] = 'classes/quiz_index/$1/$2';
$route['classes/(:num)/subject/(:num)/quiz/add'] = 'classes/quiz_add/$1/$2';
$route['classes/(:num)/subject/(:num)/quiz/insert'] = 'classes/quiz_insert/$1/$2';
$route['classes/(:num)/subject/(:num)/quiz/edit/(:num)'] = 'classes/quiz_edit/$1/$2/$3';
$route['classes/(:num)/subject/(:num)/quiz/update'] = 'classes/quiz_update';
$route['classes/(:num)/subject/(:num)/quiz/result/(:num)'] = 'classes/quiz_result/$1/$2/$3';
