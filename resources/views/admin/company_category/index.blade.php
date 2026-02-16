@extends('admin.master.master')

@section('title')
Company Category | {{ $front_ins_name ?? 'Admin' }}
@endsection

@section('css')
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
<style>
    .main-content { font-size: 0.9rem; }
    .main-content h2 { font-size: 1.6rem; }
    .main-content h5 { font-size: 1.1rem; }
    .form-control, .form-select, .btn { font-size: 0.875rem; }
    .form-label { font-size: 0.85rem; font-weight: 500; margin-bottom: 0.3rem; }
    .card-body, .card-header, .card-footer { padding: 1rem; }
    .table { font-size: 0.875rem; }
    .table th, .table td { padding: 0.6rem 0.5rem; vertical-align: middle; }
    .pagination { font-size: 0.875rem; }
</style>
@endsection

@section('body')
<main class="main-content">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
            <h2 class="mb-0">Company Category List</h2>
            <div class="d-flex align-items-center">
                <form class="d-flex me-2" role="search">
                    <input class="form-control" id="searchInput" type="search" placeholder="Search..." aria-label="Search">
                </form>

                <button type="button" class="btn text-white me-2" style="background-color: #28a745;" data-bs-toggle="modal" data-bs-target="#importModal">
                    <i data-feather="file-text" class="me-1" style="width:18px; height:18px;"></i> Import Excel
                </button>

                <button type="button" data-bs-toggle="modal" data-bs-target="#addModal" class="btn text-white" style="background-color: var(--primary-color); white-space: nowrap;">
                    <i data-feather="plus" class="me-1" style="width:18px; height:18px;"></i> Add New
                </button>
            </div>
        </div>

        <div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="importModalLabel">Import Categories</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('company-category.import') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <div class="d-flex justify-content-end mb-3">
                                <a href="{{ route('company-category.import.sample') }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-download me-1"></i> Download Sample File
                                </a>
                            </div>

                            <div class="alert alert-info" style="font-size: 0.9rem;">
                                <strong>Required Columns:</strong><br>
                                company_name, category_name, parent_category, description, image <br>
                                <small class="text-danger">
                                    * <strong>parent_category</strong> is optional.<br>
                                    * <strong>image</strong> should be a direct download link.
                                </small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Choose Excel/CSV File</label>
                                <input type="file" name="file" class="form-control" required accept=".xlsx,.xls,.csv">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-success">Upload & Import</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                @include('flash_message')
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <thead>
                            <tr>
                                <th>Sl</th>
                                <th>Image</th>
                                <th>Company</th>
                                <th>Parent Category</th>
                                <th class="sortable" data-column="name">Category Name</th>
                                <th class="sortable" data-column="status">Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="tableBody"></tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-white d-flex justify-content-between align-items-center">
                <div class="text-muted"></div>
                <nav>
                    <ul class="pagination justify-content-center" id="pagination"></ul>
                </nav>
            </div>
        </div>
    </div>
</main>

@include('admin.company_category._partial.addModal')
@include('admin.company_category._partial.editModal')
@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
@include('admin.company_category._partial.script')
@endsection