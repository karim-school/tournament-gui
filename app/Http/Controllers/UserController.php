<?php

namespace App\Http\Controllers;

use App\Enums\Membership;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class UserController extends Controller
{
    public function upgrade(User $user): RedirectResponse
    {
        /** @var User $authUser */
        $authUser = auth()->user();

        if ($authUser == null || ! $authUser->is_admin) {
            return redirect()->back();
        }

        $currentMembership = $user->membership;

        switch ($user->membership) {
            case Membership::GUEST:
                $user->membership = Membership::MEMBER;
                break;
            default:
                return redirect()->back();
        }

        Log::info("Upgrading membership of user $user->id from $currentMembership->value to {$user->membership->value}");

        $user->save();

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Membership upgraded.')]);

        return redirect()->back();
    }
}
