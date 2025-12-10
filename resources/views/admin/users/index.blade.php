@extends('layouts.admin')

@section('title', 'User Management')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-bold">User Management</h2>
    <a href="{{ route('admin.users.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700">
        + Tambah User
    </a>
</div>

<!-- Filter Tabs -->
<div class="bg-white rounded-lg shadow-md mb-6">
    <div class="border-b">
        <nav class="flex space-x-4 px-6">
            <a href="{{ route('admin.users.index', ['role' => 'all']) }}" 
               class="py-4 px-3 border-b-2 font-medium text-sm {{ $role == 'all' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
                All Users ({{ $counts['all'] }})
            </a>
            <a href="{{ route('admin.users.index', ['role' => 'customer']) }}" 
               class="py-4 px-3 border-b-2 font-medium text-sm {{ $role == 'customer' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
                Customers ({{ $counts['customer'] }})
            </a>
            <a href="{{ route('admin.users.index', ['role' => 'admin']) }}" 
               class="py-4 px-3 border-b-2 font-medium text-sm {{ $role == 'admin' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
                Admins ({{ $counts['admin'] }})
            </a>
            <a href="{{ route('admin.users.index', ['role' => 'super_admin']) }}" 
               class="py-4 px-3 border-b-2 font-medium text-sm {{ $role == 'super_admin' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
                Super Admins ({{ $counts['super_admin'] }})
            </a>
        </nav>
    </div>
</div>

<!-- Users Table -->
<div class="bg-white rounded-lg shadow-md overflow-hidden">
    <table class="w-full">
        <thead class="bg-gray-50">
            <tr>
                <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">User</th>
                <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Email</th>
                <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Role</th>
                <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Phone</th>
                <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Joined</th>
                <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Action</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @forelse($users as $user)
                <tr class="{{ $user->id == Auth::id() ? 'bg-blue-50' : '' }}">
                    <td class="px-6 py-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10 bg-indigo-100 rounded-full flex items-center justify-center">
                                <span class="text-indigo-600 font-bold">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                            </div>
                            <div class="ml-4">
                                <div class="font-medium text-gray-900">
                                    {{ $user->name }}
                                    @if($user->id == Auth::id())
                                        <span class="ml-2 text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded">You</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500">{{ $user->email }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs rounded-full
                            {{ $user->role == 'super_admin' ? 'bg-purple-100 text-purple-800' : '' }}
                            {{ $user->role == 'admin' ? 'bg-blue-100 text-blue-800' : '' }}
                            {{ $user->role == 'customer' ? 'bg-green-100 text-green-800' : '' }}">
                            {{ ucfirst(str_replace('_', ' ', $user->role)) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500">{{ $user->phone ?: '-' }}</td>
                    <td class="px-6 py-4 text-sm text-gray-500">{{ $user->created_at->format('d M Y') }}</td>
                    <td class="px-6 py-4">
                        <div class="flex space-x-2">
                            <a href="{{ route('admin.users.show', $user) }}" class="text-indigo-600 hover:text-indigo-900 text-sm">View</a>
                            
                            @if($user->id != Auth::id())
                                <a href="{{ route('admin.users.edit', $user) }}" class="text-blue-600 hover:text-blue-900 text-sm">Edit</a>
                                
                                @if(!$user->isSuperAdmin() || Auth::user()->isSuperAdmin())
                                    <form method="POST" action="{{ route('admin.users.destroy', $user) }}" 
                                          onsubmit="return confirm('Yakin ingin menghapus user ini? Aksi ini tidak bisa dibatalkan!')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 text-sm">Delete</button>
                                    </form>
                                @endif
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">No users found</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Pagination -->
<div class="mt-4">
    {{ $users->links() }}
</div>
@endsection