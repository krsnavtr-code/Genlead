<p>Dear {{ $candidate->name }},</p>
<p>Congratulations! You have been verified as an Agent and are now a part of our company.</p>
<p>Here are your login credentials:</p>
<p><strong>Username:</strong> {{ $username }}</p>
<p><strong>Password:</strong> {{ $password }}</p>
<p>Please use the following link to log in to your Agent panel:</p>
<p><a href="{{ url('/') }}">Agent Login</a></p>
<p>Welcome aboard!</p>
