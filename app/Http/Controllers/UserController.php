<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\UserRepositoryInterface;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use App\Models\Empresa;
use Spatie\Permission\Models\Role;
use App\Models\Departamento;
use App\Models\Centro;

class UserController extends Controller
{
    private UserRepositoryInterface $users;

    public function __construct(UserRepositoryInterface $users)
    {
        $this->users = $users;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', User::class);

        $users = $request->filled('search')
             ? $this->users->search($request->search)
             : $this->users->all();
        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', User::class);

        $empresas = Empresa::all();
        $departamentos = Departamento::all();
        $centros = Centro::all();
        $roles = Role::all();

        return view('users.create', compact('empresas', 'departamentos', 'centros', 'roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        $this->authorize('create', User::class);

        $this->users->create($request->validated());

        return redirect()->route('users.index')
            ->with('success', 'Usuario creado correctamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $this->authorize('update', $user);

        $empresas = Empresa::all();
        $departamentos = Departamento::all();
        $centros = Centro::all();
        $roles = Role::all();

        return view('users.edit', compact('user', 'empresas', 'departamentos', 'centros', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {

        $this->users->update($user->id, $request->validated());

        return redirect()->route('users.index')
            ->with('success', 'Usuario actualizado correctamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $this->authorize('delete', $user);

        $this->users->delete($user->id);

        return redirect()->route('users.index');
    }
}
