<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class StaffController extends Controller
{
    public function index()
    {
        return view('staff');
    }

    public function getStaff()
    {
        return User::whereIn('role', ['admin', 'manager', 'staff'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getStats()
    {
        $users = User::whereIn('role', ['admin','manager','staff'])->get();

        return response()->json([
            'total' => $users->count(),
            'active' => $users->where('status', 'active')->count(),
            'inactive' => $users->where('status', 'inactive')->count(),
            'suspended' => $users->where('status', 'suspended')->count(),
            'admin' => $users->where('role', 'admin')->count(),
            'manager' => $users->where('role', 'manager')->count(),
            'staff' => $users->where('role', 'staff')->count(),
        ]);
    }

    public function show($id)
    {
        $user = User::findOrFail($id);

        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
            'status' => $user->status ?? 'active',
            'phone' => $user->phone ?? '',
            'position' => $user->position ?? '',
            'salary' => $user->salary ?? '',
            'hire_date' => $user->hire_date ?? '',
            'address' => $user->address ?? '',
            'profile_image' => $user->profile_image ?? null,
            'created_at' => $user->created_at,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role' => 'required',
        ]);

        $data = $request->all();
        $data['password'] = Hash::make($request->password);

        if ($request->hasFile('profile_image')) {
            $data['profile_image'] = $request->file('profile_image')->store('staff', 'public');
        }

        return User::create($data);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $data = $request->all();

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        } else {
            unset($data['password']);
        }

        if ($request->hasFile('profile_image')) {
            if ($user->profile_image) {
                Storage::disk('public')->delete($user->profile_image);
            }
            $data['profile_image'] = $request->file('profile_image')->store('staff', 'public');
        }

        $user->update($data);

        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if ($user->profile_image) {
            Storage::disk('public')->delete($user->profile_image);
        }

        $user->delete();

        return response()->json(['success' => true]);
    }
}