<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\UserRepositoryInterface;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use App\Models\Empresa;
use Illuminate\Support\Facades\Log;
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

        $query = $request->filled('search')
             ? $this->users->search($request->search)
             : $this->users->all();

            // Si search devuelve un paginator, usa with(), si no, carga las relaciones manualmente
             if(method_exists($query, 'with'))
             {
                $users = $query->with(['empresa', 'departamento', 'centro']);
             } else {
            // Si all() devuelve una collection, necesitarás modificar el repository
                $users = $query;
             }

        return view('users.index', compact('users'));
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $this->authorize('view', $user);

        // Cargar relaciones necesarias
        $user->load(['empresa', 'departamento', 'centro', 'roles']);

        return view('users.show', compact('user'));
    }
    
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', User::class);

        // Recopila todos los usuarios
        $empresas = Empresa::all();
        $departamentos = Departamento::all();
        $centros = Centro::all();
        $roles = Role::all();

        return view('users.create', compact('empresas', 'departamentos', 'centros', 'roles'));
    }

    /**
     * Valida los datos recibidos y crea y Almacena el usuario en la base de datos y redirige a la vista users.index
     */
    public function store(StoreUserRequest $request)
    {
        $this->authorize('create', User::class);

        try {

            $this->users->create($request->validated());

            Log::info('Usuario creado correctamente', ['nombre' => $request->validated()['nombre'], 'email' => $request->validated()['email']]);

            return redirect()->route('users.index')->with('success', 'Usuario creado correctamente');
        }

        catch(\Illuminate\Database\QueryException $e){

            Log::error('Error al crear usuario (DB)', ['error' => $e->getMessage(), 'data' => $request->validated()]);

            return redirect()->back()->withInput()->with('error', 'Error al crear el usuario. Por favor, inténtelo de nuevo');
        }

        catch(\Exception $e){

            Log::error('Error inesperado al crear usuario', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);

            return redirect()->back()->withInput()->with('error', 'Ha ocurrido un error inesperado. Por favor, contacta con un administrador.');
        }
    }

    /**
     * Recibe todos los datos de empresa, departamento, centro y rol para luego mostrar en la vista el nombre. Redirige a la vista edit con las variables de user, empresas...
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
     * Actualiza los datos en la Base de datos a la vez que los valida para confirmar que esté todo correcto y redirige a la vista index
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $this->authorize('update', $user);

        try{
        
            $this->users->update($user->id, $request->validated());

            Log::info('Usuario actualizado correctamente', ['user_id' => $user->id, 'nombre' => $request->validated()['nombre'], 'email' => $request->validated()['email']]);
            return redirect()->route('users.index')->with('success', 'Usuario actualizado correctamente');

        }
        
        catch(\Illuminate\Database\QueryException $e){
            
            Log::error('Error al actualizar usuario (DB)', ['error' => $e->getMessage(), 'data' => $request->validated()]);

            return redirect()->back()->withInput()->with('error', 'Error al actualizar usuario. Por favor, inténtelo de nuevo');
        }

        catch(\Exception $e) {

            Log::error('Error inesperado al actualizar usuario', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);

            return redirect()->back()->withInput()->with('error', 'Ha ocurrido un error inesperado. Por favor, contacta con un administrador');
        }



    }

    /**
     * Elimina el usuario correspondiend seleccionado y redirige a la vista index.
     */
    public function destroy(User $user)
    {
        $this->authorize('delete', $user);

        try{
        
            // Guardar datos antes de eliminar (para el log)
            $nombreUsuario = $user->nombre;
            $emailUsuario = $user->email;
            $idUsuario = $user->id;

            $this->users->delete($user->id);

            Log::info('Usuario eliminado correctamente', ['user_id' => $idUsuario, 'nombre' => $nombreUsuario, 'email' => $emailUsuario]);

            return redirect()->route('users.index')->with('success', 'Usuario eliminado correctamente');
        }

        catch(\Illuminate\Database\QueryException $e){
            Log::error('Error al eliminar usuario (DB)', ['error' => $e->getMessage(), 'user_id' => $user->id, 'nombre' => $user->nombre]);

            return redirect()->back()->with('error', 'Error al eliminar usuario. Por favor, inténtalo de nuevo');
        }

        catch(\Exception $e){

            Log::error('Error inesperado al eliminar usuario.', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);

            return redirect()->back()->with('error', 'Ha ocurrido un error inesperado. Por favor, contacta con un administrador.');
        }
    }
}
