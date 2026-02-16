

 {{-- ================= CART OFFCANVAS ================= --}}
 <div class="offcanvas offcanvas-end" tabindex="-1" id="cellexaCartCanvas" aria-labelledby="cellexaCartCanvasLabel">
     <div class="offcanvas-header cellexa_company_category_offcanvas_header">
         <h5 class="offcanvas-title" id="cellexaCartCanvasLabel">Shopping Cart</h5>
         <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
     </div>

     <div class="offcanvas-body cellexa_company_category_offcanvas_body_flex" id="cart_dynamic_body">
         <div class="d-flex justify-content-center align-items-center h-100 text-muted">
             <div class="spinner-border spinner-border-sm me-2" role="status"></div>
             <span>Loading cart...</span>
         </div>
     </div>
 </div>

 {{-- ================= PROFILE / AUTH OFFCANVAS ================= --}}
 <div class="offcanvas offcanvas-end" tabindex="-1" id="cellexaProfileCanvas"
     aria-labelledby="cellexaProfileCanvasLabel">
     <div class="offcanvas-header cellexa_company_category_offcanvas_header">
         <h5 class="offcanvas-title" id="cellexaProfileCanvasLabel">Account</h5>
         <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
     </div>
     <div class="offcanvas-body">

         @auth
             {{-- LOGGED IN VIEW --}}
             <div class="text-center mt-5">
                 <div class="mb-3">
                     <i class="bi bi-person-check-fill display-1 text-success"></i>
                 </div>
                 <h4>Hello, {{ Auth::user()->name }}!</h4>
                 <p class="text-muted">Welcome back to your account.</p>
                 
                 <div class="d-grid gap-2 mt-4">
                     <a href="{{ route('front.userDashboard') }}" class="btn btn-primary">
                         <i class="bi bi-speedometer2 me-2"></i> Dashboard
                     </a>
                     <form action="{{ route('logout') }}" method="POST">
                         @csrf
                         <button type="submit" class="btn btn-outline-danger w-100">
                             <i class="bi bi-box-arrow-right me-2"></i> Logout
                         </button>
                     </form>
                 </div>
             </div>
         @else
             {{-- GUEST VIEW --}}

             {{-- 1. Tab Navigation (Login/Register) --}}
             <div class="cellexa_company_category_auth_toggle" id="auth_tabs_container">
                 <div class="cellexa_company_category_auth_tab active" onclick="switchCellexaAuth('login')">Login</div>
                 <div class="cellexa_company_category_auth_tab" onclick="switchCellexaAuth('register')">Register</div>
             </div>

             {{-- 2. Login Form --}}
             <div id="cellexaLoginForm" class="cellexa_company_category_auth_form show">
                 <form id="login_form_submit">
                     @csrf
                     <div class="mb-3">
                         <label class="form-label">Email address</label>
                         <input type="email" name="email" class="form-control" placeholder="name@example.com" required>
                     </div>
                     <div class="mb-3">
                         <label class="form-label">Password</label>
                         <input type="password" name="password" class="form-control" placeholder="********" required>
                     </div>
                     <div class="mb-3 form-check">
                         <input type="checkbox" name="remember" class="form-check-input" id="rememberMe">
                         <label class="form-check-label" for="rememberMe">Remember me</label>
                     </div>
                     <button type="submit" class="btn btn-primary w-100">Sign In</button>
                     <div class="text-center mt-3">
                         {{-- Triggers Forgot Password View --}}
                         <a href="javascript:void(0)" onclick="switchCellexaAuth('forgot')" class="text-decoration-none small">Forgot password?</a>
                     </div>
                 </form>
             </div>

             {{-- 3. Register Form --}}
             <div id="cellexaRegisterForm" class="cellexa_company_category_auth_form">
                 <form id="register_form_submit">
                     @csrf
                     <div class="mb-3">
                         <label class="form-label">Full Name <span class="text-danger">*</span></label>
                         <input type="text" name="name" class="form-control" placeholder="John Doe" required>
                     </div>
                     <div class="mb-3">
                         <label class="form-label">Company Name</label>
                         <input type="text" name="company_name" class="form-control" placeholder="Your Company Ltd.">
                     </div>
                     <div class="mb-3">
                         <label class="form-label">Company Address <span class="text-danger">*</span></label>
                         <input type="text" name="address" class="form-control" placeholder="123 Street, City" required>
                     </div>
                     <div class="mb-3">
                         <label class="form-label">Email address <span class="text-danger">*</span></label>
                         <input type="email" name="email" class="form-control" placeholder="name@example.com" required>
                     </div>
                     <div class="mb-3">
                         <label class="form-label">Password <span class="text-danger">*</span></label>
                         <input type="password" name="password" class="form-control" placeholder="Create a password" required>
                     </div>
                     <div class="mb-3">
                         <label class="form-label">Confirm Password <span class="text-danger">*</span></label>
                         <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm password" required>
                     </div>
                     <div class="mb-3 form-check">
                         <input type="checkbox" class="form-check-input" id="terms" required>
                         <label class="form-check-label small" for="terms">I agree to the Terms & Conditions</label>
                     </div>
                     <button type="submit" class="btn btn-success w-100">Create Account</button>
                 </form>
             </div>

             {{-- 4. Forgot Password Form (Two Steps) --}}
             <div id="cellexaForgotForm" class="cellexa_company_category_auth_form">
                
                {{-- STEP 1: Email Input --}}
                <div id="forgot_step_1">
                    <div class="text-center mb-4">
                        <i class="bi bi-search display-4 text-primary"></i>
                        <h5 class="mt-2">Find Your Account</h5>
                        <p class="text-muted small">Enter your email to search for your account.</p>
                    </div>
                    <form id="forgot_check_email_form">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Email address <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control" placeholder="name@example.com" required>
                        </div>
                        
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-search me-2"></i> Search Account
                        </button>
                    </form>
                </div>

                {{-- STEP 2: Password Reset Input (Initially Hidden) --}}
                <div id="forgot_step_2" style="display: none;">
                    <div class="text-center mb-4">
                        <i class="bi bi-shield-lock display-4 text-success"></i>
                        <h5 class="mt-2">Set New Password</h5>
                        <p class="text-muted small">Account found! Enter your new password below.</p>
                    </div>
                    <form id="forgot_reset_password_form">
                        @csrf
                        {{-- Hidden field to store verified email --}}
                        <input type="hidden" name="email" id="verified_email">

                        <div class="mb-3">
                            <label class="form-label">New Password <span class="text-danger">*</span></label>
                            <input type="password" name="password" class="form-control" placeholder="New password" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Confirm Password <span class="text-danger">*</span></label>
                            <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm password" required>
                        </div>
                        
                        <button type="submit" class="btn btn-success w-100">
                            <i class="bi bi-check-circle me-2"></i> Update Password
                        </button>
                    </form>
                </div>

                {{-- Back Button --}}
                <div class="text-center mt-3">
                    <a href="javascript:void(0)" onclick="switchCellexaAuth('login')" class="text-decoration-none small">
                        <i class="bi bi-arrow-left"></i> Back to Login
                    </a>
                </div>
             </div>

         @endauth

     </div>
 </div>