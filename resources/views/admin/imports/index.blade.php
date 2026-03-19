<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin • Importações</title>
    <style>body { font-family: Arial, sans-serif; margin: 2rem; } table { width:100%; border-collapse: collapse; margin-top: 1rem; } th, td { border:1px solid #ddd; padding: .6rem; } th { background: #f5f5f5; text-align: left; } .success { background:#e8f7e8; padding: .75rem; border:1px solid #a8d5a8; }</style>
</head>
<body>
<h1>Importações por PDF</h1>
<form method="post" action="{{ route('logout') }}" style="margin-bottom: 1rem;">
    @csrf
    <button type="submit">Sair</button>
</form>
<p><a href="{{ route('admin.products.index') }}">Voltar para produtos</a></p>

@if(session('success'))
    <div class="success">{{ session('success') }}</div>
@endif

<form method="post" action="{{ route('admin.imports.store') }}" enctype="multipart/form-data">
    @csrf
    <label>Fornecedor
        <input type="text" name="supplier_name" value="{{ old('supplier_name') }}" required>
    </label>
    <label>PDF de catálogo
        <input type="file" name="catalog_pdf" accept="application/pdf" required>
    </label>
    <button type="submit">Enviar batch</button>
</form>

<table>
    <thead>
    <tr>
        <th>ID</th>
        <th>Fornecedor</th>
        <th>Status</th>
        <th>Itens</th>
        <th>Criado em</th>
        <th>Ação</th>
    </tr>
    </thead>
    <tbody>
    @forelse($batches as $batch)
        <tr>
            <td>{{ $batch->id }}</td>
            <td>{{ $batch->supplier_name }}</td>
            <td>{{ $batch->status }}</td>
            <td>{{ $batch->total_items }}</td>
            <td>{{ $batch->created_at }}</td>
            <td><a href="{{ route('admin.imports.show', $batch) }}">Revisar</a></td>
        </tr>
    @empty
        <tr><td colspan="6">Nenhum batch ainda.</td></tr>
    @endforelse
    </tbody>
</table>

{{ $batches->links() }}
</body>
</html>
