<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\dashboard\Analytics;
use App\Http\Controllers\layouts\WithoutMenu;
use App\Http\Controllers\layouts\WithoutNavbar;
use App\Http\Controllers\layouts\Fluid;
use App\Http\Controllers\layouts\Container;
use App\Http\Controllers\layouts\Blank;
use App\Http\Controllers\pages\AccountSettingsAccount;
use App\Http\Controllers\pages\AccountSettingsNotifications;
use App\Http\Controllers\pages\AccountSettingsConnections;
use App\Http\Controllers\pages\MiscError;
use App\Http\Controllers\pages\MiscUnderMaintenance;
use App\Http\Controllers\authentications\LoginBasic;
use App\Http\Controllers\authentications\RegisterBasic;
use App\Http\Controllers\authentications\ForgotPasswordBasic;
use App\Http\Controllers\BrandList;
use App\Http\Controllers\cards\CardBasic;
use App\Http\Controllers\user_interface\Accordion;
use App\Http\Controllers\user_interface\Alerts;
use App\Http\Controllers\user_interface\Badges;
use App\Http\Controllers\user_interface\Buttons;
use App\Http\Controllers\user_interface\Carousel;
use App\Http\Controllers\user_interface\Collapse;
use App\Http\Controllers\user_interface\Dropdowns;
use App\Http\Controllers\user_interface\Footer;
use App\Http\Controllers\user_interface\ListGroups;
use App\Http\Controllers\user_interface\Modals;
use App\Http\Controllers\user_interface\Navbar;
use App\Http\Controllers\user_interface\Offcanvas;
use App\Http\Controllers\user_interface\PaginationBreadcrumbs;
use App\Http\Controllers\user_interface\Progress;
use App\Http\Controllers\user_interface\Spinners;
use App\Http\Controllers\user_interface\TabsPills;
use App\Http\Controllers\user_interface\Toasts;
use App\Http\Controllers\user_interface\TooltipsPopovers;
use App\Http\Controllers\user_interface\Typography;
use App\Http\Controllers\extended_ui\PerfectScrollbar;
use App\Http\Controllers\extended_ui\TextDivider;
use App\Http\Controllers\icons\RiIcons;
use App\Http\Controllers\form_elements\BasicInput;
use App\Http\Controllers\form_elements\InputGroups;
use App\Http\Controllers\form_layouts\VerticalForm;
use App\Http\Controllers\form_layouts\HorizontalForm;
use App\Http\Controllers\ProductListController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\CustomerListController;
use App\Http\Controllers\OrderListController;
use App\Http\Controllers\PromotionListController;
use App\Http\Controllers\InquiriesListController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\PageBuilderController;
use App\Http\Controllers\SalesController;

use App\Http\Controllers\tables\Basic as TablesBasic;

// Main Page Route
Route::get('/', [Analytics::class, 'index'])->name('dashboard-analytics');

// layout
Route::get('/layouts/without-menu', [WithoutMenu::class, 'index'])->name('layouts-without-menu');
Route::get('/layouts/without-navbar', [WithoutNavbar::class, 'index'])->name('layouts-without-navbar');
Route::get('/layouts/fluid', [Fluid::class, 'index'])->name('layouts-fluid');
Route::get('/layouts/container', [Container::class, 'index'])->name('layouts-container');
Route::get('/layouts/blank', [Blank::class, 'index'])->name('layouts-blank');

// pages
Route::get('/pages/account-settings-account', [AccountSettingsAccount::class, 'index'])->name('pages-account-settings-account');
Route::get('/pages/account-settings-notifications', [AccountSettingsNotifications::class, 'index'])->name('pages-account-settings-notifications');
Route::get('/pages/account-settings-connections', [AccountSettingsConnections::class, 'index'])->name('pages-account-settings-connections');
Route::get('/pages/misc-error', [MiscError::class, 'index'])->name('pages-misc-error');
Route::get('/pages/misc-under-maintenance', [MiscUnderMaintenance::class, 'index'])->name('pages-misc-under-maintenance');

// authentication
Route::get('/auth/login-basic', [LoginBasic::class, 'index'])->name('auth-login-basic');
Route::get('/auth/register-basic', [RegisterBasic::class, 'index'])->name('auth-register-basic');
Route::get('/auth/forgot-password-basic', [ForgotPasswordBasic::class, 'index'])->name('auth-reset-password-basic');

// cards
Route::get('/cards/basic', [CardBasic::class, 'index'])->name('cards-basic');

// User Interface
Route::get('/ui/accordion', [Accordion::class, 'index'])->name('ui-accordion');
Route::get('/ui/alerts', [Alerts::class, 'index'])->name('ui-alerts');
Route::get('/ui/badges', [Badges::class, 'index'])->name('ui-badges');
Route::get('/ui/buttons', [Buttons::class, 'index'])->name('ui-buttons');
Route::get('/ui/carousel', [Carousel::class, 'index'])->name('ui-carousel');
Route::get('/ui/collapse', [Collapse::class, 'index'])->name('ui-collapse');
Route::get('/ui/dropdowns', [Dropdowns::class, 'index'])->name('ui-dropdowns');
Route::get('/ui/footer', [Footer::class, 'index'])->name('ui-footer');
Route::get('/ui/list-groups', [ListGroups::class, 'index'])->name('ui-list-groups');
Route::get('/ui/modals', [Modals::class, 'index'])->name('ui-modals');
Route::get('/ui/navbar', [Navbar::class, 'index'])->name('ui-navbar');
Route::get('/ui/offcanvas', [Offcanvas::class, 'index'])->name('ui-offcanvas');
Route::get('/ui/pagination-breadcrumbs', [PaginationBreadcrumbs::class, 'index'])->name('ui-pagination-breadcrumbs');
Route::get('/ui/progress', [Progress::class, 'index'])->name('ui-progress');
Route::get('/ui/spinners', [Spinners::class, 'index'])->name('ui-spinners');
Route::get('/ui/tabs-pills', [TabsPills::class, 'index'])->name('ui-tabs-pills');
Route::get('/ui/toasts', [Toasts::class, 'index'])->name('ui-toasts');
Route::get('/ui/tooltips-popovers', [TooltipsPopovers::class, 'index'])->name('ui-tooltips-popovers');
Route::get('/ui/typography', [Typography::class, 'index'])->name('ui-typography');

// extended ui
Route::get('/extended/ui-perfect-scrollbar', [PerfectScrollbar::class, 'index'])->name('extended-ui-perfect-scrollbar');
Route::get('/extended/ui-text-divider', [TextDivider::class, 'index'])->name('extended-ui-text-divider');

// icons
Route::get('/icons/icons-ri', [RiIcons::class, 'index'])->name('icons-ri');

// form elements
Route::get('/forms/basic-inputs', [BasicInput::class, 'index'])->name('forms-basic-inputs');
Route::get('/forms/input-groups', [InputGroups::class, 'index'])->name('forms-input-groups');

// form layouts
Route::get('/form/layouts-vertical', [VerticalForm::class, 'index'])->name('form-layouts-vertical');
Route::get('/form/layouts-horizontal', [HorizontalForm::class, 'index'])->name('form-layouts-horizontal');

// tables
Route::get('/tables/basic', [TablesBasic::class, 'index'])->name('tables-basic');



//Brand List
Route::get('/brands', [BrandList::class, 'index'])->name('brands.index');
Route::post('/brands/store', [BrandList::class, 'store'])->name('brands.store');
Route::put('/brands/update/{id}', [BrandList::class, 'update'])->name('brands.update');
Route::delete('/brands/{id}', [BrandList::class, 'destroy'])->name('brands.destroy');

//Product List
Route::get('/products', [ProductListController::class, 'index'])->name('products.index');
Route::post('/products/store', [ProductListController::class, 'store'])->name('products.store');
Route::post('/products/update/{id}', [ProductListController::class, 'update'])->name('products.update');
Route::delete('/products/{id}', [ProductListController::class, 'destroy'])->name('products.destroy');
Route::get('/product-image/{id}', [ProductListController::class, 'getProductImage'])->name('products.image');

//Staff List
Route::get('/staff', [StaffController::class, 'index'])->name('staff.index');
Route::post('/staff', [StaffController::class, 'store'])->name('staff.store');
Route::get('/staff/{id}/edit', [StaffController::class, 'edit']);
Route::put('/staff/{id}', [StaffController::class, 'update']);
Route::delete('/staff/{id}', [StaffController::class, 'destroy'])->name('staff.destroy');
Route::get('/staff/profile-picture/{id}', 'StaffController@getProfilePicture');

//Customer List
Route::get('/customers', [CustomerListController::class, 'index'])->name('customers.index');
Route::post('/customers', [CustomerListController::class, 'store'])->name('customers.store');
Route::get('/customers/{id}/edit', [CustomerListController::class, 'edit']);
Route::put('/customers/{id}', [CustomerListController::class, 'update']);
Route::delete('/customers/{id}', [CustomerListController::class, 'destroy'])->name('customers.destroy');

// Order List
Route::get('/orders', [OrderListController::class, 'index'])->name('orders.index');
Route::post('/orders/store', [OrderListController::class, 'store'])->name('orders.store');
Route::post('/orders/update/{id}', [OrderListController::class, 'update'])->name('orders.update');
Route::delete('/orders/{id}', [OrderListController::class, 'destroy'])->name('orders.destroy');
Route::post('/orders/status/{id}', [OrderListController::class, 'updateStatus'])->name('orders.updateStatus');

// Promotion List

Route::get('/promotions', [PromotionListController::class, 'index'])->name('promotions.index');
Route::post('/promotions/store', [PromotionListController::class, 'store'])->name('promotions.store');
Route::post('/promotions/update/{id}', [PromotionListController::class, 'update'])->name('promotions.update');
Route::delete('/promotions/{id}', [PromotionListController::class, 'destroy'])->name('promotions.destroy');

// Inquiries List
Route::get('/inquiries', [InquiriesListController::class, 'index'])->name('inquiries.index');
Route::post('/inquiries/store', [InquiriesListController::class, 'store'])->name('inquiries.store');
Route::put('/inquiries/update/{id}', [InquiriesListController::class, 'update'])->name('inquiries.update');
Route::delete('/inquiries/{id}', [InquiriesListController::class, 'destroy'])->name('inquiries.destroy');

Route::get('/dashboard', [SalesController::class, 'index'])->name('dashboard.index');
Route::get('/sales-data', [SalesController::class, 'getWeeklySales']);
Route::get('/sales-data', [SalesController::class, 'getSalesData']);
Route::get('/weekly-sales-data', [SalesController::class, 'getWeeklySalesData']);
Route::get('/get-total-profit', [SalesController::class, 'getTotalProfitData']);
Route::get('/getTotalEarnings', [SalesController::class, 'getTotalEarnings'])->name('getTotalEarnings');
Route::get('/weekly-profit', [SalesController::class, 'getWeeklyProfitComparison'])->name('weekly-profit');
Route::get('/getNewOrderStats', [SalesController::class, 'getNewOrderStats']);

// Event Management
Route::get('/events', [EventController::class, 'index'])->name('events.index');
Route::post('/events/store', [EventController::class, 'store'])->name('events.store');
Route::put('/events/update/{id}', [EventController::class, 'update'])->name('events.update');
Route::delete('/events/{id}', [EventController::class, 'destroy'])->name('events.destroy');

// Page Builder
Route::get('/events/{id}/builder', [PageBuilderController::class, 'builder'])->name('events.builder');
Route::post('/events/{id}/builder/save', [PageBuilderController::class, 'save'])->name('events.builder.save');
Route::get('/events/{id}/view', [PageBuilderController::class, 'view'])->name('events.view');