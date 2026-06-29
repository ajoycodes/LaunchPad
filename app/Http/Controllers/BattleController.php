<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BattleController extends Controller
{
    public function show()
    {
        $battle = \App\Models\Battle::current();

        $previous = \App\Models\Battle::where('ends_at', '<', now())
            ->with(['productA.user', 'productB.user'])
            ->latest('ends_at')
            ->take(3)
            ->get();

        $userVote = null;
        if ($battle && auth()->check()) {
            $userVote = $battle->userVote(auth()->id());
        }

        return view('battles.show', compact('battle', 'previous', 'userVote'));
    }

    public function vote(Request $request, \App\Models\Battle $battle)
    {
        if (!$battle->isActive()) {
            return response()->json(['error' => 'Battle is not active.'], 422);
        }

        $data = $request->validate(['side' => ['required', 'in:a,b']]);

        $existing = $battle->userVote($request->user()->id);

        if ($existing) {
            if ($existing->voted_for === $data['side']) {
                // Remove vote
                if ($data['side'] === 'a') {
                    $battle->decrement('votes_a');
                } else {
                    $battle->decrement('votes_b');
                }
                $existing->delete();
            } else {
                // Switch vote
                if ($data['side'] === 'a') {
                    $battle->increment('votes_a');
                    $battle->decrement('votes_b');
                } else {
                    $battle->decrement('votes_a');
                    $battle->increment('votes_b');
                }
                $existing->update(['voted_for' => $data['side']]);
            }
        } else {
            $battle->votes()->create([
                'user_id'   => $request->user()->id,
                'voted_for' => $data['side'],
            ]);
            if ($data['side'] === 'a') {
                $battle->increment('votes_a');
            } else {
                $battle->increment('votes_b');
            }
        }

        $battle->refresh();

        return response()->json([
            'votes_a'   => $battle->votes_a,
            'votes_b'   => $battle->votes_b,
            'percent_a' => $battle->percentA(),
            'percent_b' => $battle->percentB(),
        ]);
    }
}
