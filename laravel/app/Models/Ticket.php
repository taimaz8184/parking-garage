<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $table = 'tickets';

    protected $fillable = ['parking_lot_id', 'ticket_number', 'vehicle_plate', 'payment_status', 'price', 'length', 'extra_pay'];

    public function parking_lot()
    {
        return $this->belongsTo(ParkingLot::class);
    }
}
