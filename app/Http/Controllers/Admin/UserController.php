<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        // Search
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        // Role filter
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Status filter
        if ($request->status === 'active') {
            $query->withoutTrashed();
        } else if ($request->status === 'deleted') {
            $query->onlyTrashed();
        } else {
            $query->withTrashed();
        }

        $users = $query->latest()->paginate(10)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|in:user,admin'
        ]);

        // Prevent admin demoting themselves accidentally
        if (auth()->id() === $user->id) {
            return back()->withErrors('You cannot change your own role!');
        }

        $user->update([
            'role' => $request->role
        ]);

        AuditLog::create([
            'admin_id' => auth()->id(),
            'target_user_id' => $user->id,
            'action' => "Changed role to {$request->role}"
        ]);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User updated!');
    }

    public function destroy(User $user)
    {
        if (auth()->id() === $user->id) {
            return back()->withErrors('You cannot delete yourself!');
        }

        AuditLog::create([
            'admin_id' => auth()->id(),
            'target_user_id' => $user->id,
            'action' => "Soft deleted user"
        ]);

        $user->delete();

        return back()->with('success', 'User soft deleted!');
    }

    public function restore($id)
    {
        $user = User::onlyTrashed()->findOrFail($id);

        $user->restore();

        AuditLog::create([
            'admin_id' => auth()->id(),
            'target_user_id' => $user->id,
            'action' => "Restored user"
        ]);

        return back()->with('success', 'User restored!');
    }
}
