<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $users = User::orderBy('username')->get();

        return view('users.index', ['users' => $users]);
    }

    public function show(User $user)
    {
        return view('users.show', ['user' => $user]);
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', Rule::unique(User::class)->ignore($user->id)],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($user->id)],
            'role' => ['required', Rule::in(['user', 'admin'])],
        ]);

        // Zo blijft er altijd een administrator over
        if ($user->is($request->user()) && $validated['role'] !== 'admin') {
            return back()->withErrors(['role' => 'Je kunt je eigen rol niet aanpassen.']);
        }

        // role staat bewust niet in Fillable, daarom zetten we die apart
        $user->fill($validated);
        $user->role = $validated['role'];
        $user->save();

        return redirect()->route('users.show', $user)->with('status', 'De gegevens zijn opgeslagen.');
    }

    public function destroy(Request $request, User $user)
    {
        if ($user->is($request->user())) {
            return back()->withErrors(['delete' => 'Je kunt je eigen account niet verwijderen.']);
        }

        $user->delete();

        return redirect()->route('users.index')->with('status', 'De user is verwijderd.');
    }
}
