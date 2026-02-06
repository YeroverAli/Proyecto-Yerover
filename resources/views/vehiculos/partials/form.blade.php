{{-- Chasis --}}
<div class="row mb-3">
    <label for="chasis" class="col-md-4 col-form-label text-md-end">Chasis</label>
    <div class="col-md-6">
        <input id="chasis" type="text"
            class="form-control @error('chasis') is-invalid @enderror"
            name="chasis" value="{{ old('chasis', $vehiculo->chasis ?? '') }}" required>
        @error('chasis')
            <span class="invalid-feedback">{{ $message }}</span>
        @enderror
    </div>
</div>

{{-- Modelo --}}
<div class="row mb-3">
    <label for="modelo" class="col-md-4 col-form-label text-md-end">Modelo</label>
    <div class="col-md-6">
        <input id="modelo" type="text"
            class="form-control @error('modelo') is-invalid @enderror"
            name="modelo" value="{{ old('modelo', $vehiculo->modelo ?? '') }}" required>
        @error('modelo')
            <span class="invalid-feedback">{{ $message }}</span>
        @enderror
    </div>
</div>

{{-- Versión --}}
<div class="row mb-3">
    <label for="version" class="col-md-4 col-form-label text-md-end">Versión</label>
    <div class="col-md-6">
        <input id="version" type="text"
            class="form-control @error('version') is-invalid @enderror"
            name="version" value="{{ old('version', $vehiculo->version ?? '') }}" required>
        @error('version')
            <span class="invalid-feedback">{{ $message }}</span>
        @enderror
    </div>
</div>

{{-- Bastidor --}}
<div class="row mb-3">
    <label for="bastidor" class="col-md-4 col-form-label text-md-end">Bastidor</label>
    <div class="col-md-6">
        <input id="bastidor" type="text"
            class="form-control @error('bastidor') is-invalid @enderror"
            name="bastidor" value="{{ old('bastidor', $vehiculo->bastidor ?? '') }}">
        @error('bastidor')
            <span class="invalid-feedback">{{ $message }}</span>
        @enderror
    </div>
</div>

{{-- Referencia --}}
<div class="row mb-3">
    <label for="referencia" class="col-md-4 col-form-label text-md-end">Referencia</label>
    <div class="col-md-6">
        <input id="referencia" type="text"
            class="form-control @error('referencia') is-invalid @enderror"
            name="referencia" value="{{ old('referencia', $vehiculo->referencia ?? '') }}">
        @error('referencia')
            <span class="invalid-feedback">{{ $message }}</span>
        @enderror
    </div>
</div>

{{-- Color Externo --}}
<div class="row mb-3">
    <label for="color_externo" class="col-md-4 col-form-label text-md-end">Color Externo</label>
    <div class="col-md-6">
        <input id="color_externo" type="text"
            class="form-control @error('color_externo') is-invalid @enderror"
            name="color_externo" value="{{ old('color_externo', $vehiculo->color_externo ?? '') }}">
        @error('color_externo')
            <span class="invalid-feedback">{{ $message }}</span>
        @enderror
    </div>
</div>

{{-- Color Interno --}}
<div class="row mb-3">
    <label for="color_interno" class="col-md-4 col-form-label text-md-end">Color Interno</label>
    <div class="col-md-6">
        <input id="color_interno" type="text"
            class="form-control @error('color_interno') is-invalid @enderror"
            name="color_interno" value="{{ old('color_interno', $vehiculo->color_interno ?? '') }}">
        @error('color_interno')
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
                    {{ old('empresa_id', $vehiculo->empresa_id ?? '') == $empresa->id ? 'selected' : '' }}>
                    {{ $empresa->nombre }}
                </option>
            @endforeach
        </select>
        @error('empresa_id')
            <span class="invalid-feedback">{{ $message }}</span>
        @enderror
    </div>
</div>
</div>
