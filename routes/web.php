<?php


use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');


    Route::livewire('/entries', 'entries.index')->name('entries.index');

    Route::livewire('/entries/create', 'entries.create')->name('entries.create');

    Route::livewire('/entries/{entry}', 'entries.show')->name('entries.show');

    Route::livewire('/entries/{entry}/edit', 'entries.edit')->name('entries.edit');
});

require __DIR__ . '/settings.php';
