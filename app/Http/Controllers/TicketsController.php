<?php

namespace App\Http\Controllers;

use App\Ticket;
use Illuminate\Http\Request;
use App\Http\Resources\Ticket as TicketResource;
use Illuminate\Validation\Rules\In;

class TicketsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tickets = Ticket::all();
        return new TicketResource($tickets);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $attributes = [
            'name' => ['required', 'min:3', 'max:190'],
            'description' => ['required', 'min:3'],
            'type_id' => ['required', 'exists:types,id'],
            'price' => ['required', 'digits_between:2,10'],
        ];

        $attributes = $request->validate($attributes);

        $ticket = Ticket::create($attributes);
        return (new TicketResource($ticket))->additional(['meta' => [
            'Ok' => '201',
        ]]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Ticket  $ticket
     * @return \Illuminate\Http\Response
     */
    public function show(Ticket $ticket)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Ticket  $ticket
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Ticket $ticket)
    {

        if ($ticket->isClean()) {
            return response()->json(['error' => 'You need to specify a different value to update', 'code' => 422], 422);
        }

        $attributes = [
            'type_id' => ['exists:types,id'],
            'price' => ['digits_between:2,10'],
        ];

        $attributes = $request->validate($attributes);
        $ticket->save();
        return new TicketResource($ticket);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Ticket  $ticket
     * @return \Illuminate\Http\Response
     */
    public function destroy(Ticket $ticket)
    {
        $ticket->delete();
        return new TicketResource($ticket);
    }
}
