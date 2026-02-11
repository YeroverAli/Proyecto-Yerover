{{-- Nombre --}}
<div class="row mb-3">
    <label for="name" class="col-md-4 col-form-label text-md-end">Nombre</label>
    <div class="col-md-6">
        <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name"
            value="{{ old('name', $role->name ?? '') }}" required>
        @error('name')
            <span class="invalid-feedback">{{ $message }}</span>
        @enderror
    </div>
</div>

{{-- Guard --}}
<div class="row mb-3">
    <label for="guard_name" class="col-md-4 col-form-label text-md-end">Guard</label>
    <div class="col-md-6">
        <input id="guard_name" type="text" class="form-control @error('guard_name') is-invalid @enderror"
            name="guard_name" value="{{ old('guard_name', $role->guard_name ?? 'web') }}" required>
        @error('guard_name')
            <span class="invalid-feedback">{{ $message }}</span>
        @enderror
    </div>
</div>

{{-- Permisos --}}
<div class="row mb-3">
    <label class="col-md-4 col-form-label text-md-end">Permisos</label>
    <div class="col-md-6">
        @forelse($permissions as $permission)
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="permissions[]" value="{{ $permission->id }}"
                    id="permission_{{ $permission->id }}" {{ (isset($role) && $role->permissions->contains($permission->id)) || in_array($permission->id, old('permissions', [])) ? 'checked' : '' }}>
                <label class="form-check-label" for="permission_{{ $permission->id }}">
                    {{ $permission->name }}
                </label>
            </div>
        @empty
            <p class="text-muted">No hay permisos disponibles</p>
        @endforelse
        @error('permissions')
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>
</div>