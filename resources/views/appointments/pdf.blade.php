<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Appointment Slip #{{ $appointment->appointment_code ?? $appointment->appointment_id }}</title>
    <style>
        /* Global Styles based on provided CSS */
        body {
            font-family: 'Segoe UI';
            margin: 0;
            padding: 5px;
            color: #333333;
            line-height: 1.6;
        }

        .container {
            max-width: 100%;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 10px;
            border-radius: 10px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
            /* ตามสไตล์ form/card */
        }

        .header {
            text-align: center;
            border-bottom: 3px solid #81d7f7;
            padding-bottom: 15px;
            margin-bottom: 25px;
        }

        .header h1 {
            color: #81d7f7;
            margin: 0;
            font-size: 26px;
            font-weight: bold;
        }

        .header p {
            color: #555;
            margin: 5px 0 0;
            font-size: 15px;
            font-style: italic;
        }

        /* Content Sections - คล้าย Appointment Card */
        .content {
            margin-bottom: 20px;
            padding: 15px;
            background-color: #d9eef550;
            /* พื้นหลังสีฟ้าอ่อนมากๆ จาก About Section */
            border-radius: 8px;
            border-left: 5px solid #81d7f7;
            /* แถบสีด้านซ้าย */
        }

        .content h2 {
            color: #1e90ff;
            font-size: 1.5rem;
            margin-top: 0;
            padding-bottom: 5px;
            margin-bottom: 10px;
            border-bottom: 1px solid #cceeff;
            /* เส้นแบ่งสีฟ้าอ่อน */
        }

        /* Table Styling for Details */
        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .details-table td {
            padding: 8px 0;
            font-size: 1rem;
            vertical-align: top;
        }

        .details-table td:first-child {
            font-weight: 600;
            width: 40%;
            color: #333;
        }

        .details-table td:last-child {
            font-weight: 500;
            color: #555;
        }

        /* Footer Styling -ปรับสีข้อความ */
        .footer {
            text-align: center;
            border-top: 1px solid #ddd;
            padding-top: 15px;
            margin-top: 25px;
            font-size: 0.9rem;
            color: #777;
        }

        .footer p {
            margin: 5px 0;
        }

        .header .logo {
            margin-bottom: 10px;/
        }

        .header .logo img {
            width: 150px;
            height: auto;
            border-radius: 10%;
            background-color: #ffffffff;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <div class="logo">
                <img src="data:image/jpeg;base64,{{ base64_encode(file_get_contents(public_path('img/dl.jpg'))) }}" alt="Logo">
            </div>
            
            <h1>FunD Dental Clinic</h1>
            <p>Appointment Slip</p>
        </div>

        <div class="content">
            <h2>Appointment Details</h2>
            <table class="details-table">
                <tr>
                    <td>Appointment Code:</td>
                    <td>{{ $appointment->appointment_code ?? $appointment->appointment_id }}</td>
                </tr>
                <tr>
                    <td>Date & Time:</td>
                    <td>{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('l, d F Y') }} at {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('H:i') }}</td>
                </tr>
                <tr>
                    <td>Procedure:</td>
                    <td>{{ $appointment->description }}</td>
                </tr>
            </table>
        </div>

        <div class="content">
            <h2>Patient Information</h2>
            <table class="details-table">
                <tr>
                    <td>Name:</td>
                    <td>{{ $appointment->patient->patient_name }}</td>
                </tr>
                <tr>
                    <td>Patient ID (HN):</td>
                    <td>{{ $appointment->patient->patient_code}}</td>
                </tr>
            </table>
        </div>

        <div class="content">
            <h2>Assigned Dentist</h2>
            <table class="details-table">
                <tr>
                    <td>Name:</td>
                    <td>{{ $appointment->dentist->dentist_name }}</td>
                </tr>
                <tr>
                    <td>Department:</td>
                    <td>{{ $appointment->dentist->dentist_department }}</td>
                </tr>
            </table>
        </div>

        <div class="footer">
            <p>Please arrive 15 minutes before your appointment time.</p>
            <p>&#xA9; Copyright FunD Dentist.</p>
        </div>
    </div>
</body>

</html>