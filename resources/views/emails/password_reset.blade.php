<p>Hello Anand</p>

<p>The following user has requested a password reset:</p>
<ul>
    <li>Name: {{ $emp_name }}</li>
    <li>Email: {{ $emp_email }}</li>
</ul>

<p>To reset the password, click the link below:</p>
<a href="{{ url('/admin/password/reset/' . $token) }}">Reset Password</a>

<p>This link will expire in 10 minutes. If you do not act within this time, the request will become invalid.</p>

<p>Thank you,<br>GEN-LEAD CRM Team</p>

