<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Citizen Verification Status</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: #f8fafc;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 24px;
            color: #0f172a;
        }
        .card {
            background: #ffffff;
            border-radius: 24px;
            padding: 32px;
            max-width: 480px;
            width: 100%;
            box-shadow: 0 15px 40px rgba(15, 23, 42, 0.12);
            text-align: center;
        }
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            border-radius: 999px;
            padding: 10px 20px;
            text-transform: uppercase;
            font-weight: 600;
            letter-spacing: 0.08em;
            font-size: 13px;
            margin-bottom: 24px;
        }
        .badge.success {
            background: #ecfdf5;
            color: #047857;
            border: 1px solid #6ee7b7;
        }
        .badge.danger {
            background: #fef2f2;
            color: #b91c1c;
            border: 1px solid #fecaca;
        }
        .details {
            margin-top: 24px;
            text-align: left;
        }
        .details dt {
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #94a3b8;
            margin-bottom: 4px;
        }
        .details dd {
            font-size: 18px;
            font-weight: 600;
            margin: 0 0 16px 0;
            color: #0f172a;
        }
        .note {
            margin-top: 24px;
            font-size: 13px;
            color: #64748b;
            line-height: 1.5;
        }
        .footer {
            margin-top: 32px;
            font-size: 12px;
            color: #94a3b8;
        }
    </style>
</head>
<body>
<div class="card">
    @if($status === 'verified')
        <div class="badge success">
            <span>✓ Verified Citizen</span>
        </div>
        <h1 style="margin:0;font-size:28px;">This certificate is valid</h1>
        <p style="color:#475569;margin-top:8px;">The individual below has been verified in the Citizen Management System.</p>
    @else
        <div class="badge danger">
            <span>✕ Not Verified</span>
        </div>
        <h1 style="margin:0;font-size:28px;">Certificate invalid</h1>
        <p style="color:#475569;margin-top:8px;">No active verification found for this citizen.</p>
    @endif

    <dl class="details">
        <dt>Citizen Name</dt>
        <dd>{{ $user->full_name ?? $user->display_name }}</dd>

        <dt>National ID</dt>
        <dd>{{ $user->nid_number ?? 'Not provided' }}</dd>

        <dt>Verification Status</dt>
        <dd style="text-transform:capitalize;">{{ $user->verification_status ?? 'unknown' }}</dd>

        <dt>Verified On</dt>
        <dd>{{ optional($user->verified_at)->format('F d, Y') ?? 'N/A' }}</dd>
    </dl>

    <p class="note">
        This status page was accessed through the QR code embedded in the official verification certificate.
        If you have concerns about authenticity, please contact the Citizen Verification Office.
    </p>

    <div class="footer">
        Citizen Management · {{ now()->format('F d, Y') }}
    </div>
</div>
</body>
</html>
