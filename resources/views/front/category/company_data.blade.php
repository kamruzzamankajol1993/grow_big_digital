@foreach($brands as $company)
<div class="col-lg-3 col-6">
    {{-- লিংক লজিক --}}
    @if($company->company_categories_count > 0)
        {{-- টেক্সট ডেকোরেশন নান (text-decoration-none) ক্লাস দেওয়া হলো যাতে নামের নিচে আন্ডারলাইন না আসে --}}
        <a href="{{ route('front.company.categories', $company->slug) }}" class="text-decoration-none">
    @else
        <a href="{{ route('front.company.products', $company->slug) }}" class="text-decoration-none">
    @endif

        <div class="cellexa_item_box">
            <div class="cellexa_item_box_img">
                <img src="{{ asset('public/'.$company->logo) }}" alt="{{ $company->name }}">
            </div>
            
            {{-- নতুন কোড: কোম্পানি নেম --}}
            <div class="text-center mt-2">
                <h6 style="font-size: 15px; font-weight: 600; color: #333;">{{ $company->name }}</h6>
            </div>
            {{-- নতুন কোড শেষ --}}
            
        </div>
    </a>
</div>
@endforeach