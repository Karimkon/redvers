<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AgentController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with('station')
            ->where('role', 'agent');

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                ->orWhere('email', 'like', "%$search%")
                ->orWhere('phone', 'like', "%$search%");
            });
        }

        if ($request->has('station_id') && $request->station_id) {
            $query->where('station_id', $request->station_id);
        }

        $agents = $query->paginate(10);
        $stations = \App\Models\Station::all();

        return view('admin.agents.index', compact('agents', 'stations'));
    }


    public function show(User $agent)
{
    return view('admin.agents.show', compact('agent'));
}



    public function create()
    {
        $stations = \App\Models\Station::all();
        return view('admin.agents.create', compact('stations'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'phone' => 'required|unique:users,phone',
            'email' => 'nullable|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'station_id' => 'required|exists:stations,id',

        ]);

        User::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => 'agent',
            'station_id' => $request->station_id,
        ]);

        return redirect()->route('admin.agents.index')->with('success', 'Agent created successfully.');
    }

   public function edit(User $agent)
    {
        $stations = \App\Models\Station::all();
        return view('admin.agents.edit', compact('agent', 'stations'));
    }


   public function update(Request $request, User $agent)
    {
        $request->validate([
            'name' => 'required|string',
            'phone' => 'required|unique:users,phone,' . $agent->id,
            'email' => 'nullable|email|unique:users,email,' . $agent->id,
            'station_id' => 'required|exists:stations,id',
            'password' => 'nullable|min:6|confirmed',
        ]);

        $updateData = [
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'station_id' => $request->station_id,
        ];

        if ($request->filled('password')) {
            $updateData['password'] = bcrypt($request->password);
        }

        $agent->update($updateData);

        return redirect()->route('admin.agents.index')->with('success', 'Agent updated successfully.');
    }


    public function destroy(User $agent)
    {
        $agent->delete();
        return redirect()->route('admin.agents.index')->with('success', 'Agent deleted.');
    }
}
