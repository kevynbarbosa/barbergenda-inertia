<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Inertia\Response;

class UserController extends Controller
{
    public function index(): Response
    {
        $users = User::latest()->get();

        return Inertia::render('users/Index', [
            'users' => $users,
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('users/Create');
    }

    public function store(StoreUserRequest $request): RedirectResponse
    {
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('users.index')
            ->with('success', 'Usuário criado com sucesso.');
    }

    public function show(User $user): Response
    {
        return Inertia::render('users/Show', [
            'user' => $user,
        ]);
    }

    public function edit(User $user): Response
    {
        return Inertia::render('users/Edit', [
            'user' => $user,
        ]);
    }

    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        $data = [
            'name' => $request->name,
            'email' => $request->email,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('users.index')
            ->with('success', 'Usuário atualizado com sucesso.');
    }

    public function destroy(User $user): RedirectResponse
    {
        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'Usuário deletado com sucesso.');
    }
}
