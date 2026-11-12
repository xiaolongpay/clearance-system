<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }
        .header {
            text-align: center;
            margin-bottom: 10px;
        }
        .header h2 {
            margin: 0;
        }
        .header p {
            margin: 2px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        td, th {
            border: 1px solid #000;
            padding: 6px;
        }
        .info-table td {
            font-size: 12px;
        }
        .signature-img {
            max-height: 35px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>STI EDUCATION SERVICES GROUP, INC.</h2>
        <p>Sayre Highway, Poblacion, Valencia City, Bukidnon</p>
        <h3>CLEARANCE</h3>
    </div>

    <table class="info-table">
        <tr>
            <td><strong>Student ID No.:</strong> {{ $student->student_number }}</td>
            <td><strong>Program/Strand:</strong> {{ $student->course }} {{ $student->year_level }}{{ $student->section }}</td>
            <td><strong>SY:</strong> {{ $schoolYear->year_label ?? '' }}</td>
        </tr>
        <tr>
            <td><strong>Last Name:</strong> {{ $student->last_name }}</td>
            <td><strong>First Name:</strong> {{ $student->first_name }}</td>
            <td><strong>Middle Initial:</strong> {{ $student->middle_initial }}</td>
        </tr>
    </table>

    <br>

    <table>
        <thead>
            <tr>
                <th>Office</th>
                <th>Name</th>
                <th>Signature</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($clearanceItems as $item)
                <tr>
                    <td>{{ $item->department->name }}</td>
                    <td>{{ $item->department->officer_name }}</td>
                    <td>
                        @if ($item->status === 'cleared' && $item->department->signature_path)
                            <img class="signature-img" src="{{ $item->department->signatureDataUri() }}">
                        @endif
                    </td>
                    <td>
                        @if ($item->status === 'cleared' && $item->signed_at)
                            {{ $item->signed_at->format('m/d/Y') }}
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>