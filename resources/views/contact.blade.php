<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - POJO FINSERV</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; color: #334155; background-color: #f8fafc; }
        .page-header {
            background: linear-gradient(135deg, rgba(7, 35, 72, 0.95), rgba(0, 82, 204, 0.9)), 
                        url('https://images.unsplash.com/photo-1423666639041-f56000c27a9a?auto=format&fit=crop&w=1920&q=80') center/cover no-repeat;
            color: #ffffff;
            padding: 80px 0;
            text-align: center;
            border-bottom: 3px solid #d4af37;
        }
        .contact-card {
            background: #ffffff;
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0, 82, 204, 0.08);
            border: 1px solid #e2e8f0;
        }
        .icon-circle {
            background: linear-gradient(135deg, #0052cc, #003b95);
            color: #fbbf24;
            border: 1px solid #d4af37;
        }
    </style>
</head>
<body>

    @include('header')

    <section class="page-header">
        <div class="container">
            <h1 class="fw-bold display-5 mb-2" style="color: #fbbf24;">Get In Touch With Us</h1>
            <p class="lead text-light mb-0">POJO FINSERV - Always Ready To Assist Your Financial Needs</p>
        </div>
    </section>

    <section class="py-5">
        <div class="container py-4">
            <div class="row g-5">
                <div class="col-lg-5">
                    <div class="contact-card h-100">
                        <h3 class="fw-bold text-dark mb-4">POJO FINSERV Contact Details</h3>
                        
                        <div class="d-flex gap-3 mb-4">
                            <div class="icon-circle p-3 rounded-circle fs-4 align-self-start">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold text-dark mb-1">Office Address</h6>
                                <p class="text-muted mb-0">D.No- 02/628, Mahil Nagar, Athappampalayam, Mel thindal, Erode - 638012</p>
                            </div>
                        </div>

                        <div class="d-flex gap-3 mb-4">
                            <div class="icon-circle p-3 rounded-circle fs-4 align-self-start">
                                <i class="fas fa-phone-alt"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold text-dark mb-1">Mobile / WhatsApp</h6>
                                <p class="mb-0"><a href="tel:9994777004" class="fw-bold fs-5 text-decoration-none" style="color: #0052cc;">9994777004</a></p>
                            </div>
                        </div>

                        <div class="d-flex gap-3 mb-4">
                            <div class="icon-circle p-3 rounded-circle fs-4 align-self-start">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold text-dark mb-1">Email Address</h6>
                                <p class="mb-0"><a href="mailto:Pojofinserv@outlook.com" class="fw-bold text-decoration-none" style="color: #0052cc;">Pojofinserv@outlook.com</a></p>
                            </div>
                        </div>

                        <div class="d-flex gap-3">
                            <div class="icon-circle p-3 rounded-circle fs-4 align-self-start">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold text-dark mb-1">Working Hours</h6>
                                <p class="text-muted mb-0">Monday - Saturday: 9:00 AM to 7:00 PM</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-7">
                    <div class="contact-card">
                        <h3 class="fw-bold text-dark mb-3">Send Us A Message</h3>
                        <p class="text-muted mb-4">Have questions regarding gold loans, doorstep visits, or investment returns? Fill out the form below.</p>
                        
                        @if(session('success'))
                            <div class="alert alert-success rounded-3 mb-4">{{ session('success') }}</div>
                        @endif

                        <form action="{{ route('contact.submit') }}" method="POST">
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Your Name</label>
                                    <input type="text" name="name" class="form-control form-control-lg fs-6" placeholder="Enter your full name" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Mobile Number</label>
                                    <input type="tel" name="phone" class="form-control form-control-lg fs-6" placeholder="Enter mobile number" required>
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-semibold">Email Address</label>
                                    <input type="email" name="email" class="form-control form-control-lg fs-6" placeholder="name@example.com">
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-semibold">Service Required</label>
                                    <select name="service_required" class="form-select form-select-lg fs-6">
                                        <option value="Doorstep Gold Loan">Doorstep Gold Loan</option>
                                        <option value="Gold Loan Takeover">Gold Loan Takeover</option>
                                        <option value="Sell Your Gold">Sell Your Gold</option>
                                        <option value="Investment Plan">Investment Plan</option>
                                        <option value="General Inquiry">General Inquiry</option>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-semibold">Message</label>
                                    <textarea name="message" class="form-control form-control-lg fs-6" rows="4" placeholder="How can we help you?"></textarea>
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn btn-lg px-5 rounded-pill fw-bold text-white shadow" style="background: linear-gradient(135deg, #0052cc, #003b95); border: 1px solid #d4af37;">
                                        Submit Message <i class="fas fa-paper-plane ms-2" style="color: #fbbf24;"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- UNIFIED FOOTER -->
    @include('footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>