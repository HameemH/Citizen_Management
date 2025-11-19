<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Verification Certificate</title>
    <style>
        * { box-sizing: border-box; }
        body {
            font-family: 'DejaVu Sans', 'Nunito', sans-serif;
            color: #1f2937;
            margin: 0;
            padding: 40px;
            background: #f3f4f6;
        }
        .certificate {
            border: 6px double #16a34a;
            padding: 40px;
            background: #ffffff;
            position: relative;
            min-height: 950px;
        }
        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 140px;
            color: rgba(22, 163, 74, 0.06);
            text-transform: uppercase;
            letter-spacing: 10px;
            pointer-events: none;
        }
        h1 {
            text-align: center;
            font-size: 34px;
            text-transform: uppercase;
            letter-spacing: 6px;
            margin-bottom: 10px;
        }
        h2 {
            text-align: center;
            font-size: 20px;
            font-weight: normal;
            margin-bottom: 40px;
            letter-spacing: 1px;
            color: #4b5563;
        }
        .details {
            margin: 40px 0;
        }
        .details table {
            width: 100%;
            border-collapse: collapse;
        }
        .details th {
            text-align: left;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #6b7280;
            padding-bottom: 6px;
        }
        .details td {
            font-size: 18px;
            font-weight: 600;
            padding-bottom: 20px;
            color: #111827;
        }
        .footer {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-top: 60px;
        }
        .signature {
            width: 45%;
            text-align: center;
        }
        .signature .line {
            border-top: 1px solid #9ca3af;
            margin-top: 50px;
        }
        .qr {
            width: 35%;
            text-align: center;
        }
        .meta {
            margin-top: 30px;
            font-size: 12px;
            color: #6b7280;
            text-align: center;
        }
    </style>
</head>
<body>
<div class="certificate">
    <div class="watermark">Verified</div>
    <h1>Verification Certificate</h1>
    <h2>Government of Smart City · Citizen Verification Authority</h2>

    <p style="text-align:center;font-size:16px;color:#374151;margin-bottom:35px;">
        This is to certify that the individual mentioned below has been successfully verified
        as a registered citizen in accordance with the national verification guidelines.
    </p>

    <div class="details">
        <table>
            <tr>
                <th>Citizen Name</th>
                <td>{{ $user->full_name ?? $user->display_name }}</td>
            </tr>
            <tr>
                <th>National ID (NID)</th>
                <td>{{ $user->nid_number }}</td>
            </tr>
            <tr>
                <th>Date of Birth</th>
                <td>{{ optional($user->date_of_birth)->format('F d, Y') }}</td>
            </tr>
            <tr>
                <th>Verification Status</th>
                <td>Verified on {{ $issuedDate }}</td>
            </tr>
            <tr>
                <th>Certificate Number</th>
                <td>{{ $certificateNumber }}</td>
            </tr>
        </table>
    </div>

    <div class="footer">
        <div class="signature">
            <div class="line"></div>
            <p style="margin-top:8px;font-size:13px;color:#374151;">Authorized Verification Officer</p>
            <p style="font-size:11px;color:#9ca3af;">Citizen Services Directorate</p>
        </div>

        <div class="qr">
            @if($qrImage)
                <img src="{{ $qrImage }}" alt="Verification QR" style="width:220px;height:220px;">
            @else
                <p style="font-size:12px;color:#9ca3af;">QR code unavailable. Visit:<br>{{ $qrFallbackUrl }}</p>
            @endif
            <p style="font-size:11px;margin-top:10px;color:#6b7280;">Scan to verify authenticity</p>
        </div>
    </div>

    <div class="meta">
        Generated on {{ now()->format('F d, Y') }} · Valid as long as verification status remains active.
    </div>
</div>
</body>
</html>
