<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserManagementController extends Controller
{
    /**
     * Display a listing of users
     */
    public function index(Request $request)
    {
        $role = $request->role ?? 'all';

        $query = User::query();

        // Filter by role
        if ($role !== 'all') {
            $query->where('role', $role);
        }

        $users = $query->latest()->paginate(15);

        // Count by role
        $counts = [
            'all' => User::count(),
            'customer' => User::where('role', 'customer')->count(),
            'admin' => User::where('role', 'admin')->count(),
            'super_admin' => User::where('role', 'super_admin')->count(),
        ];

        return view('admin.users.index', compact('users', 'role', 'counts'));
    }

    /**
     * Show the form for creating a new user
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created user
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:customer,admin,super_admin',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil dibuat!');
    }

    /**
     * Display the specified user
     */
    public function show(User $user)
    {
        // Load relationships
        $user->load(['orders', 'products']);

        // Statistics
        $stats = [
            'total_orders' => $user->orders()->count(),
            'total_spent' => $user->orders()->whereIn('status', ['paid', 'completed'])->sum('total_amount'),
            'total_products' => $user->products()->count(),
        ];

        return view('admin.users.show', compact('user', 'stats'));
    }

    /**
     * Show the form for editing the specified user
     */
    public function edit(User $user)
    {
        // Super admin tidak boleh diedit oleh admin biasa
        if ($user->isSuperAdmin() && !Auth::user()->isSuperAdmin()) {
            abort(403, 'Cannot edit super admin account');
        }

        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified user
     */
    public function update(Request $request, User $user)
    {
        // Super admin tidak boleh diupdate oleh admin biasa
        if ($user->isSuperAdmin() && !Auth::user()->isSuperAdmin()) {
            abort(403, 'Cannot edit super admin account');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:customer,admin,super_admin',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'phone' => $request->phone,
            'address' => $request->address,
        ];

        // Update password only if provided
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil diupdate!');
    }

    /**
     * Remove the specified user
     */
    public function destroy(User $user)
    {
        // Prevent deleting own account
        if ($user->id === Auth::id()) {
            return redirect()->back()
                ->with('error', 'Anda tidak bisa menghapus akun Anda sendiri!');
        }

        // Super admin tidak boleh dihapus oleh admin biasa
        if ($user->isSuperAdmin() && !Auth::user()->isSuperAdmin()) {
            abort(403, 'Cannot delete super admin account');
        }

        // Check if user has orders
        if ($user->orders()->count() > 0) {
            return redirect()->back()
                ->with('error', 'User tidak bisa dihapus karena memiliki history order!');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil dihapus!');
    }

    /**
     * Toggle user status (active/inactive) - Future feature
     */
    public function toggleStatus(User $user)
    {
        // You can add 'is_active' column to users table
        // and implement suspend/activate functionality here
        
        return redirect()->back()
            ->with('info', 'Fitur suspend user belum diimplementasikan');
    }
}