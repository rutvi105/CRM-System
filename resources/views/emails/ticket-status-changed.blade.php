<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket Status Updated</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background: #ffffff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .content {
            padding: 30px;
        }
        .ticket-info {
            background: #f8f9fa;
            border-left: 4px solid #667eea;
            padding: 15px;
            margin: 20px 0;
        }
        .status-badge {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
            font-weight: bold;
            margin: 5px;
        }
        .status-old {
            background: #ffc107;
            color: #000;
        }
        .status-new {
            background: #28a745;
            color: #fff;
        }
        .button {
            display: inline-block;
            padding: 12px 30px;
            background: #667eea;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
        }
        .footer {
            background: #f8f9fa;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🎫 Ticket Status Updated</h1>
        </div>
        
        <div class="content">
            <p>Hello <strong>{{ $ticket->user->name }}</strong>,</p>
            
            <p>Your ticket status has been updated:</p>
            
            <div class="ticket-info">
                <h3>Ticket #{{ $ticket->id }}</h3>
                <p><strong>Title:</strong> {{ $ticket->title }}</p>
                <p><strong>Priority:</strong> {{ ucfirst($ticket->priority) }}</p>
                
                <p>
                    <strong>Status changed from:</strong><br>
                    <span class="status-badge status-old">{{ ucfirst(str_replace('_', ' ', $oldStatus)) }}</span>
                    →
                    <span class="status-badge status-new">{{ ucfirst(str_replace('_', ' ', $newStatus)) }}</span>
                </p>
                
                @if($ticket->assignedAgent)
                <p><strong>Assigned Agent:</strong> {{ $ticket->assignedAgent->name }}</p>
                @endif
                
                @if($ticket->pending_reason && $newStatus == 'pending')
                <p><strong>Pending Reason:</strong> {{ $ticket->pending_reason }}</p>
                @endif
            </div>
            
            <p>You can view the full ticket details by clicking the button below:</p>
            
            <center>
                <a href="{{ config('app.url') }}/tickets/{{ $ticket->id }}" class="button">
                    View Ticket Details
                </a>
            </center>
            
            <p>Thank you for using our support system!</p>
        </div>
        
        <div class="footer">
            <p>This is an automated email from CRM System</p>
            <p>© {{ date('Y') }} CRM System. All rights reserved.</p>
        </div>
    </div>
</body>
</html>