@extends('admin.master.master')

@section('title')
Category Details | {{ $front_ins_name ?? 'Admin' }}
@endsection

@section('css')
<style>
    /* --- Description Content Fixes --- */
    .description-content {
        overflow-wrap: break-word;
        word-wrap: break-word;
        word-break: break-word;
        color: #333; /* Ensure text is visible */
    }
    .description-content img {
        max-width: 100% !important;
        height: auto !important;
        border-radius: 4px;
    }
    .description-content table {
        display: block;
        width: 100% !important;
        overflow-x: auto;
        border-collapse: collapse;
    }
    .description-content iframe {
        max-width: 100% !important;
    }
</style>
@endsection

@section('body')
<main class="main-content">
    <div class="container-fluid">
        <div class="page-header d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="mb-0 text-primary">{{ $category->name }}</h2>
                <p class="text-muted mb-0">Company Category Details</p>
            </div>
            <a href="{{ route('company-category.index') }}" class="btn btn-secondary">
                <i class="fa fa-arrow-left me-1"></i> Back to List
            </a>
        </div>

        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <div class="card-body text-center d-flex flex-column justify-content-center align-items-center">
                        @if($category->image)
                            <img src="{{ asset('public') }}/{{ $category->image }}" alt="{{ $category->name }}" class="img-fluid rounded shadow-sm" style="max-height: 250px;">
                        @else
                            <div class="bg-light d-flex align-items-center justify-content-center rounded" style="width: 200px; height: 200px;">
                                <span class="text-muted">No Image</span>
                            </div>
                        @endif
                        <h4 class="mt-3">{{ $category->name }}</h4>
                        <span class="badge {{ $category->status == 1 ? 'bg-success' : 'bg-danger' }}">
                            {{ $category->status == 1 ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-md-8 mb-4">
                <div class="card h-100">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Information</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th style="width: 200px;">Category Name</th>
                                    <td>{{ $category->name }}</td>
                                </tr>
                                <tr>
                                    <th>Company</th>
                                    <td>
                                        @if($category->company)
                                            <span class="badge bg-primary text-white">{{ $category->company->name }}</span>
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Parent Category</th>
                                    <td>
                                        @if($category->parent)
                                            <span class="badge bg-info text-dark">{{ $category->parent->name }}</span>
                                        @else
                                            <span class="badge bg-secondary">Root</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Slug</th>
                                    <td>{{ $category->slug }}</td>
                                </tr>
                            </tbody>
                        </table>

                        <div class="mt-4">
                            <h6 class="fw-bold">Description</h6>
                            <div class="p-3 bg-light rounded border description-content">
                                {!! $category->description ?? '<span class="text-muted">No description available.</span>' !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection