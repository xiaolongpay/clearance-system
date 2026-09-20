<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: -apple-system, 'Segoe UI', Roboto, Arial, sans-serif;
            background-color: #F1F3F7;
            margin: 0;
            padding: 0;
        }

        .wrapper {
            max-width: 480px;
            margin: 30px auto;
            background-color: #FFFFFF;
            border-radius: 10px;
            overflow: hidden;
            border: 1px solid #E5E7EB;
        }

        .header {
            background-color: #0F1B2D;
            padding: 22px 28px;
        }

        .header .brand {
            color: #ffffff;
            font-size: 1.05rem;
            font-weight: 700;
        }

        .header .brand span {
            color: #FFC72C;
        }

        .body {
            padding: 28px;
        }

        .body p {
            color: #111827;
            font-size: 0.95rem;
            line-height: 1.5;
            margin: 0 0 14px;
        }

        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-weight: 700;
            font-size: 0.85rem;
        }

        .status-badge.cleared {
            background-color: rgba(22, 163, 74, 0.1);
            color: #15803D;
        }

        .status-badge.rejected {
            background-color: rgba(220, 38, 38, 0.1);
            color: #B91C1C;
        }

        .remarks-box {
            background-color: #F9FAFB;
            border-left: 4px solid #DC2626;
            padding: 12px 16px;
            border-radius: 6px;
            margin: 16px 0;
            font-size: 0.88rem;
            color: #374151;
        }

        .cta {
            display: inline-block;
            margin-top: 10px;
            background-color: #FFC72C;
            color: #0F1B2D !important;
            font-weight: 700;
            padding: 12px 22px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 0.9rem;
        }

        .footer {
            padding: 18px 28px;
            border-top: 1px solid #E5E7EB;
            color: #9CA3AF;
            font-size: 0.75rem;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="header">
            <div class="brand">STI <span>Valencia</span> Clearance</div>
        </div>

        <div class="body">
            <p>Hi {{ $clearanceItem->student->first_name }},</p>

            @if ($clearanceItem->status === 'cleared')
                <p>
                    Good news — the <strong>{{ $clearanceItem->department->name }}</strong> office has marked your clearance as
                    <span class="status-badge cleared">Cleared</span>
                    for {{ $clearanceItem->schoolYear->year_label ?? 'the current school year' }}.
                </p>
            @else
                <p>
                    The <strong>{{ $clearanceItem->department->name }}</strong> office has marked your clearance as
                    <span class="status-badge rejected">Rejected</span>.
                </p>

                @if ($clearanceItem->remarks)
                    <div class="remarks-box">
                        <strong>Reason:</strong> {{ $clearanceItem->remarks }}
                    </div>
                @endif
            @endif

            <p>You can check your full clearance status anytime by logging into your dashboard.</p>

            <a href="{{ route('login') }}" class="cta">View My Clearance Status</a>
        </div>

        <div class="footer">
            This is an automated message from the STI Valencia Digital Clearance System. Please do not reply to this email.
        </div>
    </div>
</body>
</html>