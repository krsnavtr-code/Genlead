@extends('main')

@section('title', 'My Account')

@section('content')
<style>
    body {
        background-color: #f3f6fd;
    }

    .profile-container {
        margin-top: 40px;
    }

    .profile-card {
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
        display: flex;
        flex-wrap: wrap;
        background-color: #fff;
    }

    .profile-sidebar {
        background: linear-gradient(135deg, #4C70EF, #53C1F1);
        color: white;
        flex: 1 1 300px;
        padding: 40px 20px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }

    .profile-sidebar img {
        width: 140px;
        height: 140px;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid #fff;
        box-shadow: 0 0 20px rgba(255, 255, 255, 0.3);
    }

    .profile-sidebar h4 {
        margin-top: 20px;
        font-weight: 600;
    }

    .profile-sidebar small {
        font-size: 14px;
        opacity: 0.9;
    }

    .profile-content {
        flex: 2 1 600px;
        padding: 40px;
    }

    .section-title {
        font-size: 20px;
        font-weight: 600;
        color: #333;
        margin-bottom: 20px;
        border-left: 4px solid #53C1F1;
        padding-left: 10px;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
        gap: 15px;
    }

    .info-box {
        background: #f9fafe;
        padding: 15px 20px;
        border-radius: 12px;
        border: 1px solid #e0e5f1;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .info-box i {
        font-size: 18px;
        color: #4C70EF;
        min-width: 20px;
    }

    .info-box span.label {
        font-weight: 600;
        color: #555;
    }

    .info-box span.value {
        color: #222;
    }

    .btn-group-download {
        margin-top: 25px;
    }

    .referral-section {
        margin-top: 30px;
    }

    .referral-code {
        font-weight: 600;
        color: #53C1F1;
        font-size: 16px;
        word-break: break-word;
        background-color: #e9f5ff;
        padding: 10px 15px;
        border-radius: 8px;
        display: inline-block;
    }

    .copy-btn {
        font-weight: 600;
        color: black;
        font-size: 16px;
        word-break: break-word;
        background-color: #e9f5ff;
        padding: 10px 15px;
        border-radius: 8px;
    }

    #copy-message {
        display: none;
        font-weight: bold;
        margin-top: 8px;
        color: green;
    }
</style>

<div class="container profile-container mt-5">
    <div class="profile-card">
        <!-- Sidebar -->
        <div class="profile-sidebar text-center">
            @if ($employee->emp_pic)
                <img src="{{ asset($employee->emp_pic) }}" alt="Profile Picture">
            @else
                <i class="fas fa-user-circle" style="font-size: 140px;"></i>
            @endif
            <h4>{{ $employee->emp_name ?? 'N/A' }}</h4>
            <small>ID: {{ $employee->id }}</small>
        </div>

        <!-- Content -->
        <div class="profile-content">
            <div class="section-title">Account Information</div>
            <div class="info-grid">
                <div class="info-box">
                    <i class="fas fa-user"></i>
                    <div>
                        <span class="label">Name:</span><br>
                        <span class="value">{{ $employee->emp_name ?? 'N/A' }}</span>
                    </div>
                </div>
                <div class="info-box">
                    <i class="fas fa-user-tag"></i>
                    <div>
                        <span class="label">Username:</span><br>
                        <span class="value">{{ $employee->emp_username ?? 'N/A' }}</span>
                    </div>
                </div>
                <div class="info-box">
                    <i class="fas fa-user-shield"></i>
                    <div>
                        <span class="label">Role:</span><br>
                        <span class="value">
                            @if ($employee->emp_job_role == 1)
                                Superadmin
                            @elseif ($employee->emp_job_role == 2)
                                Agent
                            @elseif ($employee->emp_job_role == 4)
                                HR
                            @elseif ($employee->emp_job_role == 6)
                                Team Leader
                            @elseif ($employee->emp_job_role == 7)
                                Referral Team Leader
                            @else
                                Other
                            @endif
                        </span>
                    </div>
                </div>
            </div>

            <div class="section-title">Personal Information</div>
            <div class="info-grid">
                <div class="info-box">
                    <i class="fas fa-envelope"></i>
                    <div>
                        <span class="label">Email:</span><br>
                        <span class="value">{{ $employee->emp_email ?? 'N/A' }}</span>
                    </div>
                </div>
                <div class="info-box">
                    <i class="fas fa-phone-alt"></i>
                    <div>
                        <span class="label">Phone:</span><br>
                        <span class="value">{{ $employee->emp_phone ?? 'N/A' }}</span>
                    </div>
                </div>
                <div class="info-box">
                    <i class="fas fa-building"></i>
                    <div>
                        <span class="label">Branch:</span><br>
                        <span class="value">{{ $employee->emp_branch ?? 'N/A' }}</span>
                    </div>
                </div>
                <div class="info-box">
                    <i class="fas fa-map-marker-alt"></i>
                    <div>
                        <span class="label">Location:</span><br>
                        <span class="value">{{ $employee->emp_location ?? 'N/A' }}</span>
                    </div>
                </div>
                <div class="info-box">
                    <i class="fas fa-calendar-alt"></i>
                    <div>
                        <span class="label">Join Date:</span><br>
                        <span class="value">{{ $employee->emp_join_date ?? 'N/A' }}</span>
                    </div>
                </div>
            </div>

            <!-- Downloads -->
            @if ($employee->emp_job_role == 2)
                <div class="btn-group-download">
                    <a href="/admin/employee/download-offer-letter/{{ $employee->id }}" class="btn btn-outline-primary me-2" target="_blank">
                        <i class="fas fa-file-download"></i> Offer Letter
                    </a>
                    <a href="/admin/employee/download-id-card/{{ $employee->id }}" class="btn btn-outline-secondary">
                        <i class="fas fa-file-download"></i> ID Card
                    </a>
                </div>
            @endif

            <!-- Referral Program -->
            @if ($employee->referral_code)
                <div class="section-title referral-section"><i class="fas fa-user-friends"></i> Referral Program</div>
                <div class="referral-code">{{ $employee->referral_code }}</div>
                <button class="btn btn-sm btn-primary copy-btn" onclick="copyReferralCode()">Copy Code</button>
                <div id="copy-message">Copied!</div>
                <script>
                    function copyReferralCode() {
                        navigator.clipboard.writeText('{{ $employee->referral_code }}');
                        document.getElementById('copy-message').style.display = 'block';
                        setTimeout(() => {
                            document.getElementById('copy-message').style.display = 'none';
                        }, 2000);
                    }
                </script>
            @endif
        </div>
    </div>
</div>
@endsection
