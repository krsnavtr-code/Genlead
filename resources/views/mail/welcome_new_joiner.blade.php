

@component('mail::message')
# Welcome to Our Company

We are delighted to welcome you to our team. We want you to be part of our company, and we look forward to your contributions.

Please click the link below to complete your document upload process:

@component('mail::button', ['url' => $url])
Upload Documents
@endcomponent

This link will be active for 48 hours. If you encounter any issues, please contact your company administration.

Thanks,<br>
{{ config('app.name') }}
@endcomponent

