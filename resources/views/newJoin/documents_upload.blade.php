<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Documents</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
        }

        .content-wrapper {
            max-width: 1200px;
            margin: 30px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .content-header {
            text-align: center;
            margin-bottom: 20px;
        }

        .content-header h1 {
            font-size: 28px;
            color: #343a40;
        }

        .card {
            border: none;
            background: none;
        }

        .card-body {
            padding: 0;
        }

        h4 {
            margin-bottom: 20px;
            font-size: 22px;
            color: #495057;
        }

        .form-row {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
        }

        .form-group {
            flex: 1;
            min-width: 250px;
        }

        label {
            display: block;
            margin-bottom: 16px;
            font-weight: bold;
            color: #495057;
        }

        input[type="text"],
        input[type="email"],
        input[type="number"],
        select {
            width: 90%;
            padding: 8px;
            border: 1px solid #ced4da;
            border-radius: 4px;
        }

        input[type="file"] {
            display: block;
        }

        .btn {
            padding: 10px 20px;
            font-size: 16px;
            color: #fff;
            background-color: #007bff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .btn:hover {
            background-color: #0056b3;
        }

        .btn-link {
            background: none;
            color: #007bff;
            text-decoration: underline;
            border: none;
            padding: 0;
            font-size: 16px;
            cursor: pointer;
        }

        .btn-link:hover {
            color: #0056b3;
        }

        .alert {
            padding: 10px 20px;
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
            border-radius: 4px;
            margin-bottom: 20px;
        }

        .remove-document-btn {
            background-color: #dc3545;
        }

        .remove-document-btn:hover {
            background-color: #c82333;
        }

        #salary_amount_field {
            display: none;
        }

        .text-right {
            text-align: right;
        }

        @media (max-width: 768px) {
            .form-row {
                flex-direction: column;
            }
        }
    </style>
</head>

<body>
    <div class="content-wrapper">
        <div class="content-header sty-one">
            <h1>Upload Documents</h1>
        </div>

        <div class="content">
            <div class="card">
                <div class="card-body">

                    <!-- Success popup -->
                    @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                    @endif

                    <form action="{{ url('/admin/upload-documents') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <!-- Personal Information -->
                        <h4>Personal Information</h4>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="name">Full Name</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="phone">Phone</label>
                                <input type="text" class="form-control" id="phone" name="phone" required>
                            </div>
                            <div class="form-group">
                                <label for="address">Address</label>
                                <input type="text" class="form-control" id="address" name="address" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="salary_discussed">Salary Discussed</label>
                            <select class="form-control" id="salary_discussed" name="salary_discussed" onchange="toggleSalaryField()" required>
                                <option value="yes">Yes</option>
                                <option value="no">No</option>
                            </select>
                        </div>

                        <div class="form-group" id="salary_amount_field">
                            <label for="salary_amount">Mention Salary Amount</label>
                            <input type="number" class="form-control" id="salary_amount" name="salary_amount" placeholder="Enter Salary Amount">
                        </div>

                        <!-- Documents Upload Section -->
                        <h4>Documents Upload Section</h4>

                        <!-- Education Documents -->
                        <h4>Education Documents</h4>
                        <div id="company-documents">
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="company_pan">Marksheet</label>
                                    <input type="text" class="form-control" id="company_pan" name="company_pan_number" placeholder="Enter Marksheet Name" required>
                                </div>
                                <div class="form-group">
                                    <label for="company_pan_file">Upload File</label>
                                    <input type="file" name="company_pan_file" class="form-control-file" required>
                                </div>
                                <div class="form-group">
                                    <button type="button" class="btn btn-link" onclick="addDocument('company-documents')">+Add Document</button>
                                    <button type="submit" class="btn">Upload</button>
                                </div>
                            </div>
                        </div>

                        <!-- Personal Documents -->
                        <h4>Personal Documents</h4>
                        <div id="personal-documents">
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label for="personal_aadhar">Personal Aadhar</label>
                                    <input type="text" class="form-control" id="personal_aadhar" name="personal_aadhar_number" placeholder="Enter Aadhar Number" required>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="personal_aadhar_file">Upload File</label>
                                    <input type="file" name="personal_aadhar_file" class="form-control-file" required>
                                </div>
                                <div class="form-group">
                                    <button type="button" class="btn btn-link" onclick="addDocument('personal-documents')">+Add Document</button>
                                    <button type="submit" class="btn">Upload</button>
                                </div>
                            </div>
                            <div class="form-row mb-2">
                                <div class="form-group col-md-4">
                                    <label for="personal_pan">Personal PAN Card</label>
                                    <input type="text" class="form-control" id="personal_pan" name="personal_pan_number" placeholder="Enter PAN Number" required>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="personal_pan_file">Upload File</label>
                                    <input type="file" name="personal_pan_file" class="form-control-file" required>
                                </div>
                            </div>
                        </div>

                        

                        <div class="form-group row mt-4">
                            <div class="col-sm-12 text-right">
                                <button type="submit" class="btn">Submit All Documents</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>

          // Maximum document limits
        const maxEducationDocuments = 4;
        const maxPersonalDocuments = 3;

        // Counters for added documents
        let educationDocumentsCount = 0;
        let personalDocumentsCount = 0;

        function toggleSalaryField() {
            var salaryDiscussed = document.getElementById('salary_discussed').value;
            var salaryAmountField = document.getElementById('salary_amount_field');
            if (salaryDiscussed === 'yes') {
                salaryAmountField.style.display = 'block';
            } else {
                salaryAmountField.style.display = 'none';
            }
        }

        function addDocument(sectionId) {
            let section = document.getElementById(sectionId);

            // Determine the max limit based on the section
            let maxLimit = sectionId === 'company-documents' ? maxEducationDocuments : maxPersonalDocuments;

            // Update the corresponding document count
            let currentCount = sectionId === 'company-documents' ? educationDocumentsCount : personalDocumentsCount;

            if (currentCount < maxLimit) {
                // Create a new document input row
                let div = document.createElement('div');
                div.classList.add('form-row');
                div.innerHTML = `
                    <div class="form-group">
                        <input type="text" class="form-control" name="additional_document_name" placeholder="Enter Document Name" required>
                    </div>
                    <div class="form-group">
                        <input type="file" name="additional_document_file[]" class="form-control-file" required>
                    </div>
                    <div class="form-group">
                        <button type="button" class="btn remove-document-btn" onclick="removeDocument(this, '${sectionId}')">Remove</button>
                    </div>
                `;
                section.appendChild(div);
              
                // Increment the corresponding document count
                if (sectionId === 'company-documents') {
                    educationDocumentsCount++;
                } else {
                    personalDocumentsCount++;
                }
            } else {
                // Show an error message if the max limit is reached
                alert(`You can only add up to ${maxLimit} additional documents in this section.`);
            }
        }

        function removeDocument(button, sectionId) {
            // Remove the document row
            button.closest('.form-row').remove();

            // Decrement the corresponding document count
            if (sectionId === 'company-documents') {
                educationDocumentsCount--;
            } else {
                personalDocumentsCount--;
            }
        }
    </script>
</body>

</html>
