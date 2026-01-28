{{-- Nombre --}}
<div class="row mb-3">
    <label for="nombre" class="col-md-4 col-form-label text-md-end">Nombre</label>
    <div class="col-md-6">
        <input id="nombre" type="text"
            class="form-control @error('nombre') is-invalid @enderror"
            name="nombre" value="{{ old('nombre', $departamento->nombre ?? '') }}" required>
        @error('nombre')
            <span class="invalid-feedback">{{ $message }}</span>
        @enderror
    </div>
</div>

{{-- Abreviatura --}}
<div class="row mb-3">
    <label for="abreviatura" class="col-md-4 col-form-label text-md-end">Abreviatura</label>
    <div class="col-md-6">
        <input id="abreviatura" type="text"
            class="form-control @error('abreviatura') is-invalid @enderror"
            name="abreviatura" value="{{ old('abreviatura', $departamento->abreviatura ?? '') }}" required>
        @error('abreviatura')
            <span class="invalid-feedback">{{ $message }}</span>
        @enderror
    </div>
</div>

{{-- CIF --}}
<div class="row mb-3">
    <label for="cif" class="col-md-4 col-form-label text-md-end">CIF</label>
    <div class="col-md-6">
        <input id="cif" type="text"
            class="form-control @error('cif') is-invalid @enderror"
            name="cif" value="{{ old('cif', $departamento->cif ?? '') }}" required>
        @error('cif')
            <span class="invalid-feedback">{{ $message }}</span>
        @enderror
    </div>
</div>

