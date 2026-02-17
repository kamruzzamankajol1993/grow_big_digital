@extends('admin.layout.master')

@section('title', 'Member Profile - ' . $member->name)

@section('css')
<style>
    .profile-card { border-radius: 20px; border: none; overflow: hidden; }
    .profile-cover { height: 120px; background: linear-gradient(45deg, #00a651, #008541); }
    .profile-img-container { margin-top: -60px; position: relative; }
    .profile-img { width: 120px; height: 120px; object-fit: cover; border: 5px solid #fff; border-radius: 50%; box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
    .skill-badge { background: #f1f5f9; color: #475569; padding: 6px 15px; border-radius: 50px; font-weight: 600; font-size: 13px; margin: 4px; display: inline-block; }
    .social-btn { width: 40px; height: 40px; display: inline-flex; align-items: center; justify-content: center; border-radius: 10px; background: #f8fafc; color: #64748b; transition: 0.3s; font-size: 1.2rem; }
    .social-btn:hover { background: #00a651; color: #fff; transform: translateY(-3px); }
    .info-label { font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px; color: #94a3b8; font-weight: 700; }
</style>
@endsection

@section('body')
<div class="container-fluid py-5">
    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8">
            <div class="mb-4">
                <a href="{{ route('team.index') }}" class="btn btn-sm btn-light rounded-pill px-3">
                    <i class="bi bi-arrow-left me-1"></i> Back to Team
                </a>
            </div>

            <div class="card profile-card shadow-sm">
                <div class="profile-cover"></div>
                <div class="card-body text-center p-4">
                    <div class="profile-img-container mb-3">
                        <img src="{{ asset($member->image) }}" alt="{{ $member->name }}" class="profile-img">
                    </div>
                    
                    <h3 class="fw-bold text-dark mb-1">{{ $member->name }}</h3>
                    <p class="text-success fw-semibold mb-3">{{ $member->designation }}</p>

                    <div class="d-flex justify-content-center gap-2 mb-4">
                        @foreach($member->socialLinks as $social)
                            <a href="{{ $social->link }}" target="_blank" class="social-btn" title="{{ $social->title }}">
                                @php
                                    $icon = match(strtolower($social->title)) {
                                        'facebook'  => 'bi-facebook',
                                        'linkedin'  => 'bi-linkedin',
                                        'twitter'   => 'bi-twitter-x',
                                        'instagram' => 'bi-instagram',
                                        'github'    => 'bi-github',
                                        default     => 'bi-link-45deg',
                                    };
                                @endphp
                                <i class="bi {{ $icon }}"></i>
                            </a>
                        @endforeach
                    </div>

                    <hr class="my-4 opacity-50">

                    <div class="text-start px-md-3">
                        <div class="mb-4">
                            <label class="info-label d-block mb-2">Expertise & Skills</label>
                            <div class="d-flex flex-wrap">
                                @if($member->skills)
                                    @foreach($member->skills as $skill)
                                        <span class="skill-badge"><i class="bi bi-check2-circle text-success me-1"></i> {{ $skill }}</span>
                                    @endforeach
                                @else
                                    <span class="text-muted small">No skills listed</span>
                                @endif
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-6">
                                <label class="info-label">Member Since</label>
                                <p class="text-dark fw-medium small">{{ $member->created_at->format('M d, Y') }}</p>
                            </div>
                            <div class="col-6 text-end">
                                <label class="info-label">Last Updated</label>
                                <p class="text-dark fw-medium small">{{ $member->updated_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card-footer bg-light border-0 p-3 text-center">
                    <div class="d-flex justify-content-center gap-2">
                        <a href="{{ route('team.edit', $member->id) }}" class="btn btn-primary btn-sm rounded-pill px-4">
                            <i class="bi bi-pencil-square me-1"></i> Edit Profile
                        </a>
                        <form action="{{ route('team.destroy', $member->id) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill px-4">
                                <i class="bi bi-trash"></i> Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection