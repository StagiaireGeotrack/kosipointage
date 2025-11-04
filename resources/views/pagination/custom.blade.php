@if ($paginator->hasPages())
    <div class="pagination-simple">
        
        <!-- Navigation -->
        <nav aria-label="Pagination" class="mt-1">
            <ul class="pagination justify-content-center">
                {{-- Précédent --}}
                @if ($paginator->onFirstPage())
                    <li class="page-item disabled">
                        <span class="page-link">
                            <i class="bi bi-chevron-left"></i> Précédent
                        </span>
                    </li>
                @else
                    <li class="page-item">
                        <a class="page-link" href="{{ $paginator->previousPageUrl() }}">
                            <i class="bi bi-chevron-left"></i> Précédent
                        </a>
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
                        <a class="page-link" href="{{ $paginator->nextPageUrl() }}">
                            Suivant <i class="bi bi-chevron-right"></i>
                        </a>
                    </li>
                @else
                    <li class="page-item disabled">
                        <span class="page-link">
                            Suivant <i class="bi bi-chevron-right"></i>
                        </span>
                    </li>
                @endif
            </ul>
        </nav>

        <!-- Page actuelle (mobile) -->
        <div class="d-md-none text-center mt-1">
            <small class="pagination-mobile-info">
                <i class="bi bi-file-earmark-text"></i>
                Page {{ $paginator->currentPage() }} sur {{ $paginator->lastPage() }}
            </small>
        </div>
    </div>

    <style>
        .pagination-simple {
            margin: 2rem 0;
        }

        .pagination {
            gap: 0.5rem;
        }

        .pagination .page-link {
            color: #3F52A4;
            background-color: #fff;
            border: 2px solid #e2e8f0;
            padding: 0.6rem 1rem;
            border-radius: 8px;
            transition: all 0.3s ease;
            font-weight: 500;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        .pagination .page-link:hover {
            color: #fff;
            background: linear-gradient(135deg, #3F52A4 0%, #5a6fb8 100%);
            border-color: #3F52A4;
            text-decoration: none;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(63, 82, 164, 0.3);
        }

        .pagination .page-link i {
            font-size: 0.875rem;
        }

        .pagination .page-item.active .page-link {
            color: #fff;
            background: linear-gradient(135deg, #3F52A4 0%, #5a6fb8 100%);
            border-color: #3F52A4;
            box-shadow: 0 4px 8px rgba(63, 82, 164, 0.3);
            font-weight: 600;
        }

        .pagination .page-item.disabled .page-link {
            color: #a0aec0;
            background-color: #f7fafc;
            border-color: #e2e8f0;
            cursor: not-allowed;
            opacity: 0.6;
        }

        /* Info mobile */
        .pagination-mobile-info {
            display: inline-block;
            color: #4a5568;
            background-color: #f7fafc;
            padding: 0.5rem 1.25rem;
            border-radius: 20px;
            border: 2px solid #e2e8f0;
            font-weight: 500;
        }

        .pagination-mobile-info i {
            color: #3F52A4;
            margin-right: 0.25rem;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .pagination {
                gap: 0.25rem;
            }

            .pagination .page-link {
                padding: 0.5rem 0.75rem;
                font-size: 0.9rem;
            }
        }

        @media (max-width: 576px) {
            .pagination .page-link {
                padding: 0.4rem 0.6rem;
                font-size: 0.85rem;
            }

            /* Cacher les textes sur petit écran, garder les icônes */
            .pagination .page-link i {
                margin: 0;
            }
        }

        /* Animation au chargement */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .pagination-simple {
            animation: fadeInUp 0.5s ease-out;
        }
    </style>
@endif