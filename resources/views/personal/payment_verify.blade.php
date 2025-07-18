@extends('main')

@section('title', 'Payment Verification')

@section('content')
<div class="content-wrapper">
    <div class="content-header sty-one">
        <h1>Payment Verification</h1>
    </div>
    
    <!-- Payment Navigation -->
    <div class="d-flex flex-wrap justify-content-center gap-2 mb-3" style="margin-top: 15px;">
        <a href="{{ route('payments.index') }}" class="btn btn-primary">All Payments</a>
        <a href="{{ url('/i-admin/pending') }}" class="btn btn-primary">Pending Payments</a>
        <a href="{{ route('payment.verify') }}" class="btn btn-primary">Verify Payments</a>
        <a href="{{ route('payment.guide') }}" class="btn btn-info">Payment Guide</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="content">
        <div class="card">
            <div class="card-body">
                <h4>Payments Pending Verification</h4>
                @if($payments->isEmpty())
                    <p>No payments are pending verification.</p>
                @else
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>S.No</th>
                                    <th>Lead Name</th>
                                    <th>Agent Name</th>
                                    <th>Session Duration</th>
                                    <th>Session</th>
                                    <th>Payment Amount</th>
                                    <th>Payment Mode</th>
                                    <th>UTR No</th>
                                    <th>Bank</th>
                                    <th>Loan Amount</th>
                                    <th>Loan Details</th>
                                    <th>Payment Screenshot</th>
                                    <th>Actions</th>
                                    <th>Payment Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($payments as $payment)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $payment->lead ? $payment->lead->first_name : 'N/A' }}</td>
                                    <td>{{ $payment->lead && $payment->lead->agent ? $payment->lead->agent->emp_name : 'N/A' }}</td>
                                    <td>{{ $payment->session_duration }}</td>
                                    <td>{{ $payment->session }}</td>
                                    <td>{{ $payment->payment_amount }}</td>
                                    <td>{{ $payment->payment_mode }}</td>
                                    <td>{{ $payment->utr_no }}</td>
                                    <td>{{ $payment->bank }}</td>
                                    <td>{{ $payment->loan_amount }}</td>
                                    <td>{{ $payment->loan_details }}</td>
                                    <td>
                                        @if($payment->payment_screenshot)

                                        <a href="{{ asset($payment->payment_screenshot) }}" class="btn btn-info view-screenshot"
                                            style="display: inline-block; padding: 8px 1px; font-size: 12px; border-radius: 4px; text-align: center;">
                                            View Screenshot
                                        </a>

                                        @else
                                            <span>No Screenshot</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if(!$payment->payment_verify)
                                            <form action="{{ route('payment.confirm', $payment->id) }}" method="POST" style="display:inline-block;">
                                                @csrf
                                                <button type="submit" class="btn btn-danger" style="display: inline-block; padding: 0px 33px; font-size: 12px; border-radius: 4px; text-align: center;"
                                                    onclick="return confirm('Are you sure you want to confirm this payment?');">
                                                    Verify by Accountant
                                                </button>
                                            </form>
                                        @else
                                            <span class="badge badge-success"  style="display: inline-block; padding: 12px 33px; font-size: 15px; border-radius: 4px; text-align: center;">Verified</span>
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                        $bgClass = '';
                                        if ($payment->status === 'pending') {
                                            $bgClass = 'bg-danger text-white';
                                        } elseif ($payment->status === 'verified') {
                                            $bgClass = 'bg-success text-white';
                                        } elseif ($payment->status === 'rejected') {
                                            $bgClass = 'bg-danger text-white';
                                        }
                                        @endphp
                                        <p class="p-2 rounded {{ $bgClass }}">
                                            {{ ucfirst($payment->status) }}
                                        </p>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Modal for Viewing Screenshot -->
<div class="modal fade" id="screenshotModal" tabindex="-1" role="dialog" aria-labelledby="screenshotModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-body">
        <img id="screenshotImage" src="" alt="Payment Screenshot" class="img-fluid">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
    document.querySelectorAll('a.view-screenshot').forEach(el => {
        el.addEventListener('click', function (e) {
            e.preventDefault();
            const imageUrl = this.getAttribute('href');
            document.getElementById('screenshotImage').setAttribute('src', imageUrl);
            $('#screenshotModal').modal('show');
        });
    });
</script>
@endpush
