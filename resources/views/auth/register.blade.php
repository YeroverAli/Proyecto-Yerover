@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Registro de usuario') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        {{-- Nombre --}}
                        <div class="row mb-3">
                            <label for="nombre" class="col-md-4 col-form-label text-md-end">Nombre</label>
                            <div class="col-md-6">
                                <input id="nombre" type="text"
                                    class="form-control @error('nombre') is-invalid @enderror"
                                    name="nombre" value="{{ old('nombre') }}" required>
                                @error('nombre')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        {{-- Apellidos --}}
                        <div class="row mb-3">
                            <label for="apellidos" class="col-md-4 col-form-label text-md-end">Apellidos</label>
                            <div class="col-md-6">
                                <input id="apellidos" type="text"
                                    class="form-control @error('apellidos') is-invalid @enderror"
                                    name="apellidos" value="{{ old('apellidos') }}" required>
                                @error('apellidos')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        {{-- Empresa --}}
                        <div class="row mb-3">
                            <label for="empresa_id" class="col-md-4 col-form-label text-md-end">Empresa</label>
                            <div class="col-md-6">
                                <select id="empresa_id"
                                    class="form-select @error('empresa_id') is-invalid @enderror"
                                    name="empresa_id" required>
                                    <option value="">Seleccione una empresa</option>
                                    @foreach ($empresas as $empresa)
                                        <option value="{{ $empresa->id }}"
                                            {{ old('empresa_id') == $empresa->id ? 'selected' : '' }}>
                                            {{ $empresa->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('empresa_id')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        {{-- Departamento --}}
                        <div class="row mb-3">
                            <label for="departamento_id" class="col-md-4 col-form-label text-md-end">Departamento</label>
                            <div class="col-md-6">
                                <select id="departamento_id"
                                    class="form-select @error('departamento_id') is-invalid @enderror"
                                    name="departamento_id" required>
                                    <option value="">Seleccione un departamento</option>
                                    @foreach ($departamentos as $departamento)
                                        <option value="{{ $departamento->id }}"
                                            {{ old('departamento_id') == $departamento->id ? 'selected' : '' }}>
                                            {{ $departamento->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('departamento_id')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        {{-- Centro --}}
                        <div class="row mb-3">
                            <label for="centro_id" class="col-md-4 col-form-label text-md-end">Centro</label>
                            <div class="col-md-6">
                                <select id="centro_id"
                                    class="form-select @error('centro_id') is-invalid @enderror"
                                    name="centro_id" required>
                                    <option value="">Seleccione un centro</option>
                                    @foreach ($centros as $centro)
                                        <option value="{{ $centro->id }}"
                                            {{ old('centro_id') == $centro->id ? 'selected' : '' }}>
                                            {{ $centro->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('centro_id')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        {{-- Rol --}}
                        <div class="row mb-3">
                            <label for="role" class="col-md-4 col-form-label text-md-end">Rol</label>
                            <div class="col-md-6">
                                <select id="role"
                                    class="form-select @error('role') is-invalid @enderror"
                                    name="role" required>
                                    <option value="">Seleccione un rol</option>
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->name }}"
                                            {{ old('role', 'user') == $role->name ? 'selected' : '' }}>
                                            {{ ucfirst($role->name) }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('role')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        {{-- Email --}}
                        <div class="row mb-3">
                            <label for="email" class="col-md-4 col-form-label text-md-end">Email</label>
                            <div class="col-md-6">
                                <input id="email" type="email"
                                    class="form-control @error('email') is-invalid @enderror"
                                    name="email" value="{{ old('email') }}" required>
                                @error('email')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        {{-- Teléfono --}}
                        <div class="row mb-3">
                            <label for="telefono" class="col-md-4 col-form-label text-md-end">Teléfono</label>
                            <div class="col-md-6">
                                <input id="telefono" type="text"
                                    class="form-control @error('telefono') is-invalid @enderror"
                                    name="telefono" value="{{ old('telefono') }}">
                                @error('telefono')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        {{-- Extensión --}}
                        <div class="row mb-3">
                            <label for="extension" class="col-md-4 col-form-label text-md-end">Extensión</label>
                            <div class="col-md-6">
                                <input id="extension" type="text"
                                    class="form-control @error('extension') is-invalid @enderror"
                                    name="extension" value="{{ old('extension') }}">
                                @error('extension')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        {{-- Password --}}
                        <div class="row mb-3">
                            <label for="password" class="col-md-4 col-form-label text-md-end">Contraseña</label>
                            <div class="col-md-6">
                                <input id="password" type="password"
                                    class="form-control @error('password') is-invalid @enderror"
                                    name="password" required>
                                @error('password')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        {{-- Confirmación --}}
                        <div class="row mb-3">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-end">Confirmar contraseña</label>
                            <div class="col-md-6">
                                <input id="password-confirm" type="password"
                                    class="form-control" name="password_confirmation" required>
                            </div>
                        </div>

                        {{-- Botón --}}
                        <div class="row mb-0">
                            <div class="col-12 text-center">
                                <button type="submit" class="btn btn-primary">
                                    Registrarse
                                </button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
