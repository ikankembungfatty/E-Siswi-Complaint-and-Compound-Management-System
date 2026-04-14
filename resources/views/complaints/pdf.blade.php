<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Complaint Report - {{ $complaint->title }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 12px;
            color: #1e293b;
            line-height: 1.6;
            padding: 40px;
        }

        /* Header */
        .header {
            text-align: center;
            border-bottom: 3px solid #4f46e5;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }

        .header h1 {
            font-size: 22px;
            color: #4f46e5;
            margin-bottom: 4px;
        }

        .header .subtitle {
            font-size: 11px;
            color: #64748b;
        }

        .report-title {
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            color: #1e293b;
            margin-bottom: 25px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* Info Grid */
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .info-table td {
            padding: 10px 12px;
            border: 1px solid #e2e8f0;
            vertical-align: top;
        }

        .info-table .label {
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #64748b;
            font-weight: bold;
            width: 30%;
            background: #f8fafc;
        }

        .info-table .value {
            color: #1e293b;
            font-weight: 500;
        }

        /* Status & Priority Badges */
        .badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .badge-processing {
            background: #fef3c7;
            color: #92400e;
        }

        .badge-in_progress {
            background: #dbeafe;
            color: #1e40af;
        }

        .badge-completed {
            background: #d1fae5;
            color: #065f46;
        }

        .badge-low {
            background: #d1fae5;
            color: #065f46;
        }

        .badge-medium {
            background: #fef3c7;
            color: #92400e;
        }

        .badge-high {
            background: #fee2e2;
            color: #991b1b;
        }

        /* Description Box */
        .description-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 15px;
            margin-bottom: 20px;
        }

        .description-box h3 {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #64748b;
            margin-bottom: 8px;
        }

        .description-box p {
            color: #334155;
            white-space: pre-wrap;
        }

        /* Resolution Notes */
        .resolution-box {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            border-radius: 6px;
            padding: 15px;
            margin-bottom: 20px;
        }

        .resolution-box h3 {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #065f46;
            margin-bottom: 8px;
        }

        /* Footer */
        .footer {
            position: fixed;
            bottom: 30px;
            left: 40px;
            right: 40px;
            border-top: 1px solid #e2e8f0;
            padding-top: 10px;
            font-size: 9px;
            color: #94a3b8;
            text-align: center;
        }
    </style>
</head>

<body>
    <!-- Header -->
    <div class="header">
        <h1>E-Siswi UPTM</h1>
        <div class="subtitle">Hostel Management System — Complaint Report</div>
    </div>

    <div class="report-title">Complaint Report</div>

    <!-- Complaint Info -->
    <table class="info-table">
        <tr>
            <td class="label">Reference #</td>
            <td class="value">CMP-{{ str_pad($complaint->id, 5, '0', STR_PAD_LEFT) }}</td>
            <td class="label">Date Submitted</td>
            <td class="value">{{ $complaint->created_at->format('d M Y, h:i A') }}</td>
        </tr>
        <tr>
            <td class="label">Status</td>
            <td class="value">
                <span class="badge badge-{{ $complaint->status }}">
                    {{ ucfirst(str_replace('_', ' ', $complaint->status)) }}
                </span>
            </td>
            <td class="label">Priority</td>
            <td class="value">
                <span class="badge badge-{{ $complaint->priority }}">
                    {{ ucfirst($complaint->priority) }}
                </span>
            </td>
        </tr>
        <tr>
            <td class="label">Category</td>
            <td class="value">{{ $complaint->category->name }}</td>
            <td class="label">Location</td>
            <td class="value">{{ $complaint->location ?? 'Not specified' }}</td>
        </tr>
        <tr>
            <td class="label">Submitted By</td>
            <td class="value">
                {{ $complaint->user->name }}
                @if($complaint->user->student_id)
                    <br><span style="color: #64748b; font-size: 10px;">{{ $complaint->user->student_id }}</span>
                @endif
            </td>
            <td class="label">Assigned To</td>
            <td class="value">{{ $complaint->assignedWarden->name ?? 'Not assigned' }}</td>
        </tr>
        @if($complaint->completed_at)
            <tr>
                <td class="label">Completed On</td>
                <td class="value" colspan="3">{{ $complaint->completed_at->format('d M Y, h:i A') }}</td>
            </tr>
        @endif
    </table>

    <!-- Description -->
    <div class="description-box">
        <h3>Description</h3>
        <p>{{ $complaint->description }}</p>
    </div>

    <!-- Resolution Notes -->
    @if($complaint->resolution_notes)
        <div class="resolution-box">
            <h3>Resolution Notes</h3>
            <p>{{ $complaint->resolution_notes }}</p>
        </div>
    @endif

    <!-- Footer -->
    <div class="footer">
        Generated on {{ now()->format('d M Y, h:i A') }} — E-Siswi UPTM Hostel Management System — This is a
        system-generated report
    </div>
</body>

</html>