@extends('layouts.app')

@section('title', 'Portofolio Project - My Work')

@section('styles')
<style>
    /* Modal Styling */
    .modal-backdrop {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(9, 13, 22, 0.7);
        backdrop-filter: blur(8px);
        z-index: 1100;
        display: none;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    .modal-backdrop.show {
        display: flex;
        opacity: 1;
    }
    .modal-card {
        background-color: var(--bg-secondary);
        border: 1px solid var(--border-color);
        border-radius: var(--border-radius-lg);
        width: 90%;
        max-width: 700px;
        max-height: 90vh;
        overflow-y: auto;
        box-shadow: var(--card-shadow);
        transform: translateY(20px);
        transition: transform 0.3s ease;
    }
    .modal-backdrop.show .modal-card {
        transform: translateY(0);
    }
    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 24px;
        border-bottom: 1px solid var(--border-color);
    }
    .modal-body {
        padding: 24px;
        display: flex;
        flex-direction: column;
        gap: 20px;
    }
    .modal-close {
        background: none;
        border: none;
        font-size: 1.5rem;
        color: var(--text-secondary);
        cursor: pointer;
        transition: var(--transition);
    }
    .modal-close:hover {
        color: var(--accent);
    }
</style>
@endsection

@section('content')
<section class="section" style="padding-top: 120px;">
    <div class="container">
        <div style="text-align: center; margin-bottom: 40px;">
            <h5 class="hero-subtitle text-gradient">Portofolio</h5>
            <h2>Daftar Project Unggulan</h2>
        </div>

        <!-- Search Form & Category Filters -->
        <form action="{{ route('portfolio') }}" method="GET" style="margin-bottom: 50px;">
            <div class="portfolio-search">
                <input type="text" name="search" class="search-input" placeholder="Cari project..." value="{{ request('search') }}">
                <button type="submit" class="search-icon" style="background: none; border: none; cursor: pointer;">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>
            </div>

            <div class="portfolio-filters">
                <a href="{{ route('portfolio', ['search' => request('search')]) }}" class="filter-btn {{ !request('category') ? 'active' : '' }}">Semua</a>
                @foreach($categories as $cat)
                    <a href="{{ route('portfolio', ['category' => $cat->slug, 'search' => request('search')]) }}" class="filter-btn {{ request('category') == $cat->slug ? 'active' : '' }}">{{ $cat->name }}</a>
                @endforeach
            </div>
        </form>

        <!-- Portfolios Grid -->
        @if($portfolios->count() > 0)
            <div class="portfolio-grid">
                @foreach($portfolios as $portfolio)
                    <div class="portfolio-card">
                        <div class="portfolio-thumbnail">
                            @if($portfolio->thumbnail)
                                <img src="{{ asset('storage/' . $portfolio->thumbnail) }}" alt="{{ $portfolio->title }}">
                            @else
                                <img src="https://images.unsplash.com/photo-1531403009284-440f080d1e12?auto=format&fit=crop&q=80&w=400&h=250" alt="Thumbnail Default">
                            @endif
                            <span class="portfolio-category-badge">{{ $portfolio->category->name }}</span>
                        </div>
                        <div class="portfolio-info">
                            <h4 class="portfolio-card-title">{{ $portfolio->title }}</h4>
                            <p class="portfolio-card-desc">{{ Str::limit($portfolio->description, 120) }}</p>
                            <div class="portfolio-tech">
                                @if(is_array($portfolio->technology_used))
                                    @foreach($portfolio->technology_used as $tech)
                                        <span class="tech-badge">{{ $tech }}</span>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                        <div class="portfolio-links" style="justify-content: space-between; align-items: center;">
                            <div style="display: flex; gap: 16px;">
                                @if($portfolio->demo_link)
                                    <a href="{{ $portfolio->demo_link }}" target="_blank" class="portfolio-link"><i class="fa-solid fa-up-right-from-square"></i> Demo</a>
                                @endif
                                @if($portfolio->github_link)
                                    <a href="{{ $portfolio->github_link }}" target="_blank" class="portfolio-link"><i class="fa-brands fa-github"></i> GitHub</a>
                                @endif
                            </div>
                            <button class="text-gradient btn-detail" data-slug="{{ $portfolio->slug }}" style="font-weight: 700; font-size: 0.85rem; background: none; border: none; cursor: pointer;">Detail <i class="fa-solid fa-arrow-right"></i></button>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination Links -->
            <div style="margin-top: 50px; display: flex; justify-content: center;">
                {{ $portfolios->appends(request()->query())->links() }}
            </div>
        @else
            <div style="text-align: center; padding: 60px 0; color: var(--text-secondary);">
                <i class="fa-solid fa-folder-open" style="font-size: 3rem; margin-bottom: 16px; color: var(--border-color);"></i>
                <p>Tidak ada project yang ditemukan.</p>
            </div>
        @endif
    </div>
</section>

<!-- Detail Project Modal -->
<div class="modal-backdrop" id="projectModal">
    <div class="modal-card">
        <div class="modal-header">
            <h3 id="modalTitle">Project Title</h3>
            <button class="modal-close" id="modalClose"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <div class="modal-body">
            <div id="modalImageContainer" style="width: 100%; height: 300px; border-radius: var(--border-radius-md); overflow: hidden; background-color: var(--bg-tertiary);">
                <img id="modalImage" src="" alt="Thumbnail" style="width: 100%; height: 100%; object-fit: cover;">
            </div>
            
            <div style="display: flex; flex-wrap: wrap; justify-content: space-between; font-size: 0.9rem; color: var(--text-secondary); border-bottom: 1px solid var(--border-color); padding-bottom: 16px; gap: 12px;">
                <div><strong>Kategori:</strong> <span id="modalCategory">-</span></div>
                <div><strong>Tanggal:</strong> <span id="modalDate">-</span></div>
                <div><strong>Status:</strong> <span id="modalStatus" style="text-transform: capitalize;">-</span></div>
            </div>

            <div>
                <h4 style="margin-bottom: 8px;">Deskripsi Project</h4>
                <p id="modalDescription" style="color: var(--text-secondary); font-size: 0.95rem; line-height: 1.6; white-space: pre-wrap;"></p>
            </div>

            <div>
                <h4 style="margin-bottom: 8px;">Teknologi Yang Digunakan</h4>
                <div class="portfolio-tech" id="modalTech"></div>
            </div>

            <div style="display: flex; gap: 16px; border-top: 1px solid var(--border-color); padding-top: 24px; margin-top: 10px;">
                <a id="modalDemoLink" href="#" target="_blank" class="btn btn-primary" style="display: none;"><i class="fa-solid fa-up-right-from-square"></i> Live Demo</a>
                <a id="modalGithubLink" href="#" target="_blank" class="btn btn-secondary" style="display: none;"><i class="fa-brands fa-github"></i> Repository GitHub</a>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const modal = document.getElementById('projectModal');
    const modalClose = document.getElementById('modalClose');

    // Open Modal
    document.querySelectorAll('.btn-detail').forEach(button => {
        button.addEventListener('click', () => {
            const slug = button.getAttribute('data-slug');
            
            // Show loader if needed, or fetch directly
            fetch(`/portfolio/${slug}`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('modalTitle').innerText = data.title;
                    
                    // Thumbnail
                    const img = document.getElementById('modalImage');
                    if (data.thumbnail) {
                        img.src = '/storage/' + data.thumbnail;
                        document.getElementById('modalImageContainer').style.display = 'block';
                    } else {
                        img.src = 'https://images.unsplash.com/photo-1531403009284-440f080d1e12?auto=format&fit=crop&q=80&w=600&h=300';
                        document.getElementById('modalImageContainer').style.display = 'block';
                    }

                    // Metadata
                    document.getElementById('modalCategory').innerText = data.category.name;
                    
                    if (data.start_date) {
                        const dateObj = new Date(data.start_date);
                        document.getElementById('modalDate').innerText = dateObj.toLocaleDateString('id-ID', { year: 'numeric', month: 'long', day: 'numeric' });
                    } else {
                        document.getElementById('modalDate').innerText = '-';
                    }
                    
                    document.getElementById('modalStatus').innerText = data.status === 'completed' ? 'Selesai' : 'Sedang Dikerjakan';
                    document.getElementById('modalDescription').innerText = data.description;

                    // Tech badges
                    const techContainer = document.getElementById('modalTech');
                    techContainer.innerHTML = '';
                    if (data.technology_used) {
                        data.technology_used.forEach(tech => {
                            const badge = document.createElement('span');
                            badge.className = 'tech-badge';
                            badge.innerText = tech;
                            techContainer.appendChild(badge);
                        });
                    }

                    // Links
                    const demoLink = document.getElementById('modalDemoLink');
                    if (data.demo_link) {
                        demoLink.href = data.demo_link;
                        demoLink.style.display = 'inline-flex';
                    } else {
                        demoLink.style.display = 'none';
                    }

                    const githubLink = document.getElementById('modalGithubLink');
                    if (data.github_link) {
                        githubLink.href = data.github_link;
                        githubLink.style.display = 'inline-flex';
                    } else {
                        githubLink.style.display = 'none';
                    }

                    // Show modal
                    modal.classList.add('show');
                })
                .catch(err => {
                    console.error('Error fetching project details:', err);
                });
        });
    });

    // Close Modal
    modalClose.addEventListener('click', () => {
        modal.classList.remove('show');
    });

    modal.addEventListener('click', (e) => {
        if (e.target === modal) {
            modal.classList.remove('show');
        }
    });
</script>
@endsection
