<?php

namespace App\Http\Controllers;

use App\Models\ParkingLot;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class TicketController extends Controller
{
    // Reserve a place and issue a ticket
    public function reservePlace(Request $request)
    {
        $ticket = Ticket::orderBy('created_at', 'desc')->first();
        $ticket_number = 2000;
        if ($ticket !== null) {
            $ticket_number = $ticket->ticket_number + 1;
        }
        $created = Ticket::create([
            'parking_lot_id' => $request->id,
            'vehicle_plate' => $request->vehicle_plate,
            'ticket_number' => $ticket_number,
            'length' => $request->length,
            'price' => $request->price
        ]);
        if ($created) {
            // Change status of parking place
            ParkingLot::whereId($request->id)->update([
                'reserved' => true
            ]);
            return $ticket_number;
        }
    }

    // Get ticket details by ticket number
    public function ticketDetails(Request $request)
    {
        return Ticket::whereTicket_number($request->number)->first();
    }

    // Pay the ticket
    public function payTicket(Request $request)
    {
        Ticket::find($request->id)->update([
            'payment_status' => true
        ]);
    }


    // Save extra pay
    public function saveExtraPay(Request $request)
    {
        Ticket::find($request->id)->update([
            'extra_pay' => $request->pay
        ]);
    }
}
