<div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form id="editForm" class="modal-content" enctype="multipart/form-data">
            <input type="hidden" id="editId">
            <div class="modal-header">
                <h5 class="modal-title">Edit Company Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-dark">Company <span class="text-danger">*</span></label>
                        <select id="editCompanyId" name="company_id" class="form-select" required>
                            <option value="">Select Company</option>
                            @foreach($companies as $company)
                                <option value="{{ $company->id }}">{{ $company->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label text-dark">Parent Category</label>
                        <select id="editParentId" name="parent_id" class="form-select">
                            <option value="">No Parent (Root)</option>
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label text-dark">Category Name <span class="text-danger">*</span></label>
                    <input type="text" id="editName" name="name" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label text-dark">Image (400x400)</label>
                    <input type="file" id="editImage" name="image" class="form-control" accept="image/*">
                    <div class="mt-2">
                        <img id="editImagePreview" src="" alt="Preview" class="img-thumbnail" style="width: 80px; height: 80px; object-fit: cover; display: none;">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label text-dark">Description</label>
                    <textarea id="summernoteEdit" name="description" class="form-control" rows="3"></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label text-dark">Status</label>
                    <select id="editStatus" name="status" class="form-control">
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary">Save Changes</button>
            </div>
        </form>
    </div>
</div>