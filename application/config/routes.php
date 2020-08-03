<?php
defined('BASEPATH') or exit('No direct script access allowed');

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
$route['default_controller'] = 'auth';
$route['404_override'] = '404';
$route['translate_uri_dashes'] = FALSE;

$route['login'] = 'auth/login';
$route['auth']['post'] = 'auth/auth';
$route['forgot-password'] = 'auth/forgotPassword';
$route['send-password-token']['post'] = 'auth/sendPasswordToken';
$route['reset-password/(:any)'] = 'auth/resetPassword/$1';
$route['set-token-verified-password/(:any)']['post'] = 'auth/setTokenVerifiedPassword/$1';
$route['create-password/(:any)'] = 'auth/createPassword/$1';
$route['create-token-verified-password/(:any)']['post'] = 'auth/createTokenVerifiedPassword/$1';
$route['signup'] = 'auth/signup';
$route['register']['post'] = 'auth/register';
$route['verification/(:any)/(:any)'] = 'auth/verification/$1/$2';
$route['forgot-company-code'] = 'auth/forgotCompanyCode';
$route['send-company-code']['post'] = 'auth/sendCompanyCode';
$route['resend-verification-email/(:any)/(:any)']['post'] = 'auth/resendVerificationEmail/$1/$2';
$route['logout']['post'] = 'auth/logout';

$route['migrate/do_migration'] = 'migrate/do_migration';
$route['migrate/do_migration/(:num)'] = 'migrate/do_migration/$1';
$route['migrate/undo_migration'] = 'migrate/undo_migration';
$route['migrate/undo_migration/(:num)'] = 'migrate/undo_migration/$1';
$route['migrate/reset_migration'] = 'migrate/reset_migration';

$route['migrate/master/do_migration'] = 'master_migrate/do_migration';
$route['migrate/master/do_migration/(:num)'] = 'master_migrate/do_migration/$1';
$route['migrate/master/undo_migration'] = 'master_migrate/undo_migration';
$route['migrate/master/undo_migration/(:num)'] = 'master_migrate/undo_migration/$1';
$route['migrate/master/reset_migration'] = 'master_migrate/reset_migration';

$route['migrate/(:num)/do_migration'] = 'migrate_company/do_migration/$1';
$route['migrate/(:num)/do_migration/(:num)'] = 'migrate_company/do_migration/$1/$2';
$route['migrate/(:num)/undo_migration'] = 'migrate_company/undo_migration/$1';
$route['migrate/(:num)/undo_migration/(:num)'] = 'migrate_company/undo_migration/$1/$2';
$route['migrate/(:num)/reset_migration'] = 'migrate_company/reset_migration/$1';

$route['dashboard'] = 'dashboard/index';

$route['users'] = 'users/index';
$route['user/create'] = 'users/create';
$route['user/store']['post'] = 'users/store';
$route['user/(:num)'] = 'users/show/$1';
// $route['user/(:num)/edit'] = 'users/edit/$1';
$route['user/(:num)/update']['post'] = 'users/update/$1';
$route['user/(:num)/delete']['post'] = 'users/delete/$1';

$route['tasks'] = 'tasks/index';
$route['tasks/status/(:num)'] = 'tasks/status/$1';
$route['task/create'] = 'tasks/create';
$route['task/store']['post'] = 'tasks/store';
$route['task/(:num)'] = 'tasks/show/$1';
$route['task/(:num)/complete']['post'] = 'tasks/complete/$1';
$route['task/(:num)/add-note']['post'] = 'tasks/addNote/$1';
$route['task/(:num)/note/(:num)/update']['post'] = 'tasks/updateNote/$1/$2';
$route['task/(:num)/note/(:num)/delete']['post'] = 'tasks/deleteNote/$1/$2';
// $route['task/(:num)/edit'] = 'tasks/edit/$1';
$route['task/(:num)/update']['post'] = 'tasks/update/$1';
$route['task/(:num)/delete']['post'] = 'tasks/delete/$1';

$route['leads'] = 'leads/index';
$route['leads/status/(:num)'] = 'leads/status/$1';
$route['lead/create'] = 'leads/create';
$route['lead/store']['post'] = 'leads/store';
$route['lead/(:num)'] = 'leads/show/$1';
// $route['lead/(:num)/edit'] = 'leads/edit/$1';
$route['lead/(:num)/update']['post'] = 'leads/update/$1';
$route['lead/(:num)/delete']['post'] = 'leads/delete/$1';
$route['lead/(:num)/updatestatus']['post'] = 'leads/updatestatus/$1';
$route['lead/(:num)/add-material']['post'] = 'leads/addMaterial/$1';
$route['lead/(:num)/update-material/(:num)']['post'] = 'leads/updateMaterial/$2/$1';
$route['lead/(:num)/delete-material/(:num)']['post'] = 'leads/deleteMaterial/$2/$1';

// $route['lead/(:any)/(:num)/edit'] = 'leads/edit/$2/$1';
$route['lead/(:any)/(:num)/update']['post'] = 'leads/update/$2/$1';
$route['lead/(:any)/(:num)/delete']['post'] = 'leads/delete/$2/$1';
$route['lead/(:any)/(:num)/updatestatus']['post'] = 'leads/updatestatus/$2/$1';
$route['lead/(:any)/(:num)/add-team']['post'] = 'leads/addTeam/$2/$1';
$route['lead/(:any)/(:num)/remove-team']['post'] = 'leads/removeTeam/$2/$1';
$route['lead/(:any)/(:num)/add-material']['post'] = 'leads/addMaterial/$2/$1';
$route['lead/(:any)/(:num)/update-material/(:num)']['post'] = 'leads/updateMaterial/$3/$2/$1';
$route['lead/(:any)/(:num)/delete-material/(:num)']['post'] = 'leads/deleteMaterial/$3/$2/$1';

$route['lead/(:num)/notes'] = 'leads/notes/$1';
$route['lead/(:num)/add-note']['post'] = 'leads/addNote/$1';
$route['lead/(:num)/note/(:num)/update']['post'] = 'leads/updateNote/$1/$2';
$route['lead/(:num)/note/(:num)/delete']['post'] = 'leads/deleteNote/$1/$2';
$route['lead/(:num)/note/(:num)/replies'] = 'leads/replies/$1/$2';
$route['lead/(:num)/note/(:num)/reply']['post'] = 'leads/addNoteReply/$1/$2';
$route['lead/(:num)/note/(:num)/reply/(:num)/update']['post'] = 'leads/updateNoteReply/$1/$2/$3';
$route['lead/(:num)/note/(:num)/reply/(:num)/delete']['post'] = 'leads/deleteNoteReply/$1/$2/$3';

$route['lead/(:any)/(:num)/notes'] = 'leads/notes/$2/$1';
$route['lead/(:any)/(:num)/add-note']['post'] = 'leads/addNote/$2/$1';
$route['lead/(:any)/(:num)/note/(:num)/update']['post'] = 'leads/updateNote/$2/$3/$1';
$route['lead/(:any)/(:num)/note/(:num)/delete']['post'] = 'leads/deleteNote/$2/$3/$1';
$route['lead/(:any)/(:num)/note/(:num)/replies'] = 'leads/replies/$2/$3/$1';
$route['lead/(:any)/(:num)/note/(:num)/reply']['post'] = 'leads/addNoteReply/$2/$3/$1';
$route['lead/(:any)/(:num)/note/(:num)/reply/(:num)/update']['post'] = 'leads/updateNoteReply/$2/$3/$4/$1';
$route['lead/(:any)/(:num)/note/(:num)/reply/(:num)/delete']['post'] = 'leads/deleteNoteReply/$2/$3/$4/$1';

$route['lead/(:num)/public-folder'] = 'PublicFolder/index/$1';
$route['lead/(:num)/public-folder/upload']['post'] = 'PublicFolder/upload/$1';
$route['lead/(:num)/public-folder/(:num)/delete']['post'] = 'PublicFolder/deleteFile/$1/$2';

$route['lead/(:any)/(:num)/public-folder'] = 'PublicFolder/index/$2/$1';
$route['lead/(:any)/(:num)/public-folder/upload']['post'] = 'PublicFolder/upload/$2/$1';
$route['lead/(:any)/(:num)/public-folder/(:num)/delete']['post'] = 'PublicFolder/deleteFile/$2/$3/$1';

// $route['public-folder/download/(:num)/(:any)']['post'] = 'PublicFolder/download/$1/$2';
// $route['public-folder/(:num)/(:any)'] = 'PublicFolder/file/$1/$2';
$route['public-folder/(:num)/(:any)'] = 'PublicFolder/download/$1/$2';

$route['lead/(:num)/photos'] = 'photos/index/$1';
$route['lead/(:num)/photo/upload']['post'] = 'photos/ajaxupload_jobphoto';
$route['lead/(:num)/photo/save']['post'] = 'photos/ajaxsave_jobphoto';
$route['lead/(:num)/photo/(:num)/delete']['post'] = 'photos/deletephoto/$1/$2';
$route['lead/(:num)/photo/rotate']['post'] = 'photos/imagerotate';

$route['lead/(:any)/(:num)/photos'] = 'photos/index/$2/$1';
$route['lead/(:any)/(:num)/photo/upload']['post'] = 'photos/ajaxupload_jobphoto';
$route['lead/(:any)/(:num)/photo/save']['post'] = 'photos/ajaxsave_jobphoto';
$route['lead/(:any)/(:num)/photo/(:num)/delete']['post'] = 'photos/deletephoto/$2/$3';
$route['lead/(:any)/(:num)/photo/rotate']['post'] = 'photos/imagerotate';

$route['lead/(:num)/reports'] = 'reports/index/$1';
$route['lead/(:num)/report/create'] = 'reports/create/$1';
$route['lead/(:num)/report/upload']['post'] = 'reports/upload';
$route['lead/(:num)/report/save-img']['post'] = 'reports/save_img';
$route['lead/(:num)/report/save']['post'] = 'reports/save/$1';
$route['lead/(:num)/report/(:num)/pdf'] = 'reports/pdf/$2/$1';
$route['lead/(:num)/report/(:num)/delete']['post'] = 'reports/delete/$1/$2';
$route['lead/(:num)/report/(:num)/regenerate-pdf']['post'] = 'reports/generatePDF/$2/$1';

$route['lead/(:any)/(:num)/reports'] = 'reports/index/$2/$1';
$route['lead/(:any)/(:num)/report/create'] = 'reports/create/$2/$1';
$route['lead/(:any)/(:num)/report/upload']['post'] = 'reports/upload';
$route['lead/(:any)/(:num)/report/save-img']['post'] = 'reports/save_img';
$route['lead/(:any)/(:num)/report/save']['post'] = 'reports/save/$2/$1';
$route['lead/(:any)/(:num)/report/(:num)/pdf'] = 'reports/pdf/$3/$2/$1';
$route['lead/(:any)/(:num)/report/(:num)/delete']['post'] = 'reports/delete/$2/$3';
$route['lead/(:any)/(:num)/report/(:num)/regenerate-pdf']['post'] = 'reports/generatePDF/$3/$2/$1';

$route['lead/(:num)/docs'] = 'docs/index/$1';
$route['lead/(:num)/doc/upload']['post'] = 'docs/ajaxupload_jobdoc';
$route['lead/(:num)/doc/save']['post'] = 'docs/ajaxsave_jobdoc';
$route['lead/(:num)/doc/delete']['post'] = 'docs/deletedoc';
$route['lead/(:num)/doc/update']['post'] = 'docs/updatedocname';

$route['lead/(:any)/(:num)/docs'] = 'docs/index/$2/$1';
$route['lead/(:any)/(:num)/doc/upload']['post'] = 'docs/ajaxupload_jobdoc';
$route['lead/(:any)/(:num)/doc/save']['post'] = 'docs/ajaxsave_jobdoc';
$route['lead/(:any)/(:num)/doc/delete']['post'] = 'docs/deletedoc';
$route['lead/(:any)/(:num)/doc/update']['post'] = 'docs/updatedocname';

$route['lead/(:num)/party/add']['post'] = 'party/add/$1';
$route['lead/(:num)/party/update']['post'] = 'party/update/$1';

$route['lead/(:any)/(:num)/party/add']['post'] = 'party/add/$2/$1';
$route['lead/(:any)/(:num)/party/update']['post'] = 'party/update/$2/$1';

$route['lead/all-status'] = 'all_status/index';

$route['lead/signed-jobs'] = 'signed_jobs/index';

$route['lead/cash-jobs'] = 'cash_jobs/index';
$route['lead/cash-job/(:num)'] = 'cash_jobs/view/$1';

$route['lead/insurance-jobs'] = 'insurance_jobs/index';
$route['lead/insurance-job/(:num)'] = 'insurance_jobs/view/$1';

$route['lead/(:any)/(:num)/update-insurance-details']['post'] = 'insurance_jobs/updateInsuranceDetails/$2/$1';
$route['lead/(:any)/(:num)/insert-insurance-details']['post'] = 'insurance_jobs/insertInsuranceDetails/$2/$1';
$route['lead/(:any)/(:num)/insert-adjuster']['post'] = 'insurance_jobs/insertAdjuster/$2/$1';
$route['lead/(:any)/(:num)/delete-adjuster/(:num)']['post'] = 'insurance_jobs/deleteAdjuster/$2/$3/$1';

$route['lead/labor-jobs'] = 'labor_jobs/index';
$route['lead/labor-job/(:num)'] = 'labor_jobs/view/$1';

$route['lead/financial-jobs'] = 'financial_jobs/index';
$route['lead/financial-job/(:num)'] = 'financial_jobs/view/$1';

$route['lead/production-jobs'] = 'productions_jobs/index';
$route['lead/production-job/(:num)'] = 'productions_jobs/view/$1';

$route['lead/completed-jobs'] = 'completed_jobs/index';
$route['lead/completed-job/(:num)'] = 'completed_jobs/view/$1';

$route['lead/closed-jobs'] = 'closed_jobs/index';
$route['lead/closed-job/(:num)'] = 'closed_jobs/view/$1';

$route['lead/archive-jobs'] = 'archive_jobs/index';
$route['lead/archive-job/(:num)'] = 'archive_jobs/view/$1';

$route['financial'] = 'financial/index';
$route['financial/records'] = 'financial/records';
$route['financial/record/create'] = 'financial/create';
$route['financial/record/store']['post'] = 'financial/store';
$route['financial/record/(:num)'] = 'financial/show/$1';
// $route['financial/record/(:num)/edit'] = 'financial/edit/$1';
$route['financial/record/(:num)/update']['post'] = 'financial/update/$1';
$route['financial/record/(:num)/delete']['post'] = 'financial/delete/$1';
$route['financial/record/(:num)/receipt'] = 'financial/receipt/$1';

$route['financial/estimates'] = 'estimate/index';
$route['financial/estimate/create'] = 'estimate/create';
$route['financial/estimate/store'] = 'estimate/store';
$route['financial/estimate/(:num)'] = 'estimate/show/$1';
$route['financial/estimate/(:num)/update']['post'] = 'estimate/update/$1';
$route['financial/estimate/(:num)/delete']['post'] = 'estimate/delete/$1';
$route['financial/estimate/(:num)/pdf'] = 'estimate/pdf/$1';
$route['financial/estimate/(:num)/save-pdf']['post'] = 'estimate/savePDF/$1';

$route['items'] = 'items/index';
$route['item/create'] = 'items/create';
$route['item/store']['post'] = 'items/store';
$route['item/(:num)'] = 'items/show/$1';
$route['item/(:num)/update']['post'] = 'items/update/$1';
$route['item/(:num)/delete']['post'] = 'items/delete/$1';
$route['item/ajax-record/(:num)'] = 'items/ajaxRecord/$1';

$route['teams'] = 'teams/index';
$route['team/create'] = 'teams/create';
$route['team/store']['post'] = 'teams/store';
$route['team/(:num)'] = 'teams/show/$1';
// $route['team/(:num)/edit'] = 'teams/edit/$1';
$route['team/(:num)/update']['post'] = 'teams/update/$1';
$route['team/(:num)/delete']['post'] = 'teams/delete/$1';

$route['vendors'] = 'vendors/index';
$route['vendor/create'] = 'vendors/create';
$route['vendor/store']['post'] = 'vendors/store';
$route['vendor/(:num)'] = 'vendors/show/$1';
// $route['vendor/(:num)/edit'] = 'vendors/edit/$1';
$route['vendor/(:num)/update']['post'] = 'vendors/update/$1';
$route['vendor/(:num)/delete']['post'] = 'vendors/delete/$1';
$route['vendor/(:num)/create-contact'] = 'vendors/createContact/$1';
$route['vendor/(:num)/delete-contact/(:num)'] = 'vendors/deleteContact/$1/$2';

$route['vendor/(:num)/docs'] = 'vendor_docs/index/$1';
$route['vendor/(:num)/doc/upload']['post'] = 'vendor_docs/ajaxupload_vendordoc';
$route['vendor/(:num)/doc/save']['post'] = 'vendor_docs/ajaxsave_vendordoc';
$route['vendor/(:num)/doc/delete']['post'] = 'vendor_docs/deletedoc';
$route['vendor/(:num)/doc/update']['post'] = 'vendor_docs/updatedocname';

$route['company-docs'] = 'company_docs/index';
$route['company-docs/upload']['post'] = 'company_docs/upload';
$route['company-doc/(:num)/download'] = 'company_docs/download/$1';
$route['company-doc/(:num)/delete']['post'] = 'company_docs/delete/$1';

$route['company-photos'] = 'company_photos/index';
$route['company-photos/create-folder'] = 'company_photos/createFolder';
$route['company-photos/upload'] = 'company_photos/upload';
$route['company-photos/(:num)/generate-public-key']['post'] = 'company_photos/generatePublicKey/$1';
$route['company-photos/(:num)/delete']['post'] = 'company_photos/delete/$1';
$route['company-photos/public/(:num)/(:any)'] = 'company_photos/sharedFolder/$1/$2';
$route['company-photos/(:num)'] = 'company_photos/index/$1';
$route['company-photos/(:num)/create-folder'] = 'company_photos/createFolder/$1';
$route['company-photos/(:num)/upload'] = 'company_photos/upload/$1';
$route['company-photos/(:num)/(:num)/generate-public-key']['post'] = 'company_photos/generatePublicKey/$2/$1';
$route['company-photos/(:num)/(:num)/delete']['post'] = 'company_photos/delete/$2/$1';
$route['company-photos/public/(:num)/(:any)/(:num)'] = 'company_photos/sharedFolder/$1/$2/$3';
$route['company-photos/public/download/(:num)/(:any)/(:num)']['post'] = 'company_photos/download/$1/$2/$3';

$route['setting'] = 'setting/index';
$route['setting/company-details']['post'] = 'setting/companyDetails';
$route['setting/ajaxupload'] = 'setting/ajaxupload';
$route['setting/ajaxsave'] = 'setting/ajaxsave';
$route['setting/ajaxcolor'] = 'setting/ajaxcolor';
$route['setting/financial-options'] = 'financialOptions/index';
// $route['setting/financial-options/type/store']['post'] = 'financialOptions/insertType';
// $route['setting/financial-options/type/(:num)/update']['post'] = 'financialOptions/updateType/$1';
// $route['setting/financial-options/type/(:num)/delete']['post'] = 'financialOptions/deleteType/$1';
$route['setting/financial-options/subtype/store']['post'] = 'financialOptions/insertSubtype';
$route['setting/financial-options/subtype/(:num)/update']['post'] = 'financialOptions/updateSubtype/$1';
$route['setting/financial-options/subtype/(:num)/delete']['post'] = 'financialOptions/deleteSubtype/$1';
$route['setting/financial-options/accCode/store']['post'] = 'financialOptions/insertAccCode';
$route['setting/financial-options/accCode/(:num)/update']['post'] = 'financialOptions/updateAccCode/$1';
$route['setting/financial-options/accCode/(:num)/delete']['post'] = 'financialOptions/deleteAccCode/$1';
$route['setting/financial-options/method/store']['post'] = 'financialOptions/insertMethod';
$route['setting/financial-options/method/(:num)/update']['post'] = 'financialOptions/updateMethod/$1';
$route['setting/financial-options/method/(:num)/delete']['post'] = 'financialOptions/deleteMethod/$1';
$route['setting/financial-options/bankAcc/store']['post'] = 'financialOptions/insertBankAcc';
$route['setting/financial-options/bankAcc/(:num)/update']['post'] = 'financialOptions/updateBankAcc/$1';
$route['setting/financial-options/bankAcc/(:num)/delete']['post'] = 'financialOptions/deleteBankAcc/$1';
$route['setting/financial-options/state/store']['post'] = 'financialOptions/insertState';
$route['setting/financial-options/state/(:num)/update']['post'] = 'financialOptions/updateState/$1';
$route['setting/financial-options/state/(:num)/delete']['post'] = 'financialOptions/deleteState/$1';
$route['setting/client-options'] = 'clientOptions/index';
$route['setting/client-options/lead-source/store']['post'] = 'clientOptions/insertLeadSource';
$route['setting/client-options/lead-source/(:num)/update']['post'] = 'clientOptions/updateLeadSource/$1';
$route['setting/client-options/lead-source/(:num)/delete']['post'] = 'clientOptions/deleteLeadSource/$1';
$route['setting/client-options/classification/store']['post'] = 'clientOptions/insertClassification';
$route['setting/client-options/classification/(:num)/update']['post'] = 'clientOptions/updateClassification/$1';
$route['setting/client-options/classification/(:num)/delete']['post'] = 'clientOptions/deleteClassification/$1';
$route['setting/task-options'] = 'taskOptions/index';
$route['setting/task-options/type/store']['post'] = 'taskOptions/insertType';
$route['setting/task-options/type/(:num)/update']['post'] = 'taskOptions/updateType/$1';
$route['setting/task-options/type/(:num)/delete']['post'] = 'taskOptions/deleteType/$1';
$route['setting/smtp-settings'] = 'smtpSettings/index';
$route['setting/smtp-settings/update']['post'] = 'smtpSettings/updateSMTPSettings';
$route['setting/twilio-settings'] = 'twilioSettings/index';
$route['setting/twilio-settings/update']['post'] = 'twilioSettings/updateTwilioSettings';
$route['setting/dashboard-options'] = 'dashboardOptions/index';
$route['setting/dashboard-options/update']['post'] = 'dashboardOptions/updateBoxName';
$route['setting/user-options'] = 'userOptions/index';
$route['setting/user-options/cell-notif-suffix/store']['post'] = 'userOptions/insertCellNotifSuffix';
$route['setting/user-options/cell-notif-suffix/(:num)/update']['post'] = 'userOptions/updateCellNotifSuffix/$1';
$route['setting/user-options/cell-notif-suffix/(:num)/delete']['post'] = 'userOptions/deleteCellNotifSuffix/$1';

$route['search/leads']['post'] = 'search/leads';
$route['search/tasks']['post'] = 'search/tasks';
$route['search/users']['post'] = 'search/users';
$route['search/sales_rep']['post'] = 'search/sales_rep';

$route['(.+)'] = 'errors/page_missing';
