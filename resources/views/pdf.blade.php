<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Subir PDF</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">
                            <i class="bi bi-file-earmark-pdf"></i> Subir Archivo PDF
                        </h4>
                    </div>
                    <div class="card-body">
                        @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                        @endif

                        @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                        @endif
                        @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                aria-label="Cerrar"></button>
                        </div>
                        @endif


                        <form action="{{ route('pdf.upload') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="mb-3">
                                <label for="pdf_file" class="form-label">
                                    <strong>Seleccionar archivo PDF</strong>
                                </label>
                                <input type="file" class="form-control @error('pdf_file') is-invalid @enderror"
                                    id="pdf_file" name="pdf_file" accept=".pdf,application/pdf" required>
                                <div class="form-text">
                                    Solo se permiten archivos PDF. Tamaño máximo: 10MB
                                </div>
                                @error('pdf_file')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="nombre" class="form-label">
                                    <strong>Nombre del documento (opcional)</strong>
                                </label>
                                <input type="text" class="form-control @error('nombre') is-invalid @enderror"
                                    id="nombre" name="nombre" placeholder="Ej: Factura_2024_001"
                                    value="{{ old('nombre') }}">
                                @error('nombre')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="modelo_pdf" class="form-label">
                                    <strong>Modelo de PDF</strong>
                                </label>
                                <select name="modelo_pdf" id="modelo_pdf" class="form-select" required>
                                    <option value="">Selecciona el modelo</option>
                                    <option value="nsmit">NSMIT</option>
                                    <option value="subida_dacia">Subida Ratios (DACIA)</option>
                                    <option value="subida_renault">Subida Ratios (RENAULT)</option>
                                </select>
                            </div>

                            <div class="d-flex justify-content-between align-items-center">
                                <a href="{{ route('welcome') }}" class="btn btn-secondary">
                                    <i class="bi bi-arrow-left"></i> Volver
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-upload"></i> Subir PDF
                                </button>
                            </div>
                        </form>

                        @if(session('text'))
                        <div class="mt-5">
                            <h4>Contenido del PDF:</h4>
                            <pre class="border p-4 bg-light"
                                style="max-height: 500px; overflow-y: auto;">{{ session('text') }}</pre>
                            @if(session('text'))
                            <div class="mt-4">
                                <form method="POST" action="{{ route('pdf.procesar') }}" class="mt-3">
                                    @csrf
                                    <input type="hidden" name="text" value="{{ session('text') }}">
                                    <input type="hidden" name="modelo_pdf"
                                        value="{{ session('modelo_pdf', old('modelo_pdf', 'nsmit', 'subida_dacia', 'subida_renault')) }}">

                                    <button type="submit" class="btn btn-success">
                                        Procesar PDF
                                    </button>
                                </form>
                            </div>
                            @endif

                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>