<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\PortfolioController;
use App\Http\Controllers\Admin\SkillController;
use App\Http\Controllers\Admin\ExperienceController;
use App\Http\Controllers\Admin\CertificateController;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\MessageController;
use App\Http\Controllers\Admin\SettingController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/portfolio', [HomeController::class, 'portfolio'])->name('portfolio');
Route::get('/portfolio/{slug}', [HomeController::class, 'portfolioDetail'])->name('portfolio.detail');
Route::get('/skills', [HomeController::class, 'skills'])->name('skills');
Route::get('/experiences', [HomeController::class, 'experiences'])->name('experiences');
Route::get('/certificates', [HomeController::class, 'certificates'])->name('certificates');
Route::get('/blog', [HomeController::class, 'blog'])->name('blog');
Route::get('/blog/{slug}', [HomeController::class, 'blogDetail'])->name('blog.detail');
Route::post('/blog/{id}/comment', [HomeController::class, 'storeComment'])->name('blog.comment');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::post('/contact', [HomeController::class, 'storeContact'])->name('contact.store');

Route::get('/storage/{path}', function ($path) {
    return redirect(\Illuminate\Support\Facades\Storage::disk('public')->url($path));
})->where('path', '.*');

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Admin Dashboard Routes (Protected)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    // 1. Dashboard Landing
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/backup', [DashboardController::class, 'backup'])->name('backup');
    Route::get('/logs', [DashboardController::class, 'logs'])->name('logs');

    // 2. Profile CRUD (Manage Profil, CV, Foto)
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // 3. Portfolio CRUD
    Route::resource('portfolios', PortfolioController::class)->except(['show']);
    
    // 4. Skill CRUD
    Route::resource('skills', SkillController::class)->except(['show', 'create', 'edit']);

    // 5. Experience CRUD
    Route::resource('experiences', ExperienceController::class)->except(['show']);

    // 6. Certificate CRUD
    Route::resource('certificates', CertificateController::class)->except(['show']);

    // 7. Blog CRUD
    Route::resource('blogs', BlogController::class)->except(['show']);
    Route::delete('/blogs/comments/{id}', [BlogController::class, 'deleteComment'])->name('blogs.comments.delete');
    Route::post('/blogs/comments/{id}/approve', [BlogController::class, 'approveComment'])->name('blogs.comments.approve');

    // 8. Contact Messages (Kelola Pesan Kontak)
    Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');
    Route::post('/messages/{id}/read', [MessageController::class, 'markAsRead'])->name('messages.read');
    Route::delete('/messages/{id}', [MessageController::class, 'destroy'])->name('messages.destroy');
    Route::get('/messages/export/csv', [MessageController::class, 'exportCsv'])->name('messages.export.csv');
    Route::get('/messages/export/pdf', [MessageController::class, 'exportPdf'])->name('messages.export.pdf');

    // 9. Site Settings (Only Admin can manage, using custom gate/role checks)
    Route::middleware(['can:manage-settings'])->group(function() {
        Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
        Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');
    });
});
