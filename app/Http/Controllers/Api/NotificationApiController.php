<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Backs the navbar bell's live badge. Authenticated via Sanctum's stateful
 * (cookie) mode, so the browser's existing session — not a bearer token —
 * is what authorizes these requests.
 */
class NotificationApiController extends Controller
{
    /**
     * How many unread notifications the current user has.
     */
    public function unreadCount(Request $request): JsonResponse
    {
        return response()->json([
            'count' => $request->user()->unreadNotificationsCount(),
        ]);
    }

    /**
     * The most recent notifications for the bell dropdown preview.
     */
    public function recent(Request $request): JsonResponse
    {
        $notifications = $request->user()
            ->notifications()
            ->latest()
            ->take(5)
            ->get()
            ->map(fn ($n) => [
                'id'         => $n->id,
                'type'       => $n->type,
                'message'    => $n->message,
                'link'       => $n->link,
                'is_read'    => $n->is_read,
                'created_at' => $n->created_at->diffForHumans(),
            ]);

        return response()->json(['notifications' => $notifications]);
    }
}
