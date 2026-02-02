<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomAuthController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\registerController;
use App\Http\Controllers\API\YelamController;
use App\Http\Controllers\PMregisterController;
use App\Http\Controllers\masterpullivari;
use App\Http\Controllers\incomecontroller;
use App\Http\Controllers\expensecontroller;
use App\Http\Controllers\ReportsController;




Route::get('dashboard', [CustomAuthController::class, 'dashboard'])->name('dashboard');
Route::get('login', [CustomAuthController::class, 'index'])->name('login');
Route::post('custom-login', [CustomAuthController::class, 'customLogin'])->name('login.custom');
Route::get('register', [CustomAuthController::class, 'registration'])->name('register');
Route::post('custom-registration', [CustomAuthController::class, 'customRegistration'])->name('register.custom');
Route::get('signout', [CustomAuthController::class, 'signOut'])->name('signout');


Route::get('/', function () {
    return view('office.login');

});


Route::middleware(['auth'])->group(function () {
    Route::get('dashboard', function () {
        
        return route('dashboard');
    
    });


Route::get('/',[MemberController::class,'dashboard'])->name('dashboard');
Route::get('regisration',[MemberController::class,'registerform']);
Route::post('/registerstore',[MemberController::class,'registerstore']);
Route::get('/allmember',[MemberController::class,'allmember'])->name('allmember');
Route::get('/yelamlist',[MemberController::class,'yelamlist'])->name('yelamlist');
Route::post('/savepayment',[MemberController::class,'savepayment'])->name('savepayment');
Route::get('yelamthinglist',[MemberController::class,'yelamthinglist'])->name('yelamthinglist');
Route::get('receipt/{id}',[MemberController::class,'receipt'])->name('receipt');
Route::get('onlyyellamentryreceipt/{id}',[MemberController::class,'onlyyellamentryreceipt'])->name('onlyyellamentryreceipt');
Route::get('popupreceipt/{id}',[MemberController::class,'popup_receipt'])->name('popupreceipt');
//Piranthamagal route
Route::get('pmregisration',[PMregisterController::class,'pmregisterform']);
Route::post('/pmvalidate',[PMregisterController::class,'pmvalidate'])->name('pmvalidate');
Route::post('/pmregisterstore',[PMregisterController::class,'pmregisterstore'])->name('pmregisterstore');
Route::get('/pmmemberlist',[PMregisterController::class,'pmmemberlist'])->name('pmmemberlist');
Route::get('/editpmmemeber/{id}',[PMregisterController::class,'editpmmember'])->name('editpmmember');
Route::post('updatepmmember/{id}',[PMregisterController::class,'updatepmmember'])->name('updatepmmember');
Route::get('pmexport', [PMregisterController::class, 'pmexport'])->name('pmexport');
Route::post('pmpay',[PMregisterController::class,'pmpay'])->name('pmpay');


//pullimember edit and update
Route::get('profile/{slug}',[MemberController::class,'slug'])->name('profile');
Route::get('editmemeber/{id}',[MemberController::class,'editmember'])->name('editmember');
Route::post('updatemember/{id}',[MemberController::class,'updatemember'])->name('updatemember');
Route::get('sendwhatsappmsg/{id}',[registerController::class,'whatsapp'])->name('whatsapp');

//yellam entries
Route::get('yelamentryform',[registerController::class,'yelamentryform'])->name('yelamentryform');
Route::post('yellamvalidate',[registerController::class,'yellamvalidate'])->name('yellamvalidate');
Route::post('yelamentrystore',[registerController::class,'yelamentrystore'])->name('yelamentrystore');
Route::get('yelamthings',[registerController::class,'Things'])->name('yelamthings');
Route::post('yelamstore',[registerController::class,'yelamstore'])->name('yelamstore');
 Route::get('editmemeber/{id}',[MemberController::class,'editmember'])->name('editmember');
 Route::post('updatemember/{id}',[MemberController::class,'updatemember'])->name('updatemember');
 


Route::get('yelamwhatsappmessage/{id}',[registerController::class,'whatsappmessage'])->name('whatsappmessage');
Route::get('deletemember/{id}',[registerController::class,'delete'])->name('delete');

Route::get('reportpage', [YelamController::class,'getAllreport'])->name('reportpage');
Route::post('getAllreport', [YelamController::class, 'getAllreport'])->name('getAllreport');
Route::get('export', [YelamController::class, 'export'])->name('export');

//expense
Route::get('expenditurelist',[expensecontroller::class,'expenditurelist'])->name('expenditurelist');
Route::get('delete_enquiry/{id}',[expensecontroller::class,'delete_enquiry'])->name('delete_enquiry');
Route::get('ExpenditureEntry',[expensecontroller::class,'ExpenditureEntry'])->name('ExpenditureEntry');
Route::post('enquiryvalidate',[expensecontroller::class,'enquiryvalidate'])->name('enquiryvalidate');
Route::post('enquirystore',[expensecontroller::class,'enquirystore'])->name('enquirystore');

//income
Route::get('income_entry',[incomecontroller::class,'income_entry'])->name('income_entry');
Route::post('paymentstatus',[incomecontroller::class,'paymentstatus'])->name('paymentstatus');
Route::post('income_validate',[incomecontroller::class,'income_validate'])->name('income_validate');
Route::post('income_store',[incomecontroller::class,'income_store'])->name('income_store');
Route::post('pulliidSearch',[incomecontroller::class,'pulliidSearch'])->name('pulliidSearch');

//incomelist
Route::get('incomelist',[incomecontroller::class,'incomelist'])->name('incomelist');

//userprofile
Route::get('userprofile/{id}',[MemberController::class,'userprofile'])->name('userprofile');
Route::get('userprofile_page',[MemberController::class,'userprofile_page'])->name('userprofile_page');


//P&L report
Route::get('pl_report',[ReportsController::class,'pl_report'])->name('pl_report');
Route::post('pl_report_calc',[ReportsController::class,'pl_report_calc'])->name('pl_report_calc');



Route::get('masterpullivari', [masterpullivari::class, 'masterpullivari'])->name('masterpullivari');
Route::post('masterpullivariValidate', [masterpullivari::class, 'masterpullivariValidate'])->name('masterpullivariValidate');
Route::post('masterpullivariStore', [masterpullivari::class, 'masterpullivariStore'])->name('masterpullivariStore');
Route::post('annual_year', [masterpullivari::class, 'annual_year'])->name('annual_year');
Route::get('pullivari', [masterpullivari::class, 'pullivari'])->name('pullivari');


});

