<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin • Revisão de batch</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 2rem; }
        table { width:100%; border-collapse: collapse; margin-top: 1rem; }
        th, td { border:1px solid #ddd; padding: .6rem; }
        th { background: #f5f5f5; text-align: left; }
        .success { background:#e8f7e8; padding: .75rem; border:1px solid #a8d5a8; }
        .actions { display: flex; gap: .5rem; }
    </style>
</head>
<body>
<h1>Batch #{{ $batch->id }} — {{ $batch->supplier_name }}</h1>
<form method="post" action="{{ route('logout') }}" style="margin-bottom: 1rem;">
    @csrf
    <button type="submit">Sair</button>
</form>
<p>
    Status: <strong>{{ $batch->status }}</strong>
    | Total: {{ $batch->total_items }}
    | Aprovados: {{ $batch->approved_items }}
    | Rejeitados: {{ $batch->rejected_items }}
</p>
<p><a href="{{ route('admin.imports.index') }}">Voltar</a></p>

@if(session('success'))
    <div class="success">{{ session('success') }}</div>
@endif

@if($errors->any())
    <div class="success" style="background:#ffe8e8;border-color:#f0b5b5;">{{ $errors->first() }}</div>
@endif

@if($batch->status !== 'published')
<form method="post" action="{{ route('admin.imports.publish', $batch) }}">
    @csrf
    <button type="submit">Publicar itens aprovados</button>
</form>
@endif

<table>
    <thead>
    <tr>
        <th>Linha</th>
        <th>SKU</th>
        <th>Nome</th>
        <th>Preço</th>
        <th>Status</th>
        <th>Erros</th>
        <th>Ações</th>
    </tr>
    </thead>
    <tbody>
    @foreach($batch->items as $item)
        <tr>
            <td>{{ $item->row_number }}</td>
            <td>{{ $item->sku ?? '-' }}</td>
            <td>{{ data_get($item->normalized_data, 'name', '-') }}</td>
            <td>{{ data_get($item->normalized_data, 'price', '-') }}</td>
            <td>{{ $item->status }}</td>
            <td>{{ implode('; ', $item->errors ?? []) ?: '-' }}</td>
            <td>
                @if($item->status !== 'published')
                    <div class="actions">
                        <form method="post" action="{{ route('admin.imports.items.approve', [$batch, $item]) }}">
                            @csrf
                            <button type="submit">Aprovar</button>
                        </form>

                        <form method="post" action="{{ route('admin.imports.items.reject', [$batch, $item]) }}">
                            @csrf
                            <button type="submit">Rejeitar</button>
                        </form>
                    </div>
                @else
                    -
                @endif
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
</body>
</html>
