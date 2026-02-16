@extends('admin.master.master')
@section('title', 'Create Product')
@section('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        /* --- Modern Root Variables --- */
        /* --- Global Layout --- */
        .main-content {
            font-size: 0.925rem;
            background-color: #f9fafb;
            min-height: 100vh;
        }
        .main-content h2 { 
            font-size: 1.75rem; 
            font-weight: 700; 
            color: #111827; 
            letter-spacing: -0.025em;
        }
        
        /* --- Modern Cards --- */
        .card {
            border: none;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
            border-radius: 12px;
            background: #fff;
            transition: transform 0.2s ease;
        }
        .card-body { padding: 1.5rem; }
        .card-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: #111827;
            border-bottom: 2px solid #f9fafb;
            padding-bottom: 0.75rem;
            margin-bottom: 1.25rem;
        }

        /* --- Modern Inputs --- */
        .form-label {
            font-weight: 500;
            color: #374151;
            margin-bottom: 0.4rem;
            font-size: 0.875rem;
        }
        .form-control, .form-select {
            border-radius: 8px;
            border: 1px solid #e5e7eb;;
            padding: 0.625rem 0.875rem;
            font-size: 0.875rem;
            background-color: #fff;
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }
        .form-control:focus, .form-select:focus {
            border-color: #4f46e5;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
            outline: 0;
        }

        /* --- Upload Zone Style --- */
        .upload-zone-wrapper {
            position: relative;
            border: 2px dashed #d1d5db;
            border-radius: 12px;
            background-color: #f9fafb;
            padding: 1.5rem;
            text-align: center;
            transition: all 0.2s;
        }
        .upload-zone-wrapper:hover {
            border-color: #4f46e5;
            background-color: #eef2ff;
        }
        .upload-zone-wrapper input[type="file"] {
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            opacity: 0;
            cursor: pointer;
        }
        .upload-placeholder {
            pointer-events: none;
            color: #6b7280;
        }
        .upload-placeholder i { font-size: 2rem; margin-bottom: 0.5rem; display: block; }
        
        /* --- Category Tree Modernized --- */
        .category-tree-container {
            border: 1px solid #e5e7eb;
            padding: 1rem;
            border-radius: 8px;
            max-height: 300px;
            overflow-y: auto;
            background-color: #fff;
        }
        .category-tree-item { margin-bottom: 0.25rem; }
        .category-tree-child {
            padding-left: 1.25rem;
            border-left: 2px solid #f3f4f6;
            margin-left: 0.6rem;
            margin-top: 0.25rem;
        }
        .toggle-icon {
            cursor: pointer;
            color: #6b7280;
            font-size: 0.85rem;
            margin-right: 5px;
            transition: color 0.2s;
        }
        .toggle-icon:hover { color: #4f46e5; }
        .form-check-label { font-size: 0.9rem; cursor: pointer; user-select: none; }

        /* --- Buttons --- */
        .btn-primary {
            background-color: #4f46e5;
            border-color: #4f46e5;
            padding: 0.6rem 1.5rem;
            border-radius: 8px;
            font-weight: 500;
        }
        .btn-primary:hover {
            background-color: #4338ca;
            border-color: #4338ca;
        }

        /* --- Select2 Customization to match theme --- */
        .select2-container .select2-selection--single {
            height: 42px; /* Match form-control height */
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 6px 12px;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 40px;
            right: 8px;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 28px;
            color: #374151;
            padding-left: 0;
        }
        .select2-dropdown {
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
    </style>
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endsection
@section('body')
<main class="main-content">
    <div class="container-fluid py-3">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Create New Product</h2>
        </div>
        <form action="{{ route('product.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row g-4">
                <div class="col-md-8">
                    {{-- Main Product Fields --}}
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
                                <h5 class="card-title mb-0 border-0 p-0">Main Information</h5>
                                <span class="badge bg-light text-danger border border-danger">
                                    <i class="fas fa-info-circle me-1"></i> 600x600px 
                                </span>
                            </div>
                            
                            <div class="mb-4">
                                <label class="form-label">Thumbnail Images</label>
                                <div class="upload-zone-wrapper">
                                    <div class="upload-placeholder">
                                        <i class="fas fa-cloud-upload-alt text-primary"></i>
                                        <span>Click or Drop images here</span>
                                    </div>
                                    <input type="file" accept="image/*" name="thumbnail_image[]" id="thumbnailInput" multiple>
                                </div>
                                <div id="thumbnail-preview-container" class="mt-3 d-flex flex-wrap gap-3"></div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Product Name <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" placeholder="e.g. Premium Cotton Shirt" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Product Code</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0"><i class="fas fa-barcode text-muted"></i></span>
                                        <input type="text" name="product_code" id="product_code" class="form-control border-start-0 ps-0" value="{{ old('product_code') }}" placeholder="Auto-generated">
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea name="description" id="summernote" class="form-control" rows="4">{{ old('description') }}</textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Specification</label>
                                <textarea name="specification" id="summernote2" class="form-control" rows="4">{{ old('specification') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    {{-- Pricing & Organization --}}
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title"><i class="fas fa-tags me-2 text-primary"></i>Pricing & Details</h5>
                            
                            <div class="row g-2">
                                <div class="col-6 mb-3">
                                    <label class="form-label">Purchase Price</label>
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text">$</span>
                                        <input type="number" name="purchase_price" class="form-control" value="{{ old('purchase_price') }}" required step="0.01">
                                    </div>
                                </div>
                                <div class="col-6 mb-3">
                                    <label class="form-label">Base Price</label>
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text">$</span>
                                        <input type="number" name="base_price" class="form-control" value="{{ old('base_price') }}" step="0.01">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Discount Price</label>
                                <div class="input-group">
                                    <span class="input-group-text text-success">$</span>
                                    <input type="number" name="discount_price" class="form-control" value="{{ old('discount_price') }}" step="0.01">
                                </div>
                            </div>
                            
                            <hr class="my-4" style="border-top-style: dashed;">

                            <div class="mb-3">
                                <label class="form-label">Company</label>
                                <select name="brand_id" id="brand_id" class="form-select select2" disabled style="width: 100%;">
                                    <option value="">Select Category First</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Category <span class="text-danger">*</span></label>
                                <select name="category_id" id="category_id" class="form-select select2" required style="width: 100%;">
                                    <option value="">Select Category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Company Categories</label>
                                <div class="category-tree-container">
                                    <ul class="list-unstyled mb-0" id="company-category-tree">
                                        <li class="text-muted small text-center mt-3">Select a Company to view categories</li>
                                    </ul>
                                </div>
                                @error('category_ids')
                                    <div class="text-danger mt-1 small"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</div>
                                @enderror
                            </div>
                           
                            <hr class="my-4" style="border-top-style: dashed;">
                            
                            <div class="form-check form-switch p-3 bg-light rounded-3 d-flex align-items-center justify-content-between">
                                <label class="form-check-label fw-bold mb-0" for="status">Active Status</label>
                                <input class="form-check-input ms-0" type="checkbox" name="status" value="1" id="status" checked style="width: 3em; height: 1.5em; cursor: pointer;">
                            </div>
              
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="d-flex justify-content-end mt-4 pb-5">
                <a href="{{ url()->previous() }}" class="btn btn-light border me-2">Cancel</a>
                <button type="submit" class="btn btn-primary px-4 py-2 shadow-sm"><i class="fas fa-save me-2"></i>Save Product</button>
            </div>
        </form>
    </div>
</main>
@endsection
@section('script')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    
    // Initialize Select2
    $('.select2').select2();

    // --- Category Tree Toggle (Icons) ---
    $('.category-tree-container').on('click', '.toggle-icon', function(e) {
        e.preventDefault();
        $(this).toggleClass('fa-plus-square fa-minus-square');
        $(this).closest('.category-tree-item').children('.category-tree-child').slideToggle('fast');
    });

    // --- Auto-generate Product Code ---
    $('input[name="name"]').on('keyup change', function() {
        const productName = $(this).val();
        const productCodeInput = $('#product_code'); 
        
        if (productName.length > 2 && !productCodeInput.val()) {
            const prefix = productName.substring(0, 4).toUpperCase().replace(/\s+/g, '');
            const timestamp = Date.now().toString().slice(-5);
            productCodeInput.val(`${prefix}-${timestamp}`);
        }
    });

     // --- Generic Image Preview Logic with Modern Styling ---
    function createImagePreviewer(inputId, containerId) {
        const inputElement = document.getElementById(inputId);
        const previewContainer = document.getElementById(containerId);
        const dataTransfer = new DataTransfer();

        function renderPreviews() {
            previewContainer.innerHTML = '';
            const files = Array.from(inputElement.files);

            files.forEach((file, index) => {
                if (!file.type.startsWith('image/')) { return; }
                const reader = new FileReader();
                reader.onload = function(e) {
                    const wrapper = document.createElement('div');
                    wrapper.className = 'position-relative shadow-sm rounded overflow-hidden';
                    wrapper.style.width = '80px';
                    wrapper.style.height = '80px';
                    
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = 'w-100 h-100 object-fit-cover';

                    const removeBtn = document.createElement('button');
                    removeBtn.type = 'button';
                    removeBtn.innerHTML = '<i class="fas fa-times"></i>';
                    removeBtn.classList.add('btn', 'btn-danger', 'btn-sm', 'remove-preview-btn', 'p-0', 'd-flex', 'align-items-center', 'justify-content-center');
                    removeBtn.dataset.index = index;
                    removeBtn.style.cssText = 'position: absolute; top: 2px; right: 2px; width: 20px; height: 20px; border-radius: 50%; font-size: 10px;';

                    wrapper.appendChild(img);
                    wrapper.appendChild(removeBtn);
                    previewContainer.appendChild(wrapper);
                };
                reader.readAsDataURL(file);
            });
        }

        inputElement.addEventListener('change', function() {
            for (const file of this.files) {
                dataTransfer.items.add(file);
            }
            this.files = dataTransfer.files;
            renderPreviews();
        });

        previewContainer.addEventListener('click', function(e) {
            if (e.target.closest('.remove-preview-btn')) {
                const btn = e.target.closest('.remove-preview-btn');
                const indexToRemove = parseInt(btn.dataset.index, 10);
                const newFiles = new DataTransfer();
                const currentFiles = Array.from(inputElement.files);
                
                currentFiles.forEach((file, index) => {
                    if (index !== indexToRemove) {
                        newFiles.items.add(file);
                    }
                });

                dataTransfer.items.clear();
                Array.from(newFiles.files).forEach(file => dataTransfer.items.add(file));
                inputElement.files = newFiles.files;

                renderPreviews();
            }
        });
    }

    createImagePreviewer('thumbnailInput', 'thumbnail-preview-container');

    // --- AJAX LOGIC FOR DEPENDENT DROPDOWNS ---
    $('#category_id').on('change', function() {
        var categoryId = $(this).val();
        var brandSelect = $('#brand_id');
        var treeContainer = $('#company-category-tree');

        // Reset fields
        brandSelect.html('<option value="">Loading...</option>').prop('disabled', true);
        treeContainer.html('<li class="text-muted small text-center mt-3">Select a Company to view categories</li>');

        if (categoryId) {
            $.ajax({
                url: "{{ url('/get-brands-by-category') }}/" + categoryId,
                type: "GET",
                dataType: "json",
                success: function(data) {
                    brandSelect.html('<option value="">Select Company</option>');
                    if (data.length > 0) {
                        $.each(data, function(key, value) {
                            brandSelect.append('<option value="' + value.id + '">' + value.name + '</option>');
                        });
                        brandSelect.prop('disabled', false);
                    } else {
                        brandSelect.append('<option value="">No companies found</option>');
                    }
                    // Trigger Select2 update
                    brandSelect.trigger('change');
                },
                error: function() {
                    brandSelect.html('<option value="">Error loading companies</option>');
                    brandSelect.trigger('change');
                }
            });
        } else {
            brandSelect.html('<option value="">Select Category First</option>');
            brandSelect.trigger('change');
        }
    });

    $('#brand_id').on('change', function() {
        var brandId = $(this).val();
        var treeContainer = $('#company-category-tree');

        if(brandId == "") {
             treeContainer.html('<li class="text-muted small text-center mt-3">Select a Company to view categories</li>');
             return;
        }

        treeContainer.html('<li class="text-center mt-3"><div class="spinner-border spinner-border-sm text-primary"></div> Loading...</li>');

        $.ajax({
            url: "{{ url('/get-company-categories-by-brand') }}/" + brandId,
            type: "GET",
            dataType: "json",
            success: function(data) {
                if (data.html) {
                    treeContainer.html(data.html);
                } else {
                    treeContainer.html('<li class="text-muted small text-center mt-3">No categories available for this company.</li>');
                }
            },
            error: function() {
                 treeContainer.html('<li class="text-danger small text-center mt-3">Error fetching categories.</li>');
            }
        });
    });
});
</script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
<script>
    $(document).ready(function() {
        $('#summernote, #summernote2').summernote({
            height: 250,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'italic', 'underline', 'clear']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'hr']],
                ['view', ['fullscreen', 'codeview']]
            ],
            styleTags: ['p', 'h4', 'h5', 'h6'],
        });
    });
</script>
@endsection