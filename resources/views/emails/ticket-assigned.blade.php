<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Ticket Assigned</title>
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
        .badge {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
            font-weight: bold;
            margin: 5px 0;
        }
        .badge-high {
            background: #dc3545;
            color: white;
        }
        .badge-medium {
            background: #ffc107;
            color: #000;
        }
        .badge-low {
            background: #28a745;
            color: white;
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
        .alert {
            background: #fff3cd;
            border: 1px solid #ffc107;
            padding: 15px;
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
            <h1>🎯 New Ticket Assigned to You</h1>
        </div>
        
        <div class="content">
            <p>Hello <strong>{{ $ticket->assignedAgent->name }}</strong>,</p>
            
            <p>A new ticket has been assigned to you. Please review the details below:</p>
            
            <div class="ticket-info">
                <h3>Ticket #{{ $ticket->id }}</h3>
                <p><strong>Title:</strong> {{ $ticket->title }}</p>
                <p><strong>Description:</strong> {{ Str::limit($ticket->description, 200) }}</p>
                
                <p>
                    <strong>Priority:</strong> 
                    <span class="badge badge-{{ $ticket->priority }}">
                        {{ ucfirst($ticket->priority) }} Priority
                    </span>
                </p>
                
                <p><strong>Customer:</strong> {{ $ticket->user->name }} ({{ $ticket->user->email }})</p>
                
                @if($ticket->user->package_type)
                <p><strong>SLA Package:</strong> {{ ucfirst($ticket->user->package_type) }}</p>
                @endif
                
                <p><strong>Created:</strong> {{ $ticket->created_at->format('M d, Y H:i') }}</p>
            </div>
            
            @if($ticket->sla_due_at)
            <div class="alert">
                <strong>⏰ SLA Deadline:</strong> {{ $ticket->sla_due_at->format('M d, Y H:i') }}
                <br>
                <small>Please ensure this ticket is resolved before the SLA deadline.</small>
            </div>
            @endif
            
            <p>Click the button below to view and manage this ticket:</p>
            
            <center>
                <a href="{{ config('app.url') }}/tickets/{{ $ticket->id }}" class="button">
                    View & Manage Ticket
                </a>
            </center>
            
            <p>Thank you for your prompt attention to this matter!</p>
        </div>
        
        <div class="footer">
            <p>This is an automated email from CRM System</p>
            <p>© {{ date('Y') }} CRM System. All rights reserved.</p>
        </div>
    </div>
</body>
</html>