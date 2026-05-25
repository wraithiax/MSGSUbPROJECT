

!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maintenance Mode</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #ffe4f1 0%, #fff0f7 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .maintenance-container {
            text-align: center;
            background: white;
            padding: 60px 40px;
            border-radius: 10px;
            box-shadow: 0 10px 40px rgba(236, 72, 153, 0.15);
            max-width: 600px;
            width: 90%;
        }

        .maintenance-icon {
            font-size: 80px;
            margin-bottom: 20px;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.1);
            }
        }

        h1 {
            color: #ec4899;
            font-size: 2.5em;
            margin-bottom: 15px;
        }

        .maintenance-title {
            color: #ec4899;
            font-size: 1.2em;
            font-weight: 600;
            margin-bottom: 20px;
        }

        .maintenance-description {
            color: #666;
            font-size: 1em;
            line-height: 1.6;
            margin-bottom: 30px;
        }

        .maintenance-info {
            background: #ffe4f1;
            border-left: 4px solid #ec4899;
            padding: 20px;
            margin: 20px 0;
            border-radius: 5px;
            text-align: left;
        }

        .maintenance-info p {
            color: #555;
            margin: 10px 0;
            font-size: 0.95em;
        }

        .maintenance-info strong {
            color: #831843;
        }

        .maintenance-type {
            display: inline-block;
            background: #ec4899;
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 0.85em;
            margin-top: 10px;
            text-transform: capitalize;
        }

        .contact-info {
            margin-top: 30px;
            padding-top: 30px;
            border-top: 1px solid #eee;
            color: #999;
            font-size: 0.9em;
        }

        .loading {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid #f3f3f3;
            border-top: 3px solid #ec4899;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin-right: 10px;
            vertical-align: middle;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .status-badge {
            display: inline-block;
            background: #ec4899;
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.85em;
            margin-bottom: 15px;
            text-transform: uppercase;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="maintenance-container">
        <div class="maintenance-icon">🔧</div>
        
        <div class="status-badge">
            <span class="loading"></span>
            Under Maintenance
        </div>

        <h1>We'll Be Back Soon!</h1>

        <p class="maintenance-title">{{ $maintenance->title }}</p>

        @if($maintenance->description)
            <p class="maintenance-description">{{ $maintenance->description }}</p>
        @else
            <p class="maintenance-description">
                We're currently performing scheduled maintenance to improve your experience. 
                Thank you for your patience!
            </p>
        @endif

        <div class="maintenance-info">
            @if($maintenance->started_at)
                <p>
                    <strong>Start Time:</strong> 
                    {{ $maintenance->started_at->format('F j, Y \a\t g:i A') }}
                </p>
            @endif

            @if($maintenance->estimated_end_at)
                <p>
                    <strong>Estimated End Time:</strong> 
                    {{ $maintenance->estimated_end_at->format('F j, Y \a\t g:i A') }}
                </p>
            @endif

            @if($maintenance->maintenance_type)
                <p>
                    <strong>Type:</strong>
                    <span class="maintenance-type">{{ $maintenance->maintenance_type }}</span>
                </p>
            @endif
        </div>

        <div class="contact-info">
            <p>If you have any questions, please contact our support team.</p>
            <p>Thank you for your understanding!</p>
        </div>
    </div>
</body>
</html>
