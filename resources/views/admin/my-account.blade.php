@extends('main')

@section('title', 'My Account')

@section('content')
    <style>
        .account-card {
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
            margin-top: 30px;
            width: 100%;
        }

        .account-section-title {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 20px;
            border-bottom: 2px solid #eee;
            padding-bottom: 10px;
            color: #333;
        }

        .profile-pic {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #007bff;
        }

        .info-label {
            font-weight: 600;
            color: #444;
        }

        .info-value {
            color: #333;
        }

        .download-button {
            margin-top: 20px;
        }

        .referral-link {
            word-break: break-all;
        }
    </style>

    <div class="container">
        <div class="account-card">
            <div class="row">
                <div class="col-md-4 text-center mb-4">
                    <p class="info-label">Profile Photo:</p>
                    @if ($employee->emp_pic)
                        <img src="{{ asset($employee->emp_pic) }}" alt="Employee Photo" class="profile-pic">
                    @else
                        <p class="text-muted">No photo uploaded.</p>
                    @endif
                </div>

                <div class="col-md-8">
                    <div class="account-section-title">Account Information</div>

                    <div class="row">
                        <div class="col-sm-6 mb-3">
                            <p><span class="info-label">Name:</span> <span class="info-value">{{ $employee->emp_name ? $employee->emp_name : 'N/A' }}</span></p>
                        </div>
                        <div class="col-sm-6 mb-3">
                            <p><span class="info-label">Username:</span> <span class="info-value">{{ $employee->emp_username ? $employee->emp_username : 'N/A' }}</span></p>
                        </div>
                        <div class="col-sm-6 mb-3">
                            <p><span class="info-label">Role:</span> 
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
                            </p>
                        </div>
                    </div>

                    <div class="account-section-title">Personal Information</div>
                    <div class="row">
                        <div class="col-sm-6 mb-3">
                            <p><span class="info-label">Email:</span> <span class="info-value">{{ $employee->emp_email ? $employee->emp_email : 'N/A' }}</span></p>
                        </div>
                        <div class="col-sm-6 mb-3">
                            <p><span class="info-label">Phone:</span> <span class="info-value">{{ $employee->emp_phone ? $employee->emp_phone : 'N/A' }}</span></p>
                        </div>
                        <div class="col-sm-6 mb-3">
                            <p><span class="info-label">Branch:</span> <span class="info-value">{{ $employee->emp_branch ? $employee->emp_branch : 'N/A' }}</span></p>
                        </div>
                        <div class="col-sm-6 mb-3">
                            <p><span class="info-label">Location:</span> <span class="info-value">{{ $employee->emp_location ? $employee->emp_location : 'N/A' }}</span></p>
                        </div>
                        <div class="col-sm-6 mb-3">
                            <p><span class="info-label">Join Date:</span> <span class="info-value">{{ $employee->emp_join_date ? $employee->emp_join_date : 'N/A' }}</span></p>
                        </div>
                    </div>

                    @if ($employee->emp_job_role == 2)
                        <div class="download-button">
                            <a href="/admin/employee/download-offer-letter/{{ $employee->id }}" class="btn btn-success">
                                <i class="fas fa-download"></i> Download Offer Letter
                            </a>
                        </div>
                    @endif

                    @if ($employee->referral_code)
                        <div class="account-section-title">Referral</div>
                        <p><span class="info-label">Referral Link:</span></p>
                        <a href="https://collegevihar.com/agent/join?refid={{ $employee->referral_code }}"
                           class="referral-link d-block" target="_blank">
                           https://collegevihar.com/agent/join?ref_code={{ $employee->referral_code }}
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
