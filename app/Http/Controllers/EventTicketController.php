<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Ticket;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Log;



class EventTicketsController extends Controller
{
   
    public function showTickets(Request $request, $event_id)
    {
        $allowed_sorts = [
            'created_at'    => 'Creation date',
            'title'         => 'Ticket title',
            'quantity_sold' => 'Quantity sold',
            'sort_order'  => 'Custom Sort Order',
        ];
        $q = $request->get('q', '');
        $sort_by = $request->get('sort_by');
        if (isset($allowed_sorts[$sort_by]) === false) {
            $sort_by = 'sort_order';
        }
        $event = Event::scope()->find($event_id);
        if ($event === null) {
            abort(404);
        }
        $tickets = empty($q) === false
            ? $event->tickets()->where('title', 'like', '%' . $q . '%')->orderBy($sort_by, 'asc')->paginate()
            : $event->tickets()->orderBy($sort_by, 'asc')->paginate();

        return view('ManageEvent.Tickets', compact('event', 'tickets', 'sort_by', 'q', 'allowed_sorts'));
    }

    public function showEditTicket($event_id, $ticket_id)
    {
        $data = [
            'event'  => Event::scope()->find($event_id),
            'ticket' => Ticket::scope()->find($ticket_id),
        ];

        return view('ManageEvent.Modals.EditTicket', $data);
    }

    
    public function showCreateTicket($event_id)
    {
        return view('ManageEvent.Modals.CreateTicket', [
            'event' => Event::scope()->find($event_id),
        ]);
    }
    public
    

     function postCreateTicket(Request $request, $event_id)
    {
        $ticket = Ticket::createNew();

        if (!$ticket->validate($request->all())) {
            return response()->json([
                'status'   => 'error',
                'messages' => $ticket->errors(),
            ]);
        }

        

        session()->flash('message', trans('controllermessages.successfully-created-ticket'));

        return response()->json([
            'status'      => 'success',
            'id'          => $ticket->id,
            'message'     => 'Refreshing...',
            'redirectUrl' => route('showEventTickets', [
                'event_id' => $event_id,
            ]),
        ]);
    }

    
    public function postPauseTicket(Request $request)
    {
        $ticket_id = $request->get('ticket_id');

        $ticket = Ticket::scope()->find($ticket_id);

        $ticket->is_paused = ($ticket->is_paused == 1) ? 0 : 1;

        if ($ticket->save()) {
            return response()->json([
                'status'  => 'success',
                'message' => trans('controllermessages.successfully-updated-ticket'),
                'id'      => $ticket->id,
            ]);
        }

        Log::error('Ticket Failed to pause/resume', [
            'ticket' => $ticket,
        ]);

        return response()->json([
            'status'  => 'error',
            'id'      => $ticket->id,
            'message' => trans('controllermessages.woop-wrong'),
        ]);
    }

   
    public function postDeleteTicket(Request $request)
    {
        $ticket_id = $request->get('ticket_id');

        $ticket = Ticket::scope()->find($ticket_id);

       
        if ($ticket->quantity_sold > 0) {
            return response()->json([
                'status'  => 'error',
                'message' => trans('controllermessages.cannot-delete-ticket'),
                'id'      => $ticket->id,
            ]);
        }

        if ($ticket->delete()) {
            return response()->json([
                'status'  => 'success',
                'message' => trans('controllermessages.cannot-delete-ticket'),
                'id'      => $ticket->id,
            ]);
        }

        Log::error(trans('controllermessages.ticket-failed-delete'), [
            'ticket' => $ticket,
        ]);

        return response()->json([
            'status'  => 'error',
            'id'      => $ticket->id,
            'message' => trans('controllermessages.woop-wrong'),
        ]);
    }

    
    public function postEditTicket(Request $request, $event_id, $ticket_id)
    {
        $ticket = Ticket::scope()->findOrFail($ticket_id);

       
        $validation_rules['quantity_available'] = [
            'integer',
            'min:' . ($ticket->quantity_sold + $ticket->quantity_reserved)
        ];
        $validation_messages['quantity_available.min'] = trans('controllermessages.quantity-available-cannot');

        $ticket->rules = $validation_rules + $ticket->rules;
        $ticket->messages = $validation_messages + $ticket->messages;

        if (!$ticket->validate($request->all())) {
            return response()->json([
                'status'   => 'error',
                'messages' => $ticket->errors(),
            ]);
        }
        return response()->json([
            'status'      => 'success',
            'id'          => $ticket->id,
            'redirectUrl' => route('showEventTickets', [
                'event_id' => $event_id,
            ]),
        ]);
    }

    
}
