<?php

namespace App\Http\Controllers;

use App\Models\RankUser;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Validator;

class UserController extends Controller
{
    public function index()
    {
        return Inertia::render('RankList', [
            'users' => RankUser::get([ 'id', 'name', 'points', 'wins', 'games' ])->map(function ($user) {
                $win_percentage = $user->games > 0 ? $user->wins / $user->games : 0;
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'points' => $user->points,
                    'win_percentage' => $win_percentage,
                ];
            }),
        ]);
    }

    public function view(RankUser $user)
    {
        $win_percentage = $user->games > 0 ? $user->wins / $user->games : 0;
        return Inertia::render('ViewUser', [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'points' => $user->points,
                'win_percentage' => $win_percentage,
            ],
        ]);
    }

    public function add(Request $request): void
    {
        /*
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:32',
        ]);

        if ($validator->fails()) {
            die('not valid');
        }

        $validated = $validator->validated();
        */

        $validated = $request->validate([
            'name' => 'required|max:32',
        ]);

        $user = new RankUser([
            'name'=> $validated['name'],
        ]);

        $user->save();
    }

    public function delete(RankUser $user)
    {
        $user->delete();
        return to_route('users.index');
    }
}
