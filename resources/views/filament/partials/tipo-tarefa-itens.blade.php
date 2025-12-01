<ul class="space-y-2">
    @forelse ($itens as $item)
        <li class="flex items-start gap-2">
            <span class="text-blue-500 mt-1">•</span>
            <span class="text-gray-800">{{ $item->descricao }}</span>
        </li>
    @empty
        <li class="text-center text-gray-500">Nenhum item vinculado.</li>
    @endforelse
</ul>
