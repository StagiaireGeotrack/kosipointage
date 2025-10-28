@if ($paginator->hasPages())
    <div class="pagination-simple">
        
        <!-- Navigation -->
        <nav aria-label="Pagination" class="mt-3">
            <ul class="pagination justify-content-center">
                {{-- Précédent --}}
                @if ($paginator->onFirstPage())
                    <li class="page-item disabled">
                        <span class="page-link">Précédent</span>
                    </li>
                @else
                    <li class="page-item">
                        <a class="page-link" href="{{ $paginator->previousPageUrl() }}">Précédent</a>
                    </li>
                @endif

                {{-- Numéros de pages --}}
                @foreach ($elements as $element)
                    {{-- "..." Séparateur --}}
                    @if (is_string($element))
                        <li class="page-item disabled">
                            <span class="page-link">{{ $element }}</span>
                        </li>
                    @endif

                    {{-- Liens de pages --}}
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <li class="page-item active">
                                    <span class="page-link">{{ $page }}</span>
                                </li>
                            @else
                                <li class="page-item">
                                    <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                                </li>
                            @endif
                        @endforeach
                    @endif
                @endforeach

                {{-- Suivant --}}
                @if ($paginator->hasMorePages())
                    <li class="page-item">
                        <a class="page-link" href="{{ $paginator->nextPageUrl() }}">Suivant</a>
                    </li>
                @else
                    <li class="page-item disabled">
                        <span class="page-link">Suivant</span>
                    </li>
                @endif
            </ul>
        </nav>

        <!-- Page actuelle (mobile) -->
        <div class="d-md-none text-center mt-2">
            <small class="text-muted">
                Page {{ $paginator->currentPage() }} sur {{ $paginator->lastPage() }}
            </small>
        </div>
    </div>

    <style>
        .pagination-simple {
            margin: 2rem 0;
        }

        .pagination-info {
            text-align: center;
            margin-bottom: 1rem;
        }

        .pagination .page-link {
            color: #007bff;
            background-color: #fff;
            border: 1px solid #dee2e6;
            padding: 0.5rem 0.75rem;
            margin: 0 2px;
            border-radius: 0.375rem;
            transition: all 0.2s ease;
        }

        .pagination .page-link:hover {
            color: #0056b3;
            background-color: #e9ecef;
            border-color: #dee2e6;
            text-decoration: none;
        }

        .pagination .page-item.active .page-link {
            color: #fff;
            background-color: #007bff;
            border-color: #007bff;
        }

        .pagination .page-item.disabled .page-link {
            color: #6c757d;
            background-color: #fff;
            border-color: #dee2e6;
            cursor: not-allowed;
        }

        @media (max-width: 576px) {
            .pagination .page-link {
                padding: 0.375rem 0.5rem;
                font-size: 0.875rem;
                margin: 0 1px;
            }
        }
    </style>
@endif