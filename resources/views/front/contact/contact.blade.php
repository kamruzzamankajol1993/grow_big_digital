@extends('front.master.master')
@section('title')
    Contact Us - {{ $front_ins_name ?? 'Cellexa Scientific Solutions' }}
@endsection

@section('css')
<style>
    /* Optional: Style for error messages */
    .invalid-feedback {
        display: block;
        color: #dc3545;
        font-size: 0.875em;
    }
</style>
@endsection

@section('body')
 <main>
        <div class="page-title mt-4">
            <div class="container">
                <div class="breadcrumbs">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('front.index') }}"><i class="bi bi-house"></i> Home</a></li>
                            <li class="breadcrumb-item active current">Contact us</li>
                        </ol>
                    </nav>
                </div>
                <div class="title-wrapper">
                    <h1>Contact Us</h1>
                </div>
            </div>
        </div>
        <section id="contact" class="contact section">

            <div class="container aos-init aos-animate" data-aos="fade-up" data-aos-delay="100">
                <div class="contact-main-wrapper">
                    <div class="map-wrapper">
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3383.7979846925964!2d115.91628237626529!3d-31.993491374001007!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2a32bc72485dbb59%3A0x27a1affd128c3b10!2s111%2F34%20Welshpool%20Rd%2C%20Welshpool%20WA%206106%2C%20Australia!5e0!3m2!1sen!2sbd!4v1757328629073!5m2!1sen!2sbd"
                            width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>

                    <div class="contact-content">
                        <div class="contact-cards-container aos-init aos-animate" data-aos="fade-up"
                            data-aos-delay="300">
                            
                            {{-- Dynamic Location --}}
                            <div class="contact-card">
                                <div class="icon-box">
                                    <i class="bi bi-geo-alt"></i>
                                </div>
                                <div class="contact-text">
                                    <h4>Location</h4>
                                    <p>{{ $front_ins_add ?? 'Address not available' }}</p>
                                </div>
                            </div>

                            {{-- Dynamic Email --}}
                            <div class="contact-card">
                                <div class="icon-box">
                                    <i class="bi bi-envelope"></i>
                                </div>
                                <div class="contact-text">
                                    <h4>Email</h4>
                                    <p>{{ $front_ins_email ?? 'Email not available' }}</p>
                                </div>
                            </div>

                            {{-- Dynamic Phone --}}
                            <div class="contact-card">
                                <div class="icon-box">
                                    <i class="bi bi-telephone"></i>
                                </div>
                                <div class="contact-text">
                                    <h4>Call</h4>
                                    <p>{{ $front_ins_phone ?? 'Phone not available' }}</p>
                                    @if(!empty($front_ins_phone_one))
                                        <p>{{ $front_ins_phone_one }}</p>
                                    @endif
                                </div>
                            </div>

                            {{-- Dynamic Opening Hours --}}
                            <div class="contact-card">
                                <div class="icon-box">
                                    <i class="bi bi-clock"></i>
                                </div>
                                <div class="contact-text">
                                    <h4>Open Hours</h4>
                                    <p>{{ $front_ins_opening_hour ?? '9AM - 6PM' }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="contact-form-container aos-init aos-animate" data-aos="fade-up"
                            data-aos-delay="400">
                            <h3>Get in Touch</h3>

                            <form id="contactFormPost" class="php-email-form">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6 form-group">
                                        <input type="text" name="name" class="form-control" id="name"
                                            placeholder="Your Name" required>
                                        <span class="text-danger error-text name_error"></span>
                                    </div>
                                    <div class="col-md-6 form-group mt-3 mt-md-0">
                                        <input type="email" class="form-control" name="email" id="email"
                                            placeholder="Your Email" required>
                                        <span class="text-danger error-text email_error"></span>
                                    </div>
                                </div>
                                <div class="form-group mt-3">
                                    <input type="text" class="form-control" name="subject" id="subject"
                                        placeholder="Subject" required>
                                    <span class="text-danger error-text subject_error"></span>
                                </div>
                                <div class="form-group mt-3">
                                    <textarea class="form-control" name="message" rows="5" placeholder="Message"
                                        required></textarea>
                                    <span class="text-danger error-text message_error"></span>
                                </div>

                               

                                <div class="form-submit">
                                    <button type="submit" id="btnSubmit">Send Message</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // CSRF Token Setup
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('#contactFormPost').submit(function(e) {
            e.preventDefault();
            
            let formData = new FormData(this);
            let btn = $('#btnSubmit');
            let loading = $('.loading');
            let sentMsg = $('.sent-message');
            let errorMsg = $('.error-message');

            // Reset Errors & UI
            $('.error-text').text('');
            sentMsg.hide();
            errorMsg.hide();
            loading.show();
            btn.prop('disabled', true).text('Sending...');

            $.ajax({
                url: "{{ route('front.contactUsPost') }}", // Ensure this route exists in web.php
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    loading.hide();
                    btn.prop('disabled', false).text('Send Message');

                    if (response.status === 'success') {
                        // Success SweetAlert
                        Swal.fire({
                            icon: 'success',
                            title: 'Message Sent!',
                            text: response.message,
                            timer: 3000,
                            showConfirmButton: false
                        });
                        
                        // Show text message in form
                        sentMsg.text(response.message).fadeIn();
                        
                        // Reset form
                        $('#contactFormPost')[0].reset();
                    } 
                    else if (response.status === 'error' && response.errors) {
                        // Validation Errors
                        $.each(response.errors, function(prefix, val) {
                            $('.' + prefix + '_error').text(val[0]);
                        });
                        
                        Swal.fire({
                            icon: 'error',
                            title: 'Validation Error',
                            text: 'Please check the highlighted fields.'
                        });
                    } 
                    else {
                        // General Error
                        errorMsg.text(response.message).fadeIn();
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: response.message
                        });
                    }
                },
                error: function(xhr) {
                    loading.hide();
                    btn.prop('disabled', false).text('Send Message');
                    errorMsg.text('Something went wrong. Please try again.').fadeIn();
                    
                    Swal.fire({
                        icon: 'error',
                        title: 'Server Error',
                        text: 'Failed to connect to the server.'
                    });
                }
            });
        });
    });
</script>
@endsection