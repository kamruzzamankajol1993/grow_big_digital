<div class="floating-audit-btn" data-bs-toggle="modal" data-bs-target="#quickAuditModal">
    <i class="bi bi-search me-2"></i> Free Quick Audit
</div>

<div class="modal fade" id="quickAuditModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content custom-audit-modal">
            <div class="modal-header border-0">
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-5 pt-0 text-center">
                
                <h3 class="fw-bold text-white mb-2">Get Free Quick Audit</h3>
                <p class="text-white-50 mb-4">Let our experts analyze your presence.</p>
                
               <form class="audit-form" id="quickAuditForm">
    @csrf
    <div class="mb-3">
        <input type="text" name="full_name" class="form-control" placeholder="Full Name" required>
    </div>
    
    <div class="mb-4">
        <select name="service" class="form-select" required>
            <option selected disabled value="">Select Service for Audit...</option>
            
            {{-- লুপ শুরু: মেইন সার্ভিসগুলো optgroup হিসেবে আসবে --}}
            @foreach($allServicesWithChildren as $parentService)
                @if($parentService->children->count() > 0)
                    <optgroup label="{{ $parentService->name }}">
                        @foreach($parentService->children as $child)
                            <option value="{{ $child->name }}">{{ $child->name }}</option>
                        @endforeach
                    </optgroup>
                @else
                    {{-- যদি কোনো সার্ভিসের সাব-সার্ভিস না থাকে, তবে সেটি সাধারণ অপশন হিসেবে দেখাবে --}}
                    <option value="{{ $parentService->name }}">{{ $parentService->name }}</option>
                @endif
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <input type="url" name="profile_or_social_url" class="form-control" placeholder="Social Profile or Website URL" required>
    </div>
    <div class="mb-3">
        <input type="email" name="email" class="form-control" placeholder="Email Address" required>
    </div>
   
    <button type="submit" id="submitBtn" class="btn btn-brand-solid w-100 py-3 shadow-glow">SEND REQUEST</button>
</form>
            </div>
        </div>
    </div>
</div>