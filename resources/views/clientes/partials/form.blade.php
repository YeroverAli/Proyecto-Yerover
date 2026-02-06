{{-- Nombre --}}
<div class="row mb-3">
    <label for="nombre" class="col-md-4 col-form-label text-md-end">Nombre</label>
    <div class="col-md-6">
        <input id="nombre" type="text"
            class="form-control @error('nombre') is-invalid @enderror"
            name="nombre" value="{{ old('nombre', $cliente->nombre ?? '') }}" required>
        @error('nombre')
            <span class="invalid-feedback">{{ $message }}</span>
        @enderror
    </div>
</div>

{{-- Apellido --}}
<div class="row mb-3">
    <label for="apellidos" class="col-md-4 col-form-label text-md-end">Apellidos</label>
    <div class="col-md-6">
        <input id="apellidos" type="text"
            class="form-control @error('apellidos') is-invalid @enderror"
            name="apellidos" value="{{ old('apellidos', $cliente->apellidos ?? '') }}" required>
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
                    {{ old('empresa_id', $cliente->empresa_id ?? '') == $empresa->id ? 'selected' : '' }}>
                    {{ $empresa->nombre }}
                </option>
            @endforeach
        </select>
        @error('empresa_id')
            <span class="invalid-feedback">{{ $message }}</span>
        @enderror
    </div>
</div>

{{-- DNI --}}
<div class="row mb-3">
    <label for="dni" class="col-md-4 col-form-label text-md-end">DNI</label>
    <div class="col-md-6">
        <input id="dni" type="text"
            class="form-control @error('dni') is-invalid @enderror"
            name="dni" value="{{ old('dni', $cliente->dni ?? '') }}">
        @error('dni')
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
            name="telefono" value="{{ old('telefono', $cliente->telefono ?? '') }}">
        @error('telefono')
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
            name="email" value="{{ old('email', $cliente->email ?? '') }}">
        @error('email')
            <span class="invalid-feedback">{{ $message }}</span>
        @enderror
    </div>
</div>

{{-- Domicilio --}}
<div class="row mb-3">
    <label for="domicilio" class="col-md-4 col-form-label text-md-end">Domicilio</label>
    <div class="col-md-6">
        <input id="domicilio" type="text"
            class="form-control @error('domicilio') is-invalid @enderror"
            name="domicilio" value="{{ old('domicilio', $cliente->domicilio ?? '') }}">
        @error('domicilio')
            <span class="invalid-feedback">{{ $message }}</span>
        @enderror
    </div>
</div>

{{-- Código Postal --}}
<div class="row mb-3">
    <label for="codigo_postal" class="col-md-4 col-form-label text-md-end">Código Postal</label>
    <div class="col-md-6">
        <input id="codigo_postal" type="text"
            class="form-control @error('codigo_postal') is-invalid @enderror"
            name="codigo_postal" value="{{ old('codigo_postal', $cliente->codigo_postal ?? '') }}">
        @error('codigo_postal')
            <span class="invalid-feedback">{{ $message }}</span>
        @enderror
    </div>
</div>
</div>
