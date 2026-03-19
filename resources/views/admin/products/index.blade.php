<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin • Produtos</title>
    <style>body { font-family: Arial, sans-serif; margin: 2rem; } table { width:100%; border-collapse: collapse; } th, td { border:1px solid #ddd; padding: .6rem; } th { background: #f5f5f5; text-align: left; }</style>
</head>
<body>
<h1>Produtos</h1>
<form method="post" action="{{ route('logout') }}" style="margin-bottom: 1rem;">
    @csrf
    <button type="submit">Sair</button>
</form>
<p><a href="{{ route('admin.imports.index') }}">Ir para importações PDF</a></p>
<table>
    <thead>
    <tr>
        <th>ID</th>
        <th>SKU</th>
        <th>Nome</th>
        <th>Preço</th>
        <th>Estoque</th>
        <th>Status</th>
        <th>Origem</th>
    </tr>
    </thead>
    <tbody>
    @forelse($products as $product)
        <tr>
            <td>{{ $product->id }}</td>
            <td>{{ $product->sku }}</td>
            <td>{{ $product->name }}</td>
            <td>R$ {{ number_format((float) $product->price, 2, ',', '.') }}</td>
            <td>{{ $product->stock }}</td>
            <td>{{ $product->status }}</td>
            <td>{{ $product->source }}</td>
        </tr>
    @empty
        <tr><td colspan="7">Nenhum produto cadastrado.</td></tr>
    @endforelse
    </tbody>
</table>

{{ $products->links() }}
</body>
</html>
