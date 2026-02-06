{{-- Nombre --}}
<div class="row mb-3">
    <label for="nombre" class="col-md-4 col-form-label text-md-end">Nombre</label>
    <div class="col-md-6">
        <input id="nombre" type="text"
            class="form-control @error('nombre') is-invalid @enderror"
            name="nombre" value="{{ old('nombre', $centro->nombre ?? '') }}" required>
        @error('nombre')
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
                    {{ old('empresa_id', $centro->empresa_id ?? '') == $empresa->id ? 'selected' : '' }}>
                    {{ $empresa->nombre }}
                </option>
            @endforeach
        </select>
        @error('empresa_id')
            <span class="invalid-feedback">{{ $message }}</span>
        @enderror
    </div>
</div>

{{-- Dirección --}}
<div class="row mb-3">
    <label for="direccion" class="col-md-4 col-form-label text-md-end">Dirección</label>
    <div class="col-md-6">
        <input id="direccion" type="text"
            class="form-control @error('direccion') is-invalid @enderror"
            name="direccion" value="{{ old('direccion', $centro->direccion ?? '') }}" required>
        @error('direccion')
            <span class="invalid-feedback">{{ $message }}</span>
        @enderror
    </div>
</div>

{{-- Provincia --}}
<div class="row mb-3">
    <label for="provincia" class="col-md-4 col-form-label text-md-end">Provincia</label>
    <div class="col-md-6">
        <input id="provincia" type="text"
            class="form-control @error('provincia') is-invalid @enderror"
            name="provincia" value="{{ old('provincia', $centro->provincia ?? '') }}" required>
        @error('provincia')
            <span class="invalid-feedback">{{ $message }}</span>
        @enderror
    </div>
</div>

{{-- Municipio --}}
<div class="row mb-3">
    <label for="municipio" class="col-md-4 col-form-label text-md-end">Municipio</label>
    <div class="col-md-6">
        <input id="municipio" type="text"
            class="form-control @error('municipio') is-invalid @enderror"
            name="municipio" value="{{ old('municipio', $centro->municipio ?? '') }}" required>
        @error('municipio')
            <span class="invalid-feedback">{{ $message }}</span>
        @enderror
    </div>
</div>