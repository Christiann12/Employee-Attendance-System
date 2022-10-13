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
$route['default_controller'] = 'EmployeeLogin';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;


// Login route 

$route['AdminLogin'] = 'Login';
$route['adminlogin'] = 'Login';

// Admin Routes
$route['AdminDashboard'] = 'Admin/Dashboard';
$route['admindashboard'] = 'Admin/Dashboard';

$route['AdminEmployees'] = 'Admin/Employees';
$route['adminemployees'] = 'Admin/Employees';

$route['editemployee/(:any)'] = 'Admin/Employees/editEmployee/$1';
$route['EditEmployee/(:any)'] = 'Admin/Employees/editEmployee/$1';
$route['editemployee'] = 'Admin/Employees';
$route['EditEmployee'] = 'Admin/Employees';
$route['deleteemployee/(:any)'] = 'Admin/Employees/deleteEmployee/$1';
$route['DeleteEmployee/(:any)'] = 'Admin/Employees/deleteEmployee/$1';

$route['GenerateQr'] = 'Admin/GenerateQr';
$route['generateqr'] = 'Admin/GenerateQr';

$route['UserManagement'] = 'Admin/UserManagement';
$route['usermanagement'] = 'Admin/UserManagement';

$route['edituser/(:any)'] = 'Admin/UserManagement/editUser/$1';
$route['EditUser/(:any)'] = 'Admin/UserManagement/editUser/$1';
$route['edituser'] = 'Admin/UserManagement';
$route['EditUser'] = 'Admin/UserManagement';
$route['deleteuser/(:any)'] = 'Admin/UserManagement/deleteUser/$1';
$route['DeleteUser/(:any)'] = 'Admin/UserManagement/deleteUser/$1';

$route['TimeSheet'] = 'Admin/AttendanceList';
$route['timesheet'] = 'Admin/AttendanceList';

$route['UploadSched'] = 'Admin/UploadSched';
$route['uploadsched'] = 'Admin/UploadSched';

//****************************************************************************************************************************//

$route['Login'] = 'EmployeeLogin';
$route['login'] = 'EmployeeLogin';

$route['EmployeeDashboard'] = 'Employee/EmployeeDashboard';
$route['employeedashboard'] = 'Employee/EmployeeDashboard';

$route['EmployeeProfile'] = 'Employee/EmployeeProfile';
$route['employeeprofile'] = 'Employee/EmployeeProfile';

$route['EmployeeScan/(:any)'] = 'Employee/EmployeeScan/index/$1';
$route['employeescan/(:any)'] = 'Employee/EmployeeScan/index/$1';