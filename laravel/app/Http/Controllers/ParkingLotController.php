<?php

namespace App\Http\Controllers;

use App\Models\ParkingLot;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ParkingLotController extends Controller
{
    // Return all parking places
    public function getParkingPlaces()
    {
        return ParkingLot::all();
    }

    // Free the reservation
    public function exitParkingLot(Request $request)
    {
        $ticket = Ticket::find($request->id);

        if ($ticket) {
            $ticket->parking_lot()->update([
                'reserved' => false,
            ]);
        }

        // Notify the users when a parking lot is available
        $users = User::all();
        if ($users) {
            foreach ($users as $user) {
                Mail::send('mail-template.notification', [], function ($message) use ($user) {
                    $message->from('noreply@cyp.com', 'CYP');
                    $message->to($user->email)->subject('New Available Place');
                });
            }
        }
    }
}
