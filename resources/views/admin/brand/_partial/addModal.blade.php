<div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="addModalLabel">Add New Company</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addBrandForm" method="post" action="{{ route('brand.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-12">
        <div class="mb-3">
            <label class="form-label text-dark">Category</label>
            <select name="category_id" class="form-select">
                <option value="">Select Category</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
    </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label text-dark">Company Name</label>
                                <input type="text" name="name" class="form-control" placeholder="Enter Company Name" required>
                            </div>
                        </div>
                        <div class="col-md-12">
    <div class="mb-3">
        <label class="form-label text-dark">Description</label>
        <textarea name="description" id="summernoteAdd" class="form-control" placeholder="Enter Company Description" rows="3"></textarea>
    </div>
</div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label text-dark">Logo</label>
                                <input type="file" accept="image/*" name="logo" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div>
                        <button type="submit" class="btn btn-primary btn-sm w-md mt-4">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
