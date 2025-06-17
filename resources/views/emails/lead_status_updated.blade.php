<!DOCTYPE html>
<html>
<head>
    <title>Lead Status Updated</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .header {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }
        .content {
            padding: 20px;
        }
        .status-change {
            background-color: #f9f9f9;
            padding: 15px;
            border-left: 4px solid #4CAF50;
            margin: 15px 0;
        }
        .footer {
            margin-top: 20px;
            font-size: 12px;
            color: #777;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Lead Status Updated</h2>
        </div>
        
        <div class="content">
            <p>Hello {{ $agentName }},</p>
            
            <p>The status for lead <strong>{{ $leadName }}</strong> has been updated.</p>
            
            <!-- <p><a href="{{ $leadUrl }}" style="background-color: #4CAF50; color: white; padding: 10px 20px; text-decoration: none; border-radius: 4px; display: inline-block; margin: 10px 0;">View Lead Details</a></p> -->
            
            <div class="status-change">
                <p><strong>Status Changed:</strong> {{ $oldStatus }} → <span style="color: #4CAF50; font-weight: bold;">{{ $newStatus }}</span></p>
                @if(!empty($comments))
                    <p><strong>Comments:</strong> {{ $comments }}</p>
                @endif
                <p><strong>Next Follow-up:</strong> {{ $followUpTime }}</p>
            </div>
            
            <p>Or you can view the lead details by logging into your account.</p>
            
            <p>Thank you,<br>Genlead Team</p>
        </div>
        
        <div class="footer">
            <p>This is an automated notification. Please do not reply to this email.</p>
        </div>
    </div>
</body>
</html>
