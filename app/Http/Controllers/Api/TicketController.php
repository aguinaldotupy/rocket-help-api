<?php

namespace App\Http\Controllers\Api;

use App\Enum\StatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\TicketStoreRequest;
use App\Models\Ticket;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TicketController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function index(Request $request): JsonResponse
    {
        $request->validate([
            'perPage' => 'integer|min:1|max:100',
            'page' => 'integer|min:1',
        ]);

        return response()->json(
            Ticket::where('created_by', '=', $request->user()->id)->with([
                'comments',
                'comments',
            ])->orderBy('created_at', 'desc')
                ->paginate($request->integer('perPage', 10), page: $request->integer('page', 1))
        );
    }

    public function open(TicketStoreRequest $request): Response
    {
        $ticket = new Ticket();
        $ticket->fill($request->validated());
        $ticket->created_by = auth()->id();
        $ticket->status = StatusEnum::OPEN;
        $ticket->save();

        return response()->noContent();
    }

    public function show(Ticket $ticket, Request $request): JsonResponse
    {
        if ($ticket->created_by !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json($ticket->load('comments'));
    }

    public function close(Request $request, Ticket $ticket): Response
    {
        $request->validate([
            'solution' => 'required|string|max:255',
        ]);

        $ticket->comments()->create([
            'comment' => $request->input('solution'),
            'commented_by' => $request->user()->id,
        ]);

        $ticket->status = StatusEnum::CLOSED;
        $ticket->closed_at = now();

        $ticket->save();

        return response()->noContent();
    }
}
