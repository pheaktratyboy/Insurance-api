<?php

use App\Http\Controllers\SubscriberController;
use App\Http\Controllers\SubscriberPolicyController;
use Illuminate\Support\Facades\Route;

/** Subscribers process */
Route::get('subscribers', [SubscriberController::class,'index'])->name('subscribers.index');
Route::get('subscribers/{subscriber}', [SubscriberController::class,'show'])->name('subscribers.show');
Route::get('users/subscribers/{user}', [SubscriberController::class,'showSubscribeByUser'])->name('show_subscribers_by_user');


Route::post('subscribers', [SubscriberController::class,'store'])->name('subscribers.store');
Route::put('subscribers/{subscriber}', [SubscriberController::class,'update'])->name('subscribers.update');


/** Subscriber Policies process */
Route::get('subscriber-policies', [SubscriberPolicyController::class,'index'])->name('subscriber_policies.index');
Route::get('subscriber-policies/{subscriber_policy}', [SubscriberPolicyController::class,'show'])->name('subscriber_policies.show');

Route::post('subscriber-policies/{subscriber}', [SubscriberPolicyController::class,'store'])->name('subscriber_policies.store');
Route::put('subscriber-policies/{subscriber_policy}', [SubscriberPolicyController::class,'update'])->name('subscriber_policies.update');

