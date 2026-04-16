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
            'users' => RankUser::all(),
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

        die('add: ' . $validated['name']);
    }
}
