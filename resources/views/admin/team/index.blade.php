@extends('admin.layout.master')

@section('title', 'Team Members')

@section('css')
<style>
    .member-img { width: 45px; height: 45px; object-fit: cover; border-radius: 50%; border: 2px solid #eee; }
    .table-card { border-radius: 15px; border: none; box-shadow: 0 5px 15px rgba(0,0,0,0.05); overflow: hidden; }
    
    /* ট্যাব স্টাইল */
    .nav-tabs-custom { border-bottom: none; gap: 12px; background: #f1f5f9; padding: 8px; border-radius: 16px; display: inline-flex; }
    .nav-tabs-custom .nav-link { border: none; border-radius: 12px; padding: 10px 20px; color: #475569 !important; font-weight: 700; transition: all 0.3s; }
    .nav-tabs-custom .nav-link.active { background: #00a651 !important; color: #fff !important; box-shadow: 0 4px 12px rgba(0, 166, 81, 0.2); }
    
    .btn-add { background: #00a651; color: #fff; border-radius: 10px; font-weight: 600; padding: 8px 20px; transition: 0.3s; }
    .btn-add:hover { background: #008541; color: #fff; transform: translateY(-2px); }
    .social-icon-sm { font-size: 14px; color: #64748b; margin-right: 5px; }
</style>
@endsection

@section('body')
<div class="container-fluid py-4">
    <div class="custom-width">
        <div class="mb-4">
            <ul class="nav nav-tabs nav-tabs-custom shadow-sm">
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('admin/team/members*') ? 'active' : '' }}" href="{{ route('team.index') }}">
                        <i class="bi bi-people-fill me-2"></i> Team Members
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('admin/team/header-settings*') ? 'active' : '' }}" href="{{ route('team.header.settings') }}">
                        <i class="bi bi-gear-wide-connected me-2"></i> Header Settings
                    </a>
                </li>
            </ul>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="fw-bold mb-0">Our Professional Team</h4>
            <a href="{{ route('team.create') }}" class="btn btn-add shadow-sm">
                <i class="bi bi-person-plus-fill me-1"></i> Add Member
            </a>
        </div>

        <div class="card table-card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4" style="width: 70px;">Photo</th>
                                <th>Name</th>
                                <th>Designation</th>
                                <th>Expertise</th>
                                <th>Social</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($members as $member)
                            <tr>
                                <td class="ps-4">
                                    <img src="{{ asset('public/'.$member->image) }}" class="member-img">
                                </td>
                                <td>
                                    <div class="fw-bold text-dark">{{ $member->name }}</div>
                                    <small class="text-muted">ID: #TM-{{ $member->id }}</small>
                                </td>
                                <td><span class="badge bg-soft-success text-success p-2 px-3">{{ $member->designation }}</span></td>
                                <td>
                                    @if($member->skills)
                                        @foreach(array_slice($member->skills, 0, 2) as $skill)
                                            <span class="badge bg-light text-secondary border">{{ $skill }}</span>
                                        @endforeach
                                        @if(count($member->skills) > 2)
                                            <span class="text-muted small">+{{ count($member->skills) - 2 }} more</span>
                                        @endif
                                    @endif
                                </td>
                                <td>
                                   @foreach($member->socialLinks as $social)
    <i class="bi bi-{{ \Illuminate\Support\Str::lower($social->title) }} social-icon-sm" 
       title="{{ $social->title }}"
       style="font-size: 1.2rem; margin-right: 5px;"></i>
@endforeach
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2">
                                        <a href="{{ route('team.show', $member->id) }}" class="btn btn-sm btn-outline-info rounded-circle" title="View Profile">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('team.edit', $member->id) }}" class="btn btn-sm btn-outline-primary rounded-circle" title="Edit">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <form action="{{ route('team.destroy', $member->id) }}" method="POST" class="delete-form">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger rounded-circle" title="Delete">
                                                <i class="bi bi-trash3"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="bi bi-people fs-1 d-block mb-2"></i>
                                    No team members added yet.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-white border-0 py-3">
                {{ $members->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        $('.delete-form').submit(function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Are you sure?',
                text: "Member data and social links will be removed!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, remove them!'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.submit();
                }
            });
        });
    });
</script>
@endsection