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
|	https://codeigniter.com/userguide3/general/routing.html
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

// Default controller - redirect to a default username or show instructions
$route['default_controller'] = 'gift/index';

// Assets route
$route['assets/(:any)'] = 'assets/$1';

// 404 override
$route['404_override'] = 'gift/not_found';

// Enable URI dashes translation
$route['translate_uri_dashes'] = FALSE;

// Admin Routes - These must come BEFORE the API routes
$route['admin'] = 'admin/index';
$route['admin/login'] = 'admin/login';
$route['admin/logout'] = 'admin/logout';
$route['admin/users'] = 'admin/users';
$route['admin/users/add'] = 'admin/add_user';
$route['admin/users/edit/(:num)'] = 'admin/edit_user/$1';
$route['admin/users/delete/(:num)'] = 'admin/delete_user/$1';
$route['admin/gifts'] = 'admin/gifts';
$route['admin/gifts/add'] = 'admin/add_gift';
$route['admin/gifts/edit/(:num)'] = 'admin/edit_gift/$1';
$route['admin/gifts/delete/(:num)'] = 'admin/delete_gift/$1';
$route['admin/gifts/reset/(:num)'] = 'admin/reset_gift/$1';

// API Routes - These must come BEFORE the catch-all username route
$route['book'] = 'gift/book';
$route['confirm_purchase'] = 'gift/confirm_purchase';
$route['cancel_booking'] = 'gift/cancel_booking';
$route['get_details'] = 'gift/get_details';
$route['get_gifts'] = 'gift/get_gifts';

// Catch-all route for usernames - This must be the LAST route
// It will match any single segment that's not a controller method
$route['(:any)'] = 'gift/index/$1';
