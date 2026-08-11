{{--
    Template de pagination personnalisé NDJINA.
    Utilise le style CSS .pagination du site (public/css/app.css).
    --}}
@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination" class="pagination-wrap">
        <p class="pagination-info">
            @lang('Affichage de')
            <strong>{{ $paginator->firstItem() }}</strong>
            @lang('à')
            <strong>{{ $paginator->lastItem() }}</strong>
            @lang('sur')
            <strong>{{ $paginator->total() }}</strong>
            @lang('résultat(s)')
        </p>

        <ul class="pagination">
            {{-- Bouton Précédent --}}
            @if ($paginator->onFirstPage())
                <li class="disabled" aria-disabled="true"><span>‹</span></li>
            @else
                <li><a href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="Précédent">‹</a></li>
            @endif

            {{-- Liens de pages --}}
            @foreach ($elements as $element)
                {{-- Séparateur --}}
                @if (is_string($element))
                    <li class="disabled" aria-disabled="true"><span>{{ $element }}</span></li>
                @endif

                {{-- Liens de pages --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="active" aria-current="page"><span>{{ $page }}</span></li>
                        @else
                            <li><a href="{{ $url }}" aria-label="Page {{ $page }}">{{ $page }}</a></li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Bouton Suivant --}}
            @if ($paginator->hasMorePages())
                <li><a href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="Suivant">›</a></li>
            @else
                <li class="disabled" aria-disabled="true"><span>›</span></li>
            @endif
        </ul>
    </nav>
@endif
