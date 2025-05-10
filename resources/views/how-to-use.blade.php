@extends('main')

@section('title', 'How to Use')

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">How to Use Genlead</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/admin/home">Home</a></li>
                        <li class="breadcrumb-item active">How to Use</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Getting Started with Genlead</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="accordion" id="howToUseAccordion">
                                <!-- Lead Management Section -->
                                <div class="card">
                                    <div class="card-header" id="headingOne">
                                        <h2 class="mb-0">
                                            <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                                <i class="fas fa-user-plus mr-2"></i> Managing Leads
                                            </button>
                                        </h2>
                                    </div>
                                    <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#howToUseAccordion">
                                        <div class="card-body">
                                            <h5>Adding a New Lead</h5>
                                            <ol>
                                                <li>Navigate to <strong>Leads > Add Lead</strong> from the sidebar menu</li>
                                                <li>Fill in all required information (fields marked with *)</li>
                                                <li>Click <strong>Submit</strong> to add the lead to the system</li>
                                            </ol>
                                            
                                            <h5>Viewing and Managing Leads</h5>
                                            <ol>
                                                <li>Go to <strong>Leads > Show Leads</strong> to see all your leads</li>
                                                <li>Use the search box to find specific leads</li>
                                                <li>Click on <strong>View</strong> to see detailed information</li>
                                                <li>Click on <strong>Edit</strong> to modify lead information</li>
                                            </ol>
                                            
                                            <h5>Lead Status Management</h5>
                                            <p>Leads can have the following statuses:</p>
                                            <ul>
                                                <li><span class="badge badge-primary">New</span> - Recently added leads</li>
                                                <li><span class="badge badge-warning">In Progress</span> - Leads being worked on</li>
                                                <li><span class="badge badge-success">Converted</span> - Successfully converted leads</li>
                                                <li><span class="badge badge-danger">Lost</span> - Leads that didn't convert</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Follow-ups Section -->
                                <div class="card">
                                    <div class="card-header" id="headingTwo">
                                        <h2 class="mb-0">
                                            <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                                <i class="fas fa-calendar-check mr-2"></i> Managing Follow-ups
                                            </button>
                                        </h2>
                                    </div>
                                    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#howToUseAccordion">
                                        <div class="card-body">
                                            <h5>Creating Follow-ups</h5>
                                            <ol>
                                                <li>From the lead detail page, scroll to the follow-up section</li>
                                                <li>Enter follow-up details and select a date</li>
                                                <li>Click <strong>Add Follow-up</strong> to schedule it</li>
                                            </ol>
                                            
                                            <h5>Today's Follow-ups</h5>
                                            <ol>
                                                <li>Navigate to <strong>Follow-ups > Today</strong> from the sidebar</li>
                                                <li>View all follow-ups scheduled for today</li>
                                                <li>Mark them as complete after contacting the lead</li>
                                            </ol>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Payments Section -->
                                <div class="card">
                                    <div class="card-header" id="headingThree">
                                        <h2 class="mb-0">
                                            <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                                <i class="fas fa-money-bill-wave mr-2"></i> Processing Payments
                                            </button>
                                        </h2>
                                    </div>
                                    <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#howToUseAccordion">
                                        <div class="card-body">
                                            <h5>Recording a Payment</h5>
                                            <ol>
                                                <li>From the lead detail page, click on <strong>Process Payment</strong></li>
                                                <li>Enter the payment amount and select payment method</li>
                                                <li>Add any relevant notes about the payment</li>
                                                <li>Click <strong>Submit Payment</strong> to record it</li>
                                            </ol>
                                            
                                            <h5>Viewing Pending Payments</h5>
                                            <ol>
                                                <li>Go to <strong>Payments > Pending</strong> in the sidebar</li>
                                                <li>View all leads with pending payments</li>
                                                <li>Follow up with these leads to complete their payments</li>
                                            </ol>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Reports Section -->
                                <div class="card">
                                    <div class="card-header" id="headingFour">
                                        <h2 class="mb-0">
                                            <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                                <i class="fas fa-chart-bar mr-2"></i> Reports and Analytics
                                            </button>
                                        </h2>
                                    </div>
                                    <div id="collapseFour" class="collapse" aria-labelledby="headingFour" data-parent="#howToUseAccordion">
                                        <div class="card-body">
                                            <h5>Dashboard Overview</h5>
                                            <p>The dashboard provides a quick overview of:</p>
                                            <ul>
                                                <li>Total leads in the system</li>
                                                <li>Conversion rates</li>
                                                <li>Recent activities</li>
                                                <li>Upcoming follow-ups</li>
                                            </ul>
                                            
                                            <h5>Exporting Data</h5>
                                            <ol>
                                                <li>Navigate to <strong>Leads > Export</strong></li>
                                                <li>Select the date range for the export</li>
                                                <li>Choose the export format (CSV, Excel)</li>
                                                <li>Click <strong>Export</strong> to download the file</li>
                                            </ol>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <p class="text-muted">For additional help or questions, please contact your administrator.</p>
                </div>
            </div>
            
            <!-- Pagination -->
            <!-- <div class="d-flex justify-content-center mt-4">
                <nav aria-label="Page navigation">
                    <ul class="pagination">
                        <li class="page-item disabled">
                            <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Previous</a>
                        </li>
                        <li class="page-item active"><a class="page-link" href="#">1</a></li>
                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                        <li class="page-item">
                            <a class="page-link" href="#">Next</a>
                        </li>
                    </ul>
                </nav>
            </div> -->
        </div>
    </section>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Initialize the accordion
        $('#howToUseAccordion').on('shown.bs.collapse', function () {
            $('html, body').animate({
                scrollTop: $('.collapse.show').offset().top - 100
            }, 200);
        });
    });
</script>
@endsection
