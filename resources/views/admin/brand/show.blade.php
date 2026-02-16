@extends('admin.master.master')

@section('title')
Company Details | {{ $front_ins_name ?? 'Admin' }}
@endsection

@section('body')
<main class="main-content">
    <div class="container-fluid">
        <div class="page-header d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="mb-0 text-primary">{{ $brand->name }}</h2>
                <p class="text-muted mb-0">Company Details</p>
            </div>
            <a href="{{ route('brand.index') }}" class="btn btn-secondary">
                <i class="fa fa-arrow-left me-1"></i> Back to List
            </a>
        </div>

        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <div class="card-body text-center d-flex flex-column justify-content-center align-items-center">
                        @if($brand->logo)
                            <img src="{{ asset('public') }}/{{ $brand->logo }}" alt="{{ $brand->name }}" class="img-fluid rounded shadow-sm" style="max-height: 250px;">
                        @else
                            <div class="bg-light d-flex align-items-center justify-content-center rounded" style="width: 200px; height: 200px;">
                                <span class="text-muted">No Logo</span>
                            </div>
                        @endif
                        <h4 class="mt-3">{{ $brand->name }}</h4>
                        <span class="badge {{ $brand->status == 1 ? 'bg-success' : 'bg-danger' }}">
                            {{ $brand->status == 1 ? 'Active' : 'Inactive' }}
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
                                    <th style="width: 200px;">Company Name</th>
                                    <td>{{ $brand->name }}</td>
                                </tr>
                                <tr>
                                    <th>Category</th>
                                    <td>
                                        @if($brand->category)
                                            <span class="badge bg-info text-dark">{{ $brand->category->name }}</span>
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Slug</th>
                                    <td>{{ $brand->slug }}</td>
                                </tr>
                                <tr>
                                    <th>Created At</th>
                                    <td>{{ $brand->created_at->format('d M, Y h:i A') }}</td>
                                </tr>
                            </tbody>
                        </table>

                        <div class="mt-4">
                            <h6 class="fw-bold">Description</h6>
                            <div class="p-3 bg-light rounded border">
                                {!! $brand->description ?? '<span class="text-muted">No description available.</span>' !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection