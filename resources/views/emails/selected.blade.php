<p>Dear {{ $candidate->name }},</p>
<p>Congratulations! You are selected in the interview and are now you are part of our company.</p>
<p>Here are your login details:</p>
<p>Username: {{ $username }}</p>
<p>Password: {{ $password }}</p>
<p>Please click the link below to upload your documents. The link will expire in 48 hours:</p>
<p><a href="{{ url('/admin/new-joinee/upload-documents/' . $username) }}">Upload Documents</a></p>