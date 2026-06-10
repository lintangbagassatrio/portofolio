@extends('layouts.admin')

@section('title', 'Kelola Skill')

@section('content')
<div class="admin-title-row">
    <div>
        <h1 style="font-size: 2rem;">Kelola Keahlian (Skills)</h1>
        <p style="color: var(--text-secondary); font-size: 0.9rem;">Daftar, tambah, edit, atau hapus kompetensi teknis dan soft skills Anda.</p>
    </div>
</div>

<div class="admin-form-split" style="grid-template-columns: 0.8fr 1.2fr;">
    <!-- Add/Edit Form Panel -->
    <div class="admin-card" style="align-self: start; position: sticky; top: 100px;">
        <h3 id="formTitle"><i class="fa-solid fa-square-plus text-gradient"></i> Tambah Skill</h3>
        <p id="formDesc" style="font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 20px;">Masukkan detail kemampuan baru Anda di bawah ini.</p>
        
        <form id="skillForm" action="{{ route('admin.skills.store') }}" method="POST">
            @csrf
            <input type="hidden" name="_method" id="formMethod" value="POST">
            
            <div class="form-group">
                <label for="name">Nama Skill *</label>
                <input type="text" name="name" id="name" class="form-control" placeholder="Contoh: Laravel, Problem Solving" required>
            </div>
            
            <div class="form-group" style="margin-top: 16px;">
                <label for="category">Kategori Skill *</label>
                <select name="category" id="category" class="form-control" required>
                    <option value="technical">Keahlian Teknis (Technical Skill)</option>
                    <option value="soft">Keahlian Interpersonal (Soft Skill)</option>
                </select>
            </div>
            
            <div class="form-group" style="margin-top: 16px;">
                <label for="level">Persentase Tingkat Kemampuan (1-100)% *</label>
                <div style="display: flex; align-items: center; gap: 12px;">
                    <input type="range" id="levelRange" min="1" max="100" class="form-control" style="padding: 0; height: auto; accent-color: var(--accent);" value="80">
                    <input type="number" name="level" id="level" class="form-control" style="width: 80px;" min="1" max="100" value="80" required>
                </div>
            </div>
            
            <div style="display: flex; gap: 10px; margin-top: 24px;">
                <button type="submit" id="submitBtn" class="btn btn-primary" style="flex-grow: 1;"><i class="fa-solid fa-floppy-disk"></i> Simpan Skill</button>
                <button type="button" id="cancelBtn" class="btn btn-secondary" style="display: none;">Batal</button>
            </div>
        </form>
    </div>

    <!-- Skills Table List -->
    <div class="admin-card">
        <h3 style="margin-bottom: 20px;"><i class="fa-solid fa-list text-gradient"></i> Daftar Kemampuan</h3>
        
        <div class="admin-table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Nama Skill</th>
                        <th>Kategori</th>
                        <th style="width: 150px;">Kemampuan (%)</th>
                        <th style="width: 120px; text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($skills as $skill)
                        <tr data-id="{{ $skill->id }}" data-name="{{ $skill->name }}" data-category="{{ $skill->category }}" data-level="{{ $skill->level }}">
                            <td><strong>{{ $skill->name }}</strong></td>
                            <td>
                                @if($skill->category == 'technical')
                                    <span class="badge badge-info">Technical</span>
                                @else
                                    <span class="badge badge-success">Soft Skill</span>
                                @endif
                            </td>
                            <td>
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    <span style="font-weight: 600; font-size: 0.85rem; width: 35px;">{{ $skill->level }}%</span>
                                    <div class="skill-progress-bg" style="flex-grow: 1; height: 6px; margin-bottom: 0;">
                                        <div class="skill-progress-bar" style="width: {{ $skill->level }}%;"></div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div style="display: flex; gap: 8px; justify-content: center;">
                                    <button class="btn btn-secondary edit-skill-btn" style="padding: 6px 10px; font-size: 0.75rem;" title="Edit"><i class="fa-solid fa-pen"></i></button>
                                    
                                    <form action="{{ route('admin.skills.destroy', $skill->id) }}" method="POST" onsubmit="return confirm('Hapus skill {{ $skill->name }}?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-secondary" style="padding: 6px 10px; font-size: 0.75rem; color: #ef4444;" title="Hapus"><i class="fa-solid fa-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="text-align: center; color: var(--text-secondary); padding: 32px 0;">Belum ada data keahlian.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const skillForm = document.getElementById('skillForm');
        const formMethod = document.getElementById('formMethod');
        const formTitle = document.getElementById('formTitle');
        const formDesc = document.getElementById('formDesc');
        const nameInput = document.getElementById('name');
        const categoryInput = document.getElementById('category');
        const levelInput = document.getElementById('level');
        const levelRange = document.getElementById('levelRange');
        const submitBtn = document.getElementById('submitBtn');
        const cancelBtn = document.getElementById('cancelBtn');
        
        // Sync Range and Number Inputs
        levelRange.addEventListener('input', function() {
            levelInput.value = this.value;
        });
        levelInput.addEventListener('input', function() {
            if (this.value > 100) this.value = 100;
            if (this.value < 1) this.value = 1;
            levelRange.value = this.value;
        });

        // Edit Skill handler
        document.querySelectorAll('.edit-skill-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const tr = this.closest('tr');
                const id = tr.getAttribute('data-id');
                const name = tr.getAttribute('data-name');
                const category = tr.getAttribute('data-category');
                const level = tr.getAttribute('data-level');
                
                // Switch form to Edit Mode
                formTitle.innerHTML = '<i class="fa-solid fa-pen-to-square text-gradient"></i> Edit Skill';
                formDesc.innerText = `Mengedit data skill "${name}".`;
                skillForm.action = `/admin/skills/${id}`;
                formMethod.value = 'PUT';
                
                nameInput.value = name;
                categoryInput.value = category;
                levelInput.value = level;
                levelRange.value = level;
                
                submitBtn.innerHTML = '<i class="fa-solid fa-floppy-disk"></i> Perbarui Skill';
                cancelBtn.style.display = 'inline-flex';
                
                // Scroll to form on mobile view
                skillForm.scrollIntoView({ behavior: 'smooth' });
            });
        });

        // Cancel Edit Mode
        cancelBtn.addEventListener('click', () => {
            formTitle.innerHTML = '<i class="fa-solid fa-square-plus text-gradient"></i> Tambah Skill';
            formDesc.innerText = 'Masukkan detail kemampuan baru Anda di bawah ini.';
            skillForm.action = "{{ route('admin.skills.store') }}";
            formMethod.value = 'POST';
            
            nameInput.value = '';
            categoryInput.value = 'technical';
            levelInput.value = '80';
            levelRange.value = '80';
            
            submitBtn.innerHTML = '<i class="fa-solid fa-floppy-disk"></i> Simpan Skill';
            cancelBtn.style.display = 'none';
        });
    });
</script>
@endsection
