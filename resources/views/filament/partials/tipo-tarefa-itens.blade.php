<table class="w-full text-sm border border-gray-200 rounded-lg">
    <thead class="bg-gray-100">
        <tr>
            <th class="p-2 text-left">Descrição</th>
            <th class="p-2 text-left">Criado em</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($itens as $item)
            <tr class="border-t">
                <td class="p-2">{{ $item->descricao }}</td>
                <td class="p-2">{{ $item->created_at?->format('d/m/Y H:i') }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="2" class="p-2 text-center text-gray-500">
                    Nenhum item vinculado a este checklist.
                </td>
            </tr>
        @endforelse
    </tbody>
</table>
