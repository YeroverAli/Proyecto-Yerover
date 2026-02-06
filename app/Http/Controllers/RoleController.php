<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Repositories\RoleRepositoryInterface;
use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use Illuminate\Support\Facades\Log;
use App\Models\User;

class RoleController extends Controller
{
    private RoleRepositoryInterface $roles;

    public function __construct(RoleRepositoryInterface $roles)
    {
        $this->roles = $roles;
    }

    public function index(Request $request)
    {

        $roles = $request->filled('search')
            ? $this->roles->search($request->search)
            : $this->roles->all();

        return view('roles.index', compact('roles'));
    }

    public function create()
    {
        $permissions = Permission::all();

        return view('roles.create', compact('permissions'));
    }

    public function store(StoreRoleRequest $request)
    {

        try {
            $this->roles->create($request->validated());

            Log::info('Rol creado correctamente', ['nombre' => $request->validated()['name']]); // ✅ Cambiado 'nombre' por 'name'

            return redirect()->route('roles.index')->with('success', 'Rol creado correctamente');
                
        } catch (\Illuminate\Database\QueryException $e) {
            Log::error('Error al crear Rol (BD)', ['error' => $e->getMessage(), 'data' => $request->validated()]);

            return redirect()->back()->withInput()->with('error', 'Error al crear el Rol. Por favor, inténtalo de nuevo.');
                
        } catch (\Exception $e) {
            Log::error('Error inesperado al crear Rol', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);

            return redirect()->back()->withInput()->with('error', 'Ha ocurrido un error inesperado. Por favor, contacta con el administrador.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role)
    {
        // Roles solo pueden ser vistos por admins (middleware is_admin ya aplicado)
        $role->load('permissions');

        return view('roles.show', compact('role'));
    }

    public function edit(Role $role)
    {
        $permissions = Permission::all();
        $role->load('permissions');
        
        return view('roles.edit', compact('role', 'permissions'));
    }

    public function update(UpdateRoleRequest $request, Role $role)
    {
        
        try {
            $validated = $request->validated();
            
            // Convertir permisos a array si viene null (cuando no se marca ningún checkbox)
            if (!isset($validated['permissions'])) {
                $validated['permissions'] = [];
            }
            
            $this->roles->update($role->id, $validated);
    
            Log::info('Rol actualizado correctamente', [
                'role_id' => $role->id,
                'nombre' => $validated['name'] ?? 'N/A',
                'permisos_count' => count($validated['permissions'] ?? [])
            ]);
    
            return redirect()->route('roles.index')->with('success', 'Rol actualizado correctamente');
        }
        
        catch (\Illuminate\Validation\ValidationException $e) {
            // Si hay errores de validación, redirigir con los errores
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        }
        
        catch (\Illuminate\Database\QueryException $e) {
            Log::error('Error al actualizar Rol (BD)', [
                'error' => $e->getMessage(), 
                'data' => $request->all(),
                'role_id' => $role->id
            ]);
            
            return redirect()->back()->withInput()->with('error', 'Error al actualizar Rol. Por favor, inténtalo de nuevo.');
        }
    
        catch (\Exception $e) {
            Log::error('Error inesperado al actualizar Rol', [
                'error' => $e->getMessage(), 
                'trace' => $e->getTraceAsString(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'role_id' => $role->id
            ]);
    
            return redirect()->back()->withInput()->with('error', 'Ha ocurrido un error inesperado. Por favor, contacta con el administrador.');
        }
    }

    public function destroy(Role $role)
    {

    try {
        // Guardar nombre antes de eliminar (para el log)
        $nombreRol = $role->name;
        $idRol = $role->id;

        $this->roles->delete($role->id);

        // Log de confirmación
        Log::info('Rol eliminado correctamente', [
            'id' => $idRol,
            'nombre' => $nombreRol,
        ]);

        return redirect()->route('roles.index')->with('success', 'Rol eliminado correctamente');
            
    } catch (\Illuminate\Database\QueryException $e) {
        Log::error('Error al eliminar Rol (BD)', [
            'error' => $e->getMessage(),
            'role_id' => $role->id,
            'nombre' => $role->name
        ]);

        return redirect()->back()->with('error', 'Error al eliminar el Rol. Por favor, inténtalo de nuevo.');
            
    } catch (\Exception $e) {
        Log::error('Error inesperado al eliminar Rol', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);

        return redirect()->back()->with('error', 'Ha ocurrido un error inesperado. Por favor, contacta con el administrador.');
    }
}
}