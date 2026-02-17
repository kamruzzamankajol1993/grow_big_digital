@extends('admin.layout.master')
@section('title', 'Full Hero Settings')

@section('css')
<style>
    
    .preview-box { width: 100%; height: 120px; object-fit: cover; border-radius: 10px; border: 1px solid #ddd; margin-bottom: 5px; background: #eee; }
    .preview-icon { width: 50px; height: 50px; object-fit: contain; border: 1px solid #ddd; padding: 5px; border-radius: 5px; background: #fff; }
    .section-title { color: #00a651; border-left: 4px solid #00a651; padding-left: 10px; font-weight: 700; margin-bottom: 20px; }
    .card { border-radius: 15px; border: none; box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075); }
</style>
@endsection

@section('body')
<div class="container-fluid py-4">
    <div class="custom-width">
        <form action="{{ route('hero.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="card mb-4">
                <div class="card-body p-4">
                    <h5 class="section-title">Main Content & CTA</h5>
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="fw-bold small">Main Title</label>
                            <input type="text" name="main_title" value="{{ $hero->main_title ?? '' }}" class="form-control">
                        </div>
                        <div class="col-md-12">
                            <label class="fw-bold small">Subtitle</label>
                            <textarea name="subtitle" class="form-control" rows="2">{{ $hero->subtitle ?? '' }}</textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="fw-bold small">Button One (Name)</label>
                            <input type="text" name="button_name_one" value="{{ $hero->button_name_one ?? '' }}" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="fw-bold small">Button Two (Name)</label>
                            <input type="text" name="button_name_two" value="{{ $hero->button_name_two ?? '' }}" class="form-control">
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-body p-4">
                    <h5 class="section-title">Team Members (Recommended: 500x500 px)</h5>
                    <div class="row g-4">
                        @foreach(['one', 'two', 'three'] as $m)
                        @php 
                            $imgField = "member_{$m}_image";
                            $nameField = "member_{$m}_name";
                            $desigField = "member_{$m}_designation";
                            $iconField = "member_{$m}_icon";
                        @endphp
                        <div class="col-md-4">
                            <div class="p-3 border rounded shadow-sm">
                                <h6 class="fw-bold text-center text-primary mb-3">Position {{ ucfirst($m) }}</h6>
                                
                                <img src="{{ isset($hero->$imgField) ? asset('public/'.$hero->$imgField) : asset('public/No_Image_Available.jpg') }}" id="prev-img-{{$m}}" class="preview-box">
                                <input type="file" name="{{ $imgField }}" class="form-control form-control-sm mb-2" onchange="preview(this, 'prev-img-{{$m}}')">
                                
                                <input type="text" name="{{ $nameField }}" value="{{ $hero->$nameField ?? '' }}" class="form-control form-control-sm mb-2" placeholder="Full Name">
                                <input type="text" name="{{ $desigField }}" value="{{ $hero->$desigField ?? '' }}" class="form-control form-control-sm mb-2" placeholder="Designation">
                                
                                <div class="d-flex align-items-center gap-2">
                                    <img src="{{ isset($hero->$iconField) ? asset('public/'.$hero->$iconField) : asset('public/No_Image_Available.jpg') }}" id="prev-icon-{{$m}}" class="preview-icon">
                                    <div class="flex-grow-1">
                                        <label class="small text-muted d-block">Icon (50x50)</label>
                                        <input type="file" name="{{ $iconField }}" class="form-control form-control-sm" onchange="preview(this, 'prev-icon-{{$m}}')">
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-body p-4">
                    <h5 class="section-title">Counter Statistics (Icon: 50x50 px)</h5>
                    <div class="row g-4">
                        @foreach(['success', 'client', 'positive'] as $key)
                        @php 
                            $countField = "{$key}_count";
                            $textField = "{$key}_text";
                            $iconField = "{$key}_icon";
                        @endphp
                        <div class="col-md-4">
                            <div class="p-3 border rounded bg-light shadow-sm text-center">
                                <h6 class="fw-bold mb-3 text-uppercase">{{ $key }} Stats</h6>
                                
                                <img src="{{ isset($hero->$iconField) ? asset('public/'.$hero->$iconField) : asset('public/No_Image_Available.jpg') }}" id="prev-stat-{{$key}}" class="preview-icon mb-2 mx-auto d-block">
                                <input type="file" name="{{ $iconField }}" class="form-control form-control-sm mb-3" onchange="preview(this, 'prev-stat-{{$key}}')">
                                
                                <input type="text" name="{{ $countField }}" value="{{ $hero->$countField ?? '' }}" class="form-control form-control-sm mb-2 text-center" placeholder="Count (e.g. 500+)">
                                <input type="text" name="{{ $textField }}" value="{{ $hero->$textField ?? '' }}" class="form-control form-control-sm text-center" placeholder="Label (e.g. Clients)">
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="text-center pb-5">
                <button type="submit" class="btn btn-success px-5 py-2 rounded-pill fw-bold shadow">
                    <i class="bi bi-check-circle me-2"></i>Update Hero Section
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function preview(input, id) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById(id).src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection