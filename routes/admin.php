<?php

use App\Http\Controllers\admin\AdminController;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\admin\MainCategoryController;
use \App\Http\Controllers\admin\CompanyTypeController;
use \App\Http\Controllers\admin\MoneyManageController;
use \App\Http\Controllers\admin\MarketManageController;
use \App\Http\Controllers\admin\CompaniesController;
use \App\Http\Controllers\admin\FinaialTransactionController;
use \App\Http\Controllers\admin\RegionController;
use \App\Http\Controllers\admin\SupervisorControllers;
use \App\Http\Controllers\admin\SubCategoriesController;

Route::get('login', [AdminController::class, 'index'])->name('login');
Route::group(['prefix' => 'admin'], function () {
    Route::post('admin_login', [AdminController::class, 'admin_login']);
    Route::group(['middleware' => ['auth']], function () {
        Route::get('dashboard', [AdminController::class, 'dashboard']);
        Route::post('logout', [AdminController::class, 'logout'])->name('logout');
        // Start Update Admin Details
        Route::match(['post', 'get'], 'update_admin_password', [AdminController::class, 'update_admin_password']);
        Route::match(['post', 'get'], 'update_admin_details', [AdminController::class, 'update_admin_details']);
        // Start Update Admin Password
        // update admin password
        Route::match(['post', 'get'], 'update_admin_password', [AdminController::class, 'update_admin_password']);
        // check Admin Password
        Route::post('check_admin_password', [AdminController::class, 'check_admin_password']);
        // Start Company Categories
        Route::controller(MainCategoryController::class)->group(function () {
            Route::get('main_categories', 'index');
            Route::post('main_categories/add', 'add');
            Route::post('main_categories/edit', 'edit');
            Route::post('main_categories/delete/{id}', 'delete');
        });
        //////////// Start SubCategories Controller
        Route::controller(SubCategoriesController::class)->group(function () {
            Route::get('sub_categories/{id}', 'index');
            Route::post('sub_category/add', 'add');
            Route::post('sub_category/edit', 'edit');
            Route::post('sub_category/delete/{id}', 'delete');

        });
        // Company Types
        Route::controller(CompanyTypeController::class)->group(function () {
            Route::get('company_type', 'index');
            Route::post('company_type/store', 'store');
            Route::post('company_type/update', 'update');
            Route::post('company_type/destroy/{id}', 'destroy');
        });
        Route::controller(CompaniesController::class)->group(function () {
            Route::get('companies', 'index');
            Route::get('company/main-filter', 'MainFilter');
            Route::get('expire-companies', 'expiredCompanies');
            Route::get('expire-month', 'expiringCompaniesinlastmonth');
            Route::get('company/filter', 'getFilteredCompanies');
            Route::match(['post', 'get'], 'companies/store', 'store');
            Route::match(['post', 'get'], 'companies/update/{id}', 'update');
            Route::post('companies/destroy/{id}', 'destroy');
            // get the company transactions
            Route::get('company/transactions/{id}', 'transactions');
            Route::post('companies/market_confirm/{id}', 'market_confirm');
            Route::match(['post','get'],'companies/money_confirm/{id}', 'money_confirm');
            Route::get('companies/market-unconfirmed', 'companies_unconfirmed');
            Route::get('companies/money-unconfirmed', 'money_unconfirmed');
            Route::match(['get', 'post'], 'company/certificate/{id}', 'certificate');
            Route::get('companies/get-branches/{region_id}', 'getBranches');
            Route::get('companies/get-subcategories/{main_category}', 'getsubcategories');
            Route::get('companies/company_under_view', 'company_under_view');
            Route::post('companies/confirm/{id}', 'confirm_archive');
        });

        // Start Management Routes

        // Start Money Management

        Route::controller(MoneyManageController::class)->group(function () {
            Route::get('money-manage', 'index');
            Route::post('money-manage/store', 'store');
            Route::match(['post', 'get'], 'money-manage/update/{id}', 'update');
            //  Route::post('money-manage/update', 'update');
            Route::post('money-manage/destroy/{id}', 'destroy');
            Route::get('get-branches/{region_id}', 'getBranches');
        });
        // Start Market Management
        Route::controller(MarketManageController::class)->group(function () {
            Route::get('market-manage', 'index');
            Route::post('market-manage/store', 'store');
            Route::match(['post', 'get'], 'market-manage/update/{id}', 'update');
            Route::post('market-manage/destroy/{id}', 'destroy');
            Route::get('get-branches/{region_id}', 'getBranches');
        });

        ////////////////////// Start SuperVisor Controller ///////////////////
        ///
        Route::controller(SupervisorControllers::class)->group(function () {
            Route::get('supervisors', 'index');
            Route::post('supervisor/store', 'store');
            //Route::post('supervisor/update/{id}', 'update');
            Route::match(['post', 'get'], 'supervisor/update/{id}', 'update');
            Route::post('supervisor/destroy/{id}', 'destroy');
            Route::get('get-branches/{region_id}', 'getBranches');

        });

        // Start Financial Transaction

        Route::controller(FinaialTransactionController::class)->group(function () {
            Route::get('transaction', 'index');
            Route::get('transaction/filter', 'TransactionFilter');
            Route::match(['post', 'get'], 'transaction/store', 'store');
            Route::match(['post', 'get'], 'transaction/update/{id}', 'update');
            Route::post('transaction/destroy/{id}', 'destroy');
        });

        ///////////////////////// Start Regions /////////////////////
        ///
        Route::controller(RegionController::class)->group(function () {
            Route::get('regions', 'index');
            Route::match(['post', 'get'], 'region/store', 'store');
            Route::match(['post', 'get'], 'region/update', 'update');
            Route::post('region/delete/{id}', 'delete');
        });
        ///////////////////////// Start Branches /////////////////////
        ///
        Route::controller(\App\Http\Controllers\admin\BranchController::class)->group(function () {
            Route::get('branches/{region_id}', 'index');
            Route::match(['post', 'get'], 'branche/store', 'store');
            Route::match(['post', 'get'], 'branche/update', 'update');
            Route::post('branche/delete/{id}', 'delete');
        });
        Route::get('company-excel', function () {
            return (new \App\Exports\CompanyExport())->download('company.xlsx');
        });
        Route::get('company-report',[\App\Http\Controllers\admin\GeneratePdfController::class,'generatecompanypdf']);
    });
});

Route::controller(CompaniesController::class)->group(function () {
    Route::match(['post', 'get'], 'companies/store', 'user_store');
    Route::get('companies/get-branches/{region_id}', 'getBranches');
    Route::get('companies/get-subcategories/{main_category}', 'getsubcategories');
});
