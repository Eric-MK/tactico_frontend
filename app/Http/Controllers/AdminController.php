<?php

namespace App\Http\Controllers;
use App\Models\User;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function viewVerifiedAccounts()
    {
        $verifiedUsers = User::where('is_verified', 1)
                             ->where('role', '!=', 'admin')
                             ->get();
        return view('Admin.admin', compact('verifiedUsers'));
    }


public function viewNonVerifiedAccounts()
{
    $nonverifiedUsers = User::where('is_verified', 0)->get();
    return view('Admin.adminNoneV', compact('nonverifiedUsers'));
}

public function viewDeletedAccounts()
{
    $deletedUsers = User::where('is_deleted', 1)->get();
    return view('Admin.adminD', compact('deletedUsers'));
}

public function unverify($id)
{
    // Retrieve the user by ID
    $user = User::find($id);

    // Perform the unverification process
    if ($user) {
        $user->is_verified = 0;
        $user->save();
        return redirect()->route('admin.verified-accounts')->with('success', 'User unverified successfully.');
    } else {
        return redirect()->route('admin.verified-accounts')->with('error', 'User not found.');
    }
}

public function verify($id)
{
    // Retrieve the user by ID
    $user = User::find($id);

    // Perform the unverification process
    if ($user) {
        $user->is_verified = 1;
        $user->save();
        return redirect()->route('admin.nonverified-accounts')->with('success', 'User verified successfully.');
    } else {
        return redirect()->route('admin.nonverified-accounts')->with('error', 'User not found.');
    }
}

public function delete($id)
{
    // Retrieve the user by ID
    $user = User::find($id);

    // Perform the unverification process
    if ($user) {
        $user->delete();
        return redirect()->route('admin.deleted-accounts')->with('success', 'User deleted successfully.');
    } else {
        return redirect()->route('admin.deleted-accounts')->with('error', 'User not found.');
    }
}

}
