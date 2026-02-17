@forelse($portfolios as $index => $item)
    @php
        $colSize = '';
        // আপনার স্ক্রিপ্ট লজিক: ১ম সারিতে ৮+৪, ২য় সারিতে ৪+৮
        // (index 0,1) -> 8, 4 | (index 2,3) -> 4, 8
        if ($index % 4 == 0) {
            $colSize = 'col-lg-8';
        } elseif ($index % 4 == 1) {
            $colSize = 'col-lg-4';
        } elseif ($index % 4 == 2) {
            $colSize = 'col-lg-4';
        } else {
            $colSize = 'col-lg-8';
        }
    @endphp

    <div class="{{ $colSize }} col-md-12">
        <div class="portfolio-box reveal-visible">
            <div class="portfolio-media">
                @if(!$item->image && $item->video_link)
    {{-- ভিডিওর জন্য একটি রেসপন্সিভ র‍্যাপার --}}
    <div class="iframe-container-wrapper" style="position: relative; width: 100%; height: 100%; min-height: 250px; overflow: hidden; border-radius: 8px;">
        {!! $item->video_link !!}
        
        {{-- প্লে বাটন লেয়ার --}}
        <div class="play-btn-circle" onclick="openPopup('video', `{{ $item->video_link }}`)" style="cursor: pointer; z-index: 10;">
            <i class="bi bi-play-fill"></i>
        </div>
    </div>
@else
    <img src="{{ asset('public/'.$item->image) }}" alt="{{ $item->title }}" onclick="openPopup('image', '{{ asset('public/'.$item->image) }}')">
@endif

                <div class="portfolio-overlay">
                    <div class="overlay-info">
                        {{-- ট্যাগ হিসেবে সাবক্যাটাগরির নাম দেখানো হয়েছে --}}
                        <span class="cat-tag">{{ $item->subcategory->name ?? 'CREATIVE WORK' }}</span>
                        <h4 class="work-title">{{ $item->title }}</h4>
                        <p class="work-desc">{{ $item->subtitle }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@empty
    <div class="col-12 text-center py-5">
        <p class="text-muted">No masterpieces found in this category.</p>
    </div>
@endforelse