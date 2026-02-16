@extends('front.master.master')
@section('title')
    About Us - Cellexa Scientific Solutions
@endsection

@section('body')

     <main>

        <section class="history section">

            <div class="container aos-init aos-animate" data-aos="fade-up" data-aos-delay="100">

                <div class="row g-5">
                    <div class="col-lg-7">
                        <div class="about-content aos-init aos-animate" data-aos="fade-up" data-aos-delay="200">
                            <h2>Our Story</h2>
                            <p>Founded under the umbrella of Aurexa Group Pty Ltd, Australia, Cellexa Scientific
                                Solutions was born from a vision to bridge global innovation with local pharmaceutical
                                needs. Today, we serve clients across continents, empowering research, development, and
                                quality control in the pharmaceutical sector.</p>

                            <div class="row">
                                <div class="col-lg-12 mb-3 text-center aos-init aos-animate" data-aos="fade-up"
                                    data-aos-delay="500">
                                    <div class="achievement-badge">
                                        <div class="badge-content">
                                            <h4 class="badge-title">Our Mission</h4>
                                            <p class="badge-text">To deliver high-quality pharmaceutical lab equipment
                                                that drives innovation,
                                                ensures compliance, and supports scientific excellence worldwide</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12 text-center aos-init aos-animate" data-aos="fade-up"
                                    data-aos-delay="500">
                                    <div class="achievement-badge">
                                        <div class="badge-content">
                                            <h4 class="badge-title">Our Vision</h4>
                                            <p class="badge-text">To be a globally trusted partner in pharmaceutical
                                                solutions—advancing science
                                                and improving lives through reliable technology and strategic
                                                collaboration.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-5">
                        <div class="about-image aos-init aos-animate" data-aos="zoom-in" data-aos-delay="300">
                            <img src="{{ asset('/') }}public/front/assets/img/home/page_about.webp" alt="Campus">
                        </div>
                    </div>
                </div>

                <div class="row mt-5">
                    <div class="col-lg-12">
                        <div class="core-values aos-init aos-animate" data-aos="fade-up" data-aos-delay="500">
                            <h3 class="mb-4">Core Values</h3>
                            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
                                <div class="col">
                                    <div class="value-card">
                                        <div class="value-icon">
                                            <i class="bi bi-book"></i>
                                        </div>
                                        <h4>Integrity</h4>
                                        <p>Honesty and transparency guide everything we do.</p>
                                    </div>
                                </div>

                                <div class="col">
                                    <div class="value-card">
                                        <div class="value-icon">
                                            <i class="bi bi-people"></i>
                                        </div>
                                        <h4>Excellence</h4>
                                        <p>We ensure quality and precision in every solution we provide.</p>
                                    </div>
                                </div>

                                <div class="col">
                                    <div class="value-card">
                                        <div class="value-icon">
                                            <i class="bi bi-lightbulb"></i>
                                        </div>
                                        <h4>Innovation</h4>
                                        <p>Bringing the latest global technologies to local industries.</p>
                                    </div>
                                </div>

                                <div class="col">
                                    <div class="value-card">
                                        <div class="value-icon">
                                            <i class="bi bi-globe"></i>
                                        </div>
                                        <h4>Reliability</h4>
                                        <p>On-time delivery and dependable service.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </section>

        <section id="leadership" class="leadership section">

            <div class="container aos-init aos-animate" data-aos="fade-up" data-aos-delay="100">

                <div class="intro-wrapper">
                    <div class="row align-items-center">
                        <div class="col-lg-6 order-lg-2 mb-5 mb-lg-0 aos-init aos-animate" data-aos="zoom-in"
                            data-aos-delay="200">
                            <div class="intro-image">
                                <img src="{{ asset('/') }}public/front/assets/img/home/55.jpg" alt="School Leadership" class="img-fluid rounded-lg">
                                <div class="experience-badge">
                                    <span class="years">10+</span>
                                    <span class="text">Years of Educational Excellence</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 order-lg-1 aos-init aos-animate" data-aos="fade-up" data-aos-delay="300">
                            <div class="intro-content">
                                <div class="highlights">
                                    <div class="highlight-item">
                                        <div class="content">
                                            <h2>Global Reach, Local Insight</h2>
                                            <p>With roots in Australia and a footprint
                                                across continents, we understand both international standards and
                                                regional needs </p>
                                        </div>
                                    </div>
                                    <div class="highlight-item">
                                        <div class="content">
                                            <h2>Regulatory Expertise</h2>
                                            <p>Our equipment solutions align with GMP, FDA, and
                                                ISO standards, helping clients meet compliance with confidence</p>
                                        </div>
                                    </div>
                                    <div class="highlight-item">
                                        <div class="content">
                                            <h2>Innovation-Driven Partnerships</h2>
                                            <p>We collaborate with manufacturers at the
                                                forefront of lab technology, ensuring access to the latest scientific
                                                advancements</p>
                                        </div>
                                    </div>
                                    <div class="highlight-item">
                                        <div class="content">
                                            <h2>AI & Tech-Enabled Support</h2>
                                            <p>As part of Aurexa Group Pty Ltd, Cellexa
                                                offers access to AI-driven tools and technology consulting through its
                                                sister concerns—enhancing operational efficiency and digital
                                                transformation</p>
                                        </div>
                                    </div>
                                    <div class="highlight-item">
                                        <div class="content">
                                            <h2>Client-Centric Approach</h2>
                                            <p>Every solution is customized, every interaction
                                                is transparent, and every delivery is backed by our commitment to
                                                excellence</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- <div class="leadership-section aos-init aos-animate" data-aos="fade-up">
                    <div class="section-header text-center">
                        <span class="subtitle">Our Team</span>
                        <h2 class="title">Meet Our Distinguished Leadership</h2>
                        <p class="description">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc aliquet
                            scelerisque pellentesque. Praesent vestibulum scelerisque scelerisque.</p>
                    </div>

                    <div class="row g-4">
                        <div class="col-xl-3 col-lg-4 col-md-6 aos-init aos-animate" data-aos="fade-up"
                            data-aos-delay="100">
                            <div class="team-card">
                                <div class="card-inner">
                                    <div class="card-front">
                                        <div class="member-image">
                                            <img src="{{ asset('/') }}public/front/assets/img/home/testimonials-4.jpg" alt="Principal"
                                                class="img-fluid">
                                        </div>
                                        <div class="member-info">
                                            <h4>Dr. Robert Williams</h4>
                                            <p>Principal</p>
                                        </div>
                                    </div>
                                    <div class="card-back">
                                        <h4>Dr. Robert Williams</h4>
                                        <p class="position">Principal</p>
                                        <p class="bio">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam
                                            auctor euismod lobortis.</p>
                                        <div class="social-links">
                                            <a href="#"><i class="bi bi-linkedin"></i></a>
                                            <a href="#"><i class="bi bi-twitter-x"></i></a>
                                            <a href="#"><i class="bi bi-envelope"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-lg-4 col-md-6 aos-init aos-animate" data-aos="fade-up"
                            data-aos-delay="200">
                            <div class="team-card">
                                <div class="card-inner">
                                    <div class="card-front">
                                        <div class="member-image">
                                            <img src="{{ asset('/') }}public/front/assets/img/home/testimonials-4.jpg" alt="Vice Principal"
                                                class="img-fluid">
                                        </div>
                                        <div class="member-info">
                                            <h4>Dr. Jennifer Parker</h4>
                                            <p>Vice Principal</p>
                                        </div>
                                    </div>
                                    <div class="card-back">
                                        <h4>Dr. Jennifer Parker</h4>
                                        <p class="position">Vice Principal</p>
                                        <p class="bio">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam
                                            auctor euismod lobortis.</p>
                                        <div class="social-links">
                                            <a href="#"><i class="bi bi-linkedin"></i></a>
                                            <a href="#"><i class="bi bi-twitter-x"></i></a>
                                            <a href="#"><i class="bi bi-envelope"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-lg-4 col-md-6 aos-init aos-animate" data-aos="fade-up"
                            data-aos-delay="300">
                            <div class="team-card">
                                <div class="card-inner">
                                    <div class="card-front">
                                        <div class="member-image">
                                            <img src="{{ asset('/') }}public/front/assets/img/home/testimonials-4.jpg" alt="Academic Dean"
                                                class="img-fluid">
                                        </div>
                                        <div class="member-info">
                                            <h4>Prof. Michael Stevens</h4>
                                            <p>Academic Dean</p>
                                        </div>
                                    </div>
                                    <div class="card-back">
                                        <h4>Prof. Michael Stevens</h4>
                                        <p class="position">Academic Dean</p>
                                        <p class="bio">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam
                                            auctor euismod lobortis.</p>
                                        <div class="social-links">
                                            <a href="#"><i class="bi bi-linkedin"></i></a>
                                            <a href="#"><i class="bi bi-twitter-x"></i></a>
                                            <a href="#"><i class="bi bi-envelope"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-lg-4 col-md-6 aos-init aos-animate" data-aos="fade-up"
                            data-aos-delay="400">
                            <div class="team-card">
                                <div class="card-inner">
                                    <div class="card-front">
                                        <div class="member-image">
                                            <img src="{{ asset('/') }}public/front/assets/img/home/testimonials-4.jpg" alt="Student Affairs"
                                                class="img-fluid">
                                        </div>
                                        <div class="member-info">
                                            <h4>Dr. Angela Martinez</h4>
                                            <p>Student Affairs</p>
                                        </div>
                                    </div>
                                    <div class="card-back">
                                        <h4>Dr. Angela Martinez</h4>
                                        <p class="position">Student Affairs</p>
                                        <p class="bio">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam
                                            auctor euismod lobortis.</p>
                                        <div class="social-links">
                                            <a href="#"><i class="bi bi-linkedin"></i></a>
                                            <a href="#"><i class="bi bi-twitter-x"></i></a>
                                            <a href="#"><i class="bi bi-envelope"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> -->

            </div>

        </section>

    </main>
@endsection
@section('scripts')
@endsection
