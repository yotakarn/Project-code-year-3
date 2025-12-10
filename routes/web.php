<?php


use App\Http\Controllers\DentistController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\HomeController;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Route;

Route::middleware([
    'cache.headers:no_store;no_cache;must_revalidate;max_age=0',
])
    ->group(static function (): void {
        Route::get('/', static function (): RedirectResponse {
            return redirect()->route('home.view');
        });

        Route::controller(HomeController::class)
            ->prefix('/home')
            ->name('home.')
            ->group(static function (): void {
                Route::get('/', 'view')->name('view');
            });

        Route::controller(LoginController::class)
            ->prefix('auth')
            ->group(static function (): void {
                Route::get('/login', 'showLoginForm')->name('login');
                Route::post('/login', 'authenticate')->name('authenticate');
                Route::post('/logout', 'logout')->name('logout');
            });

        Route::middleware(['auth'])->group(static function (): void {
            Route::controller(DentistController::class)
                ->prefix('/dentists')
                ->name('dentists.')
                ->group(static function (): void {
                    Route::get('', 'list')->name('list');
                    Route::get('/add', 'showAddDentistForm')->name('addDentist-form');
                    Route::post('/add', 'add')->name('add');
                    Route::prefix('/{dentist}')->group(static function (): void {
                        Route::get('', 'view')->name('view');
                        Route::get('/update', 'showUpdateForm')->name('update-form');
                        Route::post('/update', 'update')->name('update');
                        Route::post('/delete', 'delete')->name('delete');
                        Route::prefix('/patients')->group(static function (): void {
                            Route::get('/patients', 'viewPatients',)->name('view-patients');
                        });
                        Route::prefix('/appointments')->group(static function (): void {
                            Route::get('/appointments', 'viewAppointments',)->name('view-appointments');
                        });
                    });
                });

            Route::controller(PatientController::class)
                ->prefix('/patients')
                ->name('patients.')
                ->group(static function (): void {
                    Route::get('', 'list')->name('list');
                    Route::get('/add', 'showAddPatientForm')->name('addPatient-form');
                    Route::post('/add', 'add')->name('add');
                    Route::prefix('/{patient}')->group(static function (): void {
                        Route::get('', 'view')->name('view');
                        Route::get('/update', 'showUpdateForm')->name('update-form');
                        Route::post('/update', 'update')->name('update');
                        Route::prefix('/dentists')->group(static function (): void {
                            Route::get('/dentists', 'viewDentists',)->name('view-dentists');
                        });
                        Route::prefix('/appointments')->group(static function (): void {
                            Route::get('/appointments', 'viewAppointments',)->name('view-appointments');
                        });
                    });
                });

            Route::controller(AppointmentController::class)
                ->prefix('/appointments')
                ->name('appointments.')
                ->group(static function (): void {
                    Route::get('', 'list')->name('list');
                    Route::get('/create', 'showCreateForm')->name('create-form');
                    Route::post('/create', 'create')->name('create');
                    Route::prefix('/{appointment}')->group(static function (): void {
                        Route::get('', 'view')->name('view');
                        Route::get('/update', 'showUpdateForm')->name('update-form');
                        Route::post('/update', 'update')->name('update');
                        Route::post('/delete', 'delete')->name('delete');
                        Route::get('/pdf', 'downloadPdf')->name('pdf');
                    });
                });

            Route::controller(UserController::class)
                ->prefix('/users')
                ->name('users.')
                ->group(static function (): void {
                    Route::get('', 'list')->name('list');
                    Route::get('/create', 'showCreateForm')->name('create-form');
                    Route::post('/create', 'create')->name('create');
                    Route::prefix('/self')->group(static function (): void {
                        Route::get('', 'selfView')->name('selves.view');
                        Route::get('/update', 'showSelfUpdateForm')->name('selves.update-form');
                        Route::post('/update', 'selfUpdate')->name('selves.update');
                    });
                    Route::prefix('/{user}')->group(static function (): void {
                        Route::get('', 'view')->name('view');
                        Route::get('/update', 'showUpdateForm')->name('update-form');
                        Route::post('/update', 'update')->name('update');
                    });
                });
        });
    });
