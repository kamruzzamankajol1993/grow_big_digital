@extends('admin.layout.master')

@section('title', 'Edit Team Member')

@section('css')
<style>
    .custom-width { max-width: 900px; margin: 0 auto; }
    .preview-box { width: 150px; height: 150px; border: 2px dashed #00a651; border-radius: 15px; overflow: hidden; }
    .preview-box img { width: 100%; height: 100%; object-fit: cover; }
    .skill-badge { background: #eef2ff; color: #4f46e5; padding: 5px 12px; border-radius: 20px; font-size: 13px; display: inline-flex; align-items: center; gap: 5px; margin: 5px; }
</style>
@endsection

@section('body')
<div class="container-fluid py-4">
    <div class="custom-width">
        <div class="d-flex align-items-center mb-4">
            <a href="{{ route('team.index') }}" class="btn btn-outline-secondary btn-sm rounded-circle me-3"><i class="bi bi-arrow-left"></i></a>
            <h4 class="fw-bold mb-0">Edit Team Member</h4>
        </div>

        <form action="{{ route('team.update', $member->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="card border-0 shadow-sm mb-4" style="border-radius: 20px;">
                <div class="card-body p-4 p-md-5">
                    <div class="row g-4">
                        <div class="col-md-12">
                            <label class="form-label fw-bold">Profile Image</label>
                            <div class="d-flex align-items-center gap-4">
                                <div class="preview-box">
                                    <img src="{{ asset($member->image) }}" id="imagePreview">
                                </div>
                                <input type="file" name="image" class="form-control" id="imageInput" accept="image/*">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Full Name</label>
                            <input type="text" name="name" value="{{ $member->name }}" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Designation</label>
                            <input type="text" name="designation" value="{{ $member->designation }}" class="form-control" required>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label fw-bold">Skills</label>
                            <div class="input-group mb-2">
                                <input type="text" id="skillInput" class="form-control" placeholder="Add skills">
                                <button type="button" id="addSkill" class="btn btn-dark">Add</button>
                            </div>
                            <div id="skillContainer" class="d-flex flex-wrap border rounded p-2 bg-light">
                                @foreach($member->skills as $skill)
                                    <span class="skill-badge">{{ $skill }} <i class="bi bi-x-circle-fill ms-1 text-danger cursor-pointer" onclick="this.parentElement.remove(); renderHiddenSkills();"></i></span>
                                @endforeach
                            </div>
                            <div id="hiddenSkills">
                                @foreach($member->skills as $skill)
                                    <input type="hidden" name="skills[]" value="{{ $skill }}">
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm" style="border-radius: 20px;">
                <div class="card-header bg-white py-3 border-0"><h6 class="fw-bold mb-0">Social Media Links</h6></div>
                <div class="card-body p-4 pt-0">
                    <div id="socialRows">
                        @forelse($member->socialLinks as $social)
                        <div class="row g-2 mb-3 align-items-end">
                            <div class="col-md-5"><input type="text" name="social_titles[]" value="{{ $social->title }}" class="form-control"></div>
                            <div class="col-md-6"><input type="url" name="social_links[]" value="{{ $social->link }}" class="form-control"></div>
                            <div class="col-md-1"><button type="button" class="btn btn-outline-danger w-100 remove-row"><i class="bi bi-trash"></i></button></div>
                        </div>
                        @empty
                        <div class="row g-2 mb-3 align-items-end">
                            <div class="col-md-5"><input type="text" name="social_titles[]" class="form-control" placeholder="Platform"></div>
                            <div class="col-md-6"><input type="url" name="social_links[]" class="form-control" placeholder="URL"></div>
                            <div class="col-md-1"><button type="button" class="btn btn-outline-danger w-100 remove-row"><i class="bi bi-trash"></i></button></div>
                        </div>
                        @endforelse
                    </div>
                    <button type="button" id="addSocial" class="btn btn-sm btn-outline-primary">+ Add More</button>
                    <div class="text-end mt-4 pt-3 border-top"><button type="submit" class="btn btn-primary px-5 rounded-pill fw-bold">Update Member</button></div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // ইমেজ প্রিভিউ
    document.getElementById('imageInput').onchange = evt => {
        const [file] = document.getElementById('imageInput').files;
        if (file) document.getElementById('imagePreview').src = URL.createObjectURL(file);
    }

    // স্কিল রেন্ডার এবং হ্যান্ডেলিং
    function renderHiddenSkills() {
        const container = document.getElementById('hiddenSkills');
        container.innerHTML = '';
        document.querySelectorAll('#skillContainer .skill-badge').forEach(badge => {
            const skillValue = badge.innerText.trim();
            container.innerHTML += `<input type="hidden" name="skills[]" value="${skillValue}">`;
        });
    }

    document.getElementById('addSkill').onclick = () => {
        const val = document.getElementById('skillInput').value;
        if(val) {
            document.getElementById('skillContainer').innerHTML += `<span class="skill-badge">${val} <i class="bi bi-x-circle-fill ms-1 text-danger cursor-pointer" onclick="this.parentElement.remove(); renderHiddenSkills();"></i></span>`;
            document.getElementById('skillInput').value = '';
            renderHiddenSkills();
        }
    }

    document.getElementById('addSocial').onclick = () => {
        const row = `<div class="row g-2 mb-3 align-items-end">
            <div class="col-md-5"><input type="text" name="social_titles[]" class="form-control"></div>
            <div class="col-md-6"><input type="url" name="social_links[]" class="form-control"></div>
            <div class="col-md-1"><button type="button" class="btn btn-outline-danger w-100 remove-row"><i class="bi bi-trash"></i></button></div>
        </div>`;
        document.getElementById('socialRows').insertAdjacentHTML('beforeend', row);
    };

    $(document).on('click', '.remove-row', function() { $(this).closest('.row').remove(); });
</script>
@endsection