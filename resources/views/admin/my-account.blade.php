@extends('main')

@section('title', 'My Account')

@section('content')
    <style>
        /* General container styling */
        .container {
            max-width: 800px; /* Limit the width of the content */
            margin-top: -33px;
        }

        /* Account container styling */
        .account-container {
            margin-top: 30px;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        /* Section Title Styling */
        h4 {
            font-size: 24px;
            margin-bottom: 20px;
            color: #333;
        }

        /* Information styling */
        p {
            font-size: 16px;
            margin-bottom: 12px;
            color: #555;
        }

        p strong {
            color: #333;
        }

        /* Divider between sections */
        .section-divider {
            border-bottom: 2px solid #e0e0e0;
            margin: 20px 0;
        }

        /* Make the layout responsive */
        @media (max-width: 768px) {
            .container {
                padding: 0 15px;
            }

            p {
                font-size: 14px; /* Adjust font size on smaller screens */
            }
        }
    </style>

    <div class="container">
        <div class="account-container">
            <h4>Account Information</h4>

            <!-- Display Employee Photo -->
            <p><strong>Profile Photo:</strong></p>
            @if ($employee->emp_pic)
                <img src="{{ asset($employee->emp_pic) }}" alt="Employee Photo" style="width: 150px; height: 150px; border-radius: 50%;">
            @else
                <p>No photo uploaded.</p>
            @endif
            
            <p><strong>Employee Name:</strong> {{ $employee->emp_name }}</p>
            <p><strong>Username:</strong> {{ $employee->emp_username }}</p>
            <p><strong>Role:</strong> 
                @if ($employee->emp_job_role == 1)
                    Superadmin
                @elseif ($employee->emp_job_role == 2)
                    Agent
                @elseif ($employee->emp_job_role == 4)
                    HR
                @else
                    Other
                @endif
            </p>

            <div class="section-divider"></div>

            <h4>Personal Information</h4>

            <p><strong>Employee Email:</strong> {{ $employee->emp_email }}</p>
            <p><strong>Employee Phone:</strong> {{ $employee->emp_phone }}</p>
            <p><strong>Employee Branch:</strong> {{ $employee->emp_branch }}</p>
            <p><strong>Employee Location:</strong> {{ $employee->emp_location }}</p>
            <p><strong>Employee Join Date:</strong> {{ $employee->emp_join_date }}</p>

            @if ($employee->emp_job_role == 2)
            <div class="download-button">
                <a href="/admin/employee/download-offer-letter/{{ $employee->id }}" class="btn btn-success">
                    Download Offer Letter
                </a>
            </div>
          @endif

            @if ($employee->referral_code)
                <div class="section-divider"></div> <!-- Divider line -->

                <h4>Referral</h4>
                <p><strong>Referral Link:</strong> 
                    <a href="https://collegevihar.com/agent/join?refid={{ $employee->referral_code }}" target="_blank">
                        https://collegevihar.com/agent/join?ref_code={{ $employee->referral_code }}
                    </a>
                </p>
            @endif
        </div>
    </div>
@endsection
