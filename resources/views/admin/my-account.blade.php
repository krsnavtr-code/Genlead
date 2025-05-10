@extends('main')

@section('title', 'My Account')

@section('content')
<style>
    .account-card {
        background: #fff;
        padding: 30px;
        border-radius: 16px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.07);
        margin-top: 30px;
    }

    .account-section-title {
        font-size: 20px;
        font-weight: 600;
        margin-top: 30px;
        margin-bottom: 20px;
        border-left: 5px solid #007bff;
        padding-left: 10px;
        color: #007bff;
    }

    .profile-pic {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid #007bff;
    }

    .info-item {
        margin-bottom: 15px;
        display: flex;
        align-items: center;
    }

    .info-item i {
        width: 25px;
        color: #007bff;
    }

    .info-label {
        font-weight: 600;
        margin-right: 5px;
        color: #555;
    }

    .info-value {
        color: #222;
    }

    .download-button {
        margin-top: 25px;
    }

    .referral-link {
        word-break: break-all;
        background: #f8f9fa;
        padding: 10px;
        border-radius: 8px;
        display: inline-block;
        font-weight: 500;
        color: #007bff;
    }

    .referral-link:hover {
        text-decoration: underline;
    }
</style>

<div class="container">
    <div class="account-card">
        <div class="row">
            <div class="col-md-4 text-center mb-4">
                <p class="info-label">Profile Photo</p>
                @if ($employee->emp_pic)
                    <img src="{{ asset($employee->emp_pic) }}" alt="Employee Photo" class="profile-pic mb-2">
                @else
                    <p class="text-muted">No photo uploaded.</p>
                @endif
                <p class="text-secondary mt-2"><i class="fas fa-id-badge"></i> ID: {{ $employee->id }}</p>
            </div>

            <div class="col-md-8">
                <div class="account-section-title"><i class="fas fa-user-circle"></i> Account Information</div>

                <div class="row">
                    <div class="col-sm-6 info-item">
                        <i class="fas fa-user"></i>
                        <span class="info-label">Name:</span>
                        <span class="info-value">{{ $employee->emp_name ?? 'N/A' }}</span>
                    </div>
                    <div class="col-sm-6 info-item">
                        <i class="fas fa-user-tag"></i>
                        <span class="info-label">Username:</span>
                        <span class="info-value">{{ $employee->emp_username ?? 'N/A' }}</span>
                    </div>
                    <div class="col-sm-6 info-item">
                        <i class="fas fa-user-shield"></i>
                        <span class="info-label">Role:</span>
                        <span class="info-value">
                            @if ($employee->emp_job_role == 1)
                                Superadmin
                            @elseif ($employee->emp_job_role == 2)
                                Agent
                            @elseif ($employee->emp_job_role == 4)
                                HR
                            @else
                                Other
                            @endif
                        </span>
                    </div>
                </div>

                <div class="account-section-title"><i class="fas fa-address-card"></i> Personal Information</div>
                <div class="row">
                    <div class="col-sm-6 info-item">
                        <i class="fas fa-envelope"></i>
                        <span class="info-label">Email:</span>
                        <span class="info-value">{{ $employee->emp_email ?? 'N/A' }}</span>
                    </div>
                    <div class="col-sm-6 info-item">
                        <i class="fas fa-phone-alt"></i>
                        <span class="info-label">Phone:</span>
                        <span class="info-value">{{ $employee->emp_phone ?? 'N/A' }}</span>
                    </div>
                    <div class="col-sm-6 info-item">
                        <i class="fas fa-building"></i>
                        <span class="info-label">Branch:</span>
                        <span class="info-value">{{ $employee->emp_branch ?? 'N/A' }}</span>
                    </div>
                    <div class="col-sm-6 info-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <span class="info-label">Location:</span>
                        <span class="info-value">{{ $employee->emp_location ?? 'N/A' }}</span>
                    </div>
                    <div class="col-sm-6 info-item">
                        <i class="fas fa-calendar-alt"></i>
                        <span class="info-label">Join Date:</span>
                        <span class="info-value">{{ $employee->emp_join_date ?? 'N/A' }}</span>
                    </div>
                </div>

                @if ($employee->emp_job_role == 2)
                    <div class="download-button">
                        <a href="/admin/employee/download-offer-letter/{{ $employee->id }}" class="btn btn-success">
                            <i class="fas fa-file-download"></i> Download Offer Letter
                        </a>
                    </div>
                @endif

                @if ($employee->referral_code)
                    <div class="account-section-title"><i class="fas fa-user-friends"></i> Referral Program</div>
                    <p><span class="info-label">Your Referral Link:</span></p>
                    <a href="https://collegevihar.com/agent/join?refid={{ $employee->referral_code }}"
                       class="referral-link" target="_blank">
                       https://collegevihar.com/agent/join?refid={{ $employee->referral_code }}
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection