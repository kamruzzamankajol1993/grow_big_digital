@extends('admin.layout.master')

@section('title', 'Add Team Member')

@section('css')
<style>
    .preview-box { 
        width: 150px; height: 150px; border: 2px dashed #ddd; 
        border-radius: 15px; display: flex; align-items: center; 
        justify-content: center; overflow: hidden; background: #f8fafc;
    }
    .preview-box img { width: 100%; height: 100%; object-fit: cover; display: none; }
    .skill-badge { background: #eef2ff; color: #4f46e5; padding: 5px 12px; border-radius: 20px; font-size: 13px; display: inline-flex; align-items: center; gap: 5px; margin: 5px; }
</style>
@endsection

@section('body')
<div class="container-fluid py-4">
    <div class="custom-width">
        <div class="d-flex align-items-center mb-4">
            <a href="{{ route('team.index') }}" class="btn btn-outline-secondary btn-sm rounded-circle me-3"><i class="bi bi-arrow-left"></i></a>
            <h4 class="fw-bold mb-0">Add New Team Member</h4>
        </div>

        <form action="{{ route('team.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="card border-0 shadow-sm mb-4" style="border-radius: 20px;">
                <div class="card-body p-4 p-md-5">
                    <div class="row g-4">
                        <div class="col-md-12">
                            <label class="form-label fw-bold">Profile Image (Recommended: 500x500 px)</label>
                            <div class="d-flex align-items-center gap-4">
                                <div class="preview-box" id="imagePreviewContainer">
                                    <i class="bi bi-person-bounding-box fs-1 text-muted" id="placeholderIcon"></i>
                                    <img src="" id="imagePreview">
                                </div>
                                <input type="file" name="image" class="form-control" id="imageInput" accept="image/*" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Full Name</label>
                            <input type="text" name="name" class="form-control" placeholder="Member Name" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Designation</label>
                            <input type="text" name="designation" class="form-control" placeholder="e.g. Senior Developer" required>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label fw-bold">Skills (Press Enter to add)</label>
                            <div class="input-group mb-2">
                                <input type="text" id="skillInput" class="form-control" placeholder="e.g. Laravel, React">
                                <button type="button" id="addSkill" class="btn btn-dark">Add</button>
                            </div>
                            <div id="skillContainer" class="d-flex flex-wrap border rounded p-2 bg-light" style="min-height: 50px;">
                                </div>
                            <div id="hiddenSkills"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm" style="border-radius: 20px;">
                <div class="card-header bg-white py-3 border-0">
                    <h6 class="fw-bold mb-0">Social Media Links</h6>
                </div>
                <div class="card-body p-4 pt-0">
                    <div id="socialRows">
                        <div class="row g-2 mb-3 align-items-end">
                            <div class="col-md-5">
                                <label class="small text-muted">Platform Name</label>
                                <input type="text" name="social_titles[]" class="form-control" placeholder="e.g. LinkedIn">
                            </div>
                            <div class="col-md-6">
                                <label class="small text-muted">Profile URL</label>
                                <input type="url" name="social_links[]" class="form-control" placeholder="https://...">
                            </div>
                            <div class="col-md-1">
                                <button type="button" class="btn btn-outline-danger w-100 remove-row"><i class="bi bi-trash"></i></button>
                            </div>
                        </div>
                    </div>
                    <button type="button" id="addSocial" class="btn btn-sm btn-outline-primary"><i class="bi bi-plus-circle me-1"></i> Add More Social Link</button>
                    
                    <div class="text-end mt-4 pt-3 border-top">
                        <button type="submit" class="btn btn-success px-5 rounded-pill fw-bold">Save Member</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Image Preview
    document.getElementById('imageInput').onchange = evt => {
        const [file] = document.getElementById('imageInput').files;
        if (file) {
            document.getElementById('imagePreview').src = URL.createObjectURL(file);
            document.getElementById('imagePreview').style.display = 'block';
            document.getElementById('placeholderIcon').style.display = 'none';
        }
    }

    // Skills Logic
    let skills = [];
    const skillInput = document.getElementById('skillInput');
    const skillContainer = document.getElementById('skillContainer');
    const hiddenSkills = document.getElementById('hiddenSkills');

    function renderSkills() {
        skillContainer.innerHTML = '';
        hiddenSkills.innerHTML = '';
        skills.forEach((skill, index) => {
            skillContainer.innerHTML += `<span class="skill-badge">${skill} <i class="bi bi-x-circle-fill ms-1 text-danger cursor-pointer" onclick="removeSkill(${index})"></i></span>`;
            hiddenSkills.innerHTML += `<input type="hidden" name="skills[]" value="${skill}">`;
        });
    }

    document.getElementById('addSkill').onclick = () => {
        if(skillInput.value) {
            skills.push(skillInput.value);
            skillInput.value = '';
            renderSkills();
        }
    }

    function removeSkill(index) {
        skills.splice(index, 1);
        renderSkills();
    }

    // Dynamic Social Rows
    document.getElementById('addSocial').onclick = () => {
        const row = `<div class="row g-2 mb-3 align-items-end">
            <div class="col-md-5"><input type="text" name="social_titles[]" class="form-control" placeholder="Platform Name"></div>
            <div class="col-md-6"><input type="url" name="social_links[]" class="form-control" placeholder="Profile URL"></div>
            <div class="col-md-1"><button type="button" class="btn btn-outline-danger w-100 remove-row"><i class="bi bi-trash"></i></button></div>
        </div>`;
        document.getElementById('socialRows').insertAdjacentHTML('beforeend', row);
    };

    $(document).on('click', '.remove-row', function() { $(this).closest('.row').remove(); });
</script>
@endsection