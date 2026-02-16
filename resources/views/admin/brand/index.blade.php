@extends('admin.master.master')

@section('title')
Company Management | {{ $front_ins_name }}
@endsection

@section('css')
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
<style>
     /* --- Font & Layout Adjustments --- */
    .main-content {
        font-size: 0.9rem; /* Reduced base font size */
    }
    .main-content h2 { font-size: 1.6rem; }
    .main-content h5 { font-size: 1.1rem; }

    /* Forms & Buttons */
    .form-control, .form-select, .btn {
        font-size: 0.875rem; /* Consistent font size for form elements */
    }
    .form-label {
        font-size: 0.85rem;
        font-weight: 500;
        margin-bottom: 0.3rem;
    }
    /* Cards */
    .card-body, .card-header, .card-footer {
        padding: 1rem;
    }

    /* Tables */
    .table {
        font-size: 0.875rem;
    }
    .table th, .table td {
        padding: 0.6rem 0.5rem; /* Reduce padding for a tighter look */
        vertical-align: middle;
    }
    .pagination {
        font-size: 0.875rem;
    }
    </style>
@endsection
@section('body')
<main class="main-content">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
            <h2 class="mb-0">Company List</h2>
            <div class="d-flex align-items-center">
                <form class="d-flex me-2" role="search">
                    <input class="form-control" id="searchInput" type="search" placeholder="Search Companies..." aria-label="Search">
                </form>
                <button type="button" class="btn text-white me-2" style="background-color: #28a745;" data-bs-toggle="modal" data-bs-target="#importModal">
            <i data-feather="file-text" class="me-1" style="width:18px; height:18px;"></i> Import Excel
        </button>
                {{-- @if (Auth::user()->can('CompanyAdd')) --}}
                <a type="button" data-bs-toggle="modal" data-bs-target="#addModal" class="btn text-white" style="background-color: var(--primary-color); white-space: nowrap;">
                    <i data-feather="plus" class="me-1" style="width:18px; height:18px;"></i> Add New Company
                </a>
                {{-- @endif --}}
            </div>
        </div>
<div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="importModalLabel">Import Companies</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('brand.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="d-flex justify-content-end mb-3">
                        <a href="{{ route('brand.import.sample') }}" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-download me-1"></i> Download Sample File
                        </a>
                    </div>

                   <div class="alert alert-info" style="font-size: 0.9rem;">
    <strong>Required Columns:</strong><br>
    company_name, category_name, description, image <br>
    <small class="text-danger">* Category Name must match exactly with existing categories.</small><br>
    <small class="text-muted">* Image column should contain a direct download link (e.g., https://site.com/image.jpg).</small>
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
        <th>Logo</th>
        <th class="sortable" data-column="name">Company Name</th>
        <th>Category</th>
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

@include('admin.brand._partial.addModal')
@include('admin.brand._partial.editModal')
@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
@include('admin.brand._partial.script')
@endsection
