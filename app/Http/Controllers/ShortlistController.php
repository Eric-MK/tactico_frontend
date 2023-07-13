<?php

namespace App\Http\Controllers;

use App\Models\Shortlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShortlistController extends Controller
{
    public function index() {

        if (!Auth::check()) {
            return redirect('/login');
        }

        $userId = Auth::id();

        $shortlistedPlayers = Shortlist::where('user_id', $userId)->get();

        return view('frontend.ShortlistPage', compact('shortlistedPlayers'));
    }

    public function store(Request $request)
    {
        if (!Auth::check()) {
            return redirect('/login');
        }

        $data = $request->only('player_name', 'position', 'competition', 'age', 'player_type');
        $data['user_id'] = Auth::user()->id; // get the current user's id

        $shortlistedPlayer = Shortlist::firstOrCreate($data);

        // If the model already exists in the database the wasRecentlyCreated property will be false.
        if ($shortlistedPlayer->wasRecentlyCreated) {
            return redirect()->route('dashboard')->with('success', 'Player has been added to your shortlist.'); // home should be a named route in your web.php
        } else {
            return redirect()->route('dashboard')->with('error', 'Player is already in your shortlist.'); // home should be a named route in your web.php
        }
    }
    public function destroy($id)
    {

        if (!Auth::check()) {
            return redirect('/login');
        }
        
        // Get the player from the shortlist
        $player = Shortlist::find($id);

        // Check if the player exists and if it belongs to the authenticated user
        if($player && $player->user_id == Auth::user()->id) {
            $player->delete();

            // After deletion, redirect the user to the shortlist page with a success message
            return redirect()->back()->with('success', 'Player has been removed from your shortlist.');
        }

        // If the player doesn't exist or doesn't belong to the authenticated user, redirect with an error message
        return redirect()->back()->with('error', 'Unable to remove player from shortlist.');
    }


}

