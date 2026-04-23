<?php


use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::livewire('/entries', 'pages::entry.index')->name('entries.index');

    Route::livewire('/entries/trash', 'pages::entry.trash')->name('entries.trash');

    Route::livewire('/entries/create', 'pages::entry.create')->name('entries.create');

    Route::livewire('/entries/{entry:slug}', 'pages::entry.show')->name('entries.show');

    Route::livewire('/entries/{entry:slug}/edit', 'pages::entry.edit')->name('entries.edit');
});

require __DIR__ . '/settings.php';
