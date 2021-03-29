<?php

use App\Http\Controllers\ParkingLotController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\TicketController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('get-parking-places', [ParkingLotController::class, 'getParkingPlaces']);
Route::post('exit-parking-lot', [ParkingLotController::class, 'exitParkingLot']);
Route::post('reserve-place', [TicketController::class, 'reservePlace']);
Route::post('ticket-details', [TicketController::class, 'ticketDetails']);
Route::post('pay-ticket', [TicketController::class, 'payTicket']);
Route::post('save-extra-pay', [TicketController::class, 'saveExtraPay']);
Route::post('register', [RegisterController::class, 'register']);
