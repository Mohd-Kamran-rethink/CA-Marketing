<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BannkController;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\PhoneNumberController;
use App\Http\Controllers\SocialAccounts;
use App\Http\Controllers\SourceController;
use App\Http\Controllers\TransferController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// AUTH ROUTES
Route::get('/',[AuthController::class,'loginView'])->name('loginView');
Route::post('/login',[AuthController::class,'login'])->name('login');
Route::get('/logout',[AuthController::class,'logout'])->name('logout');
// Transactions
Route::get('/dashboard',[AuthController::class,'dashboard'])->name('dashboard');
Route::post('/dashboard',[AuthController::class,'dashboard'])->name('dashboard');


//bank-accounts   
Route::prefix('bank-accounts')->group(function () {
    Route::get('',[BannkController::class,'list'])->name('list');
    Route::get('/add',[BannkController::class,'addForm'])->name('addForm');
    Route::post('/add',[BannkController::class,'add'])->name('add');
    Route::get('/edit/{id}',[BannkController::class,'addForm'])->name('addForm');
    Route::post('/edit',[BannkController::class,'edit'])->name('edit');
    Route::post('/delete',[BannkController::class,'delete'])->name('delete');
    // deposit money
    Route::get('deposit-money/{id}',[BannkController::class,'adddepositForm'])->name('adddepositForm');
    Route::post('/deposit',[BannkController::class,'addDeposit'])->name('addDeposit');
    Route::get('/withdraw-money/{id}',[BannkController::class,'addWithdrawForm'])->name('addWithdrawForm');
    Route::post('/withdraw',[BannkController::class,'addWithdraw'])->name('addWithdraw');
    // view detai;s
    Route::get('/details',[BannkController::class,'viewDetails'])->name('viewDetails');
    Route::post('/reactive',[BannkController::class,'reactivebaNK'])->name('reactivebaNK');
});   

Route::prefix('sources')->group(function () {
    Route::get('',[SourceController::class,'list'])->name('list');
    Route::get('/add',[SourceController::class,'addView'])->name('addView');
    Route::post('/add',[SourceController::class,'add'])->name('add');
    Route::get('/edit',[SourceController::class,'addView'])->name('addView');
    Route::post('/edit',[SourceController::class,'edit'])->name('edit');
    Route::post('/delete',[SourceController::class,'delete'])->name('delete');
}); 

Route::prefix('accounts')->group(function () {
    Route::get('',[SocialAccounts::class,'list'])->name('list');
    Route::post('',[SocialAccounts::class,'list'])->name('list');
    Route::get('/add',[SocialAccounts::class,'addView'])->name('addView');
    Route::post('/add',[SocialAccounts::class,'add'])->name('add');
    Route::get('/edit',[SocialAccounts::class,'addView'])->name('addView');
    Route::post('/edit',[SocialAccounts::class,'edit'])->name('edit');
    Route::get('/change-status',[SocialAccounts::class,'changeStatus'])->name('changeStatus');
}); 

Route::prefix('agents')->group(function () {
    Route::get('',[UserController::class,'listAgents'])->name('listAgents');
    Route::get('/add',[UserController::class,'AgentView'])->name('AgentView');
    Route::post('/add',[UserController::class,'add'])->name('add');
    Route::get('/edit',[UserController::class,'AgentView'])->name('AgentView');
    Route::post('/edit',[UserController::class,'edit'])->name('edit');
    Route::post('/delete',[UserController::class,'delete'])->name('delete');
});

Route::prefix('campaigns')->group(function () {
    Route::get('',[CampaignController::class,'list'])->name('list');
    Route::post('',[CampaignController::class,'list'])->name('list');
    Route::get('/add',[CampaignController::class,'addView'])->name('addView');
    Route::post('/add',[CampaignController::class,'add'])->name('add');
    Route::get('/edit',[CampaignController::class,'addView'])->name('addView');
    Route::post('/edit',[CampaignController::class,'edit'])->name('edit');
    Route::post('/delete',[CampaignController::class,'delete'])->name('delete');
    Route::post('change-status',[CampaignController::class,'changeStatus'])->name('changeStatus');
    Route::post('/add-spending',[CampaignController::class,'addSpending'])->name('addSpending');
    Route::get('/view-details',[CampaignController::class,'viewSpendings'])->name('viewSpendings');
    Route::get('/add-results',[CampaignController::class,'addResultForm'])->name('addResultForm');
    Route::get('/edit-results',[CampaignController::class,'addResultForm'])->name('addResultForm');
    Route::post('/edit-result',[CampaignController::class,'editResult'])->name('editResult');
    Route::post('/add-result',[CampaignController::class,'addResult'])->name('addResult');
    Route::get('view-results',[CampaignController::class,'viewResults'])->name('viewResults');
    Route::get('agent/{id}',[CampaignController::class,'viewMine'])->name('viewMine');
    Route::get('show-numbers',[CampaignController::class,'showNumbers'])->name('showNumbers');
}); 
Route::get('/render/cities',[CampaignController::class,'renderCities'])->name('renderCities');


Route::prefix('transfers')->group(function () {
    Route::get('',[TransferController::class,'TransferList'])->name('TransferList');
    Route::get('/add',[TransferController::class,'addTransferForm'])->name('addTransferForm');
    Route::post('/add',[TransferController::class,'addTransfer'])->name('addTransfer');
});

Route::prefix('/phone-numbers')->group(function () {
    Route::get('', [PhoneNumberController::class, 'list'])->name('list');
    Route::get('add', [PhoneNumberController::class, 'addForm'])->name('addForm');
    Route::post('add', [PhoneNumberController::class, 'add'])->name('add');
    Route::get('edit', [PhoneNumberController::class, 'addForm'])->name('addForm');
    Route::post('edit', [PhoneNumberController::class, 'edit'])->name('edit');
    Route::post('change-status', [PhoneNumberController::class, 'statusChange'])->name('statusChange');
    Route::post('reassign', [PhoneNumberController::class, 'reassign'])->name('reassign');
    Route::get('history', [PhoneNumberController::class, 'history'])->name('history');
});
