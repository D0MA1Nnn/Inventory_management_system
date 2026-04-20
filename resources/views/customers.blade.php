@extends('layouts.app')

@section('title','Customers')

@section('content')

<div class="space-y-6">

    <!-- HEADER -->
    <div>
        <h1 class="text-2xl font-bold text-gray-900"></h1>
    </div>

    <!-- STATS -->
    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
        <div class="bg-white p-4 rounded-xl shadow">
            <p class="text-xl font-bold text-blue-600" id="totalCustomers">0</p>
            <p class="text-xs text-gray-500">Total Customers</p>
        </div>

        <div class="bg-white p-4 rounded-xl shadow">
            <p class="text-xl font-bold text-green-600" id="newCustomers">0</p>
            <p class="text-xs text-gray-500">New (7 days)</p>
        </div>
    </div>

    <!-- TABLE -->
    <div class="bg-white rounded-xl shadow overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="p-3 text-left text-xs">Name</th>
                    <th class="p-3 text-left text-xs">Email</th>
                    <th class="p-3 text-left text-xs">Joined</th>
                </tr>
            </thead>
            <tbody id="customerTable">
                <tr>
                    <td colspan="3" class="text-center py-6 text-gray-500">Loading...</td>
                </tr>
            </tbody>
        </table>
    </div>

</div>

<script>
async function loadCustomers() {
    const res = await fetch('/api/customers');
    const data = await res.json();

    document.getElementById('customerTable').innerHTML = data.map(c => `
        <tr class="border-b">
            <td class="p-3">${c.name}</td>
            <td class="p-3">${c.email}</td>
            <td class="p-3">${new Date(c.created_at).toLocaleDateString()}</td>
        </tr>
    `).join('');
}

async function loadStats() {
    const res = await fetch('/api/customers/stats');
    const stats = await res.json();

    document.getElementById('totalCustomers').innerText = stats.total;
    document.getElementById('newCustomers').innerText = stats.new;
}

loadCustomers();
loadStats();
</script>

@endsection