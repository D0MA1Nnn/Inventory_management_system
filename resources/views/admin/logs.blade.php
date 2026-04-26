@extends('layouts.app')

@section('title', 'Activity Logs')

@section('content')
    @php
        $tableReady = $tableReady ?? true;
    @endphp

    <div class="space-y-6">
        @unless($tableReady)
            <div class="rounded-2xl border border-amber-200 bg-amber-50 px-5 py-4 text-amber-900 shadow-sm">
                <h3 class="text-sm font-semibold uppercase tracking-wide">Migration Required</h3>
                <p class="mt-2 text-sm">
                    The activity log table is not available yet. Run <span class="rounded bg-amber-100 px-2 py-1 font-mono text-xs">php artisan migrate</span>
                    to enable login and logout tracking in this environment.
                </p>
            </div>
        @endunless

        <!-- STATS CARDS - AT THE TOP -->
        <div class="grid gap-4 md:grid-cols-3">
            <div class="rounded-2xl bg-gradient-to-br from-slate-900 to-slate-700 p-5 text-white shadow-lg">
                <p class="text-sm text-slate-300">Visible Results</p>
                <p class="mt-3 text-3xl font-bold">{{ $tableReady ? $logs->total() : 0 }}</p>
                <p class="mt-2 text-sm text-slate-300">Filtered activity records</p>
            </div>

            <div class="rounded-2xl border border-emerald-100 bg-emerald-50 p-5 shadow-sm">
                <p class="text-sm text-emerald-700">Logins On This Page</p>
                <p class="mt-3 text-3xl font-bold text-emerald-900">{{ $tableReady ? $logs->getCollection()->where('action', 'login')->count() : 0 }}</p>
                <p class="mt-2 text-sm text-emerald-700">Successful authentications</p>
            </div>

            <div class="rounded-2xl border border-rose-100 bg-rose-50 p-5 shadow-sm">
                <p class="text-sm text-rose-700">Logouts On This Page</p>
                <p class="mt-3 text-3xl font-bold text-rose-900">{{ $tableReady ? $logs->getCollection()->where('action', 'logout')->count() : 0 }}</p>
                <p class="mt-2 text-sm text-rose-700">Ended authenticated sessions</p>
            </div>
        </div>

        <!-- FILTER FORM - FULL WIDTH -->
        <div class="w-full">
            <form method="GET" action="{{ route('admin.logs.index') }}" class="grid gap-3 rounded-2xl border border-gray-200 bg-white p-5 shadow-sm sm:grid-cols-4">
                <div>
                    <label for="role" class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-500">Role</label>
                    <select
                        id="role"
                        name="role"
                        class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm text-gray-700 outline-none transition focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-100"
                    >
                        <option value="">All roles</option>
                        <option value="admin" @selected(($filters['role'] ?? '') === 'admin')>Admin</option>
                        <option value="manager" @selected(($filters['role'] ?? '') === 'manager')>Manager</option>
                        <option value="staff" @selected(($filters['role'] ?? '') === 'staff')>Staff</option>
                    </select>
                </div>

                <div>
                    <label for="action" class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-500">Action</label>
                    <select
                        id="action"
                        name="action"
                        class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm text-gray-700 outline-none transition focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-100"
                    >
                        <option value="">All actions</option>
                        <option value="login" @selected(($filters['action'] ?? '') === 'login')>Login</option>
                        <option value="logout" @selected(($filters['action'] ?? '') === 'logout')>Logout</option>
                    </select>
                </div>

                <div>
                    <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-500">&nbsp;</label>
                    <button
                        type="submit"
                        class="inline-flex w-full items-center justify-center rounded-xl bg-gray-900 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-gray-800"
                    >
                        Apply Filters
                    </button>
                </div>

                <div>
                    <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-500">&nbsp;</label>
                    <a
                        href="{{ route('admin.logs.index') }}"
                        class="inline-flex w-full items-center justify-center rounded-xl border border-gray-200 px-4 py-2.5 text-sm font-semibold text-gray-600 transition hover:bg-gray-50"
                    >
                        Reset
                    </a>
                </div>
            </form>
        </div>

        <!-- AUDIT TRAIL TABLE -->
        <div class="overflow-hidden rounded-3xl border border-gray-200 bg-white shadow-sm">
            <div class="border-b border-gray-100 px-6 py-4">
                <h3 class="text-lg font-semibold text-gray-900">Audit Trail</h3>
                <p class="text-sm text-gray-500">Sorted from newest to oldest.</p>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Date / Time</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Name</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Email</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Role</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        @forelse($logs as $log)
                            <tr class="transition hover:bg-gray-50">
                                <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-700">
                                    <div class="font-semibold text-gray-900">{{ $log->created_at->format('M d, Y') }}</div>
                                    <div class="text-xs text-gray-500">{{ $log->created_at->format('h:i:s A') }}</div>
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-gray-900">{{ $log->name }}</td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-600">{{ $log->email }}</td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm">
                                    <span class="inline-flex rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-blue-700">
                                        {{ $log->role }}
                                    </span>
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm">
                                    <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold uppercase tracking-wide {{ $log->action === 'login' ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700' }}">
                                        {{ $log->action }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <div class="mx-auto max-w-md">
                                        <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-gray-100">
                                            <svg class="h-7 w-7 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                        </div>
                                        <h4 class="mt-4 text-lg font-semibold text-gray-900">No activity logs found</h4>
                                        <p class="mt-2 text-sm text-gray-500">
                                            Try changing the filters or log in and out with a staff account to generate fresh records.
                                        </p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($tableReady && $logs->hasPages())
                <div class="border-t border-gray-100 px-6 py-4">
                    {{ $logs->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection