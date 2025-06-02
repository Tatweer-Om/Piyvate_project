<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Printable Patient Session Receipt</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f8f9fa;
    }

    .receipt {
      max-width: 800px;
      margin: 30px auto;
      background: white;
      padding: 30px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }

    .logo img {
      max-height: 80px;
    }

    .title {
      font-weight: bold;
      font-size: 1.5rem;
      color: #333;
    }

    .details {
      line-height: 1.5;
    }

    .table th, .table td {
      vertical-align: middle;
    }

    @media print {
      body {
        background: none;
      }

      .receipt {
        box-shadow: none !important;
        margin: 0;
        padding: 0;
        border: none;
        page-break-inside: avoid;
      }

      .table {
        page-break-inside: avoid;
      }

      .table th, .table td {
        border: 1px solid #000 !important;
      }

      .no-print {
        display: none !important;
      }
    }
  </style>
</head>
<body>

<div class="receipt border rounded">
        <div class="row mb-4 align-items-center">
          <div class="col-md-4 logo">
            <img src="{{ asset('images/logo/piyalogo-1.png') }}" alt="Clinic Logo" class="img-fluid">
          </div>
          <div class="col-md-8 text-end">
            <h5 class="title">Patient Invoice</h5>
            <p class="mb-0">Session Date: <strong>{{ $appointment->session_date ?? '' }}</strong></p>
            <p class="mb-0">Session No: <strong>{{ $appointment->session_no ?? '' }}</strong></p>
          </div>
        </div>

        <hr>

        <div class="row details mb-4">
          <div class="col-md-6">
            <h6 class="text-muted">Patient Information</h6>
            <p class="mb-1"><strong>Name:</strong> {{ $patient->full_name ?? '' }}</p>
            <p class="mb-1"><strong>Mobile:</strong> {{ $patient->HN ?? '' }}</p>
            <p class="mb-1"><strong>Age:</strong> {{ $patient->age ?? '' }}</p>
          </div>
          <div class="col-md-6 text-md-end">
            <h6 class="text-muted">Doctor Information</h6>
            <p class="mb-1"><strong>Name:</strong> {{ $doctor->doctor_name ?? '' }}</p>
            <p class="mb-1"><strong>Department:</strong>{{$special ?? '' }} </p>
    </div>
</div>

  <h6 class="mb-3">Session Details</h6>
  <table class="table table-bordered">
    <thead class="table-light">
      <tr>
        <th>Session No.</th>
        <th>Total Sessions</th>
        <th>Session Status</th>
        <th>Session Type</th>
        <th>Session Price</th>
        <th>Total Price</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>{{ $appointment->session_no ?? '' }}</td>
        <td>{{ $total_sessions ?? '' }}</td>
        <td>
            <span class="text-success">Done: {{ $session_done ?? '' }}</span><br>
            <span class="text-danger">Remaining: {{ $session_remaining ?? '' }}</span>
        </td>
        <td>{{$sessionType ?? ''}}</td>
                <td>{{ $session_price ?? ''}}</td>
        <td>{{ $total_price ?? '' }}</td>

      </tr>
    </tbody>
  </table>

  <div class="row justify-content-end">
    <div class="col-md-5">
      <table class="table">
        <tr>
          <th>Subtotal:</th>
          <td>{{ $total_price ?? '' }}</td>
        </tr>
        <tr>
          <th>Discount:</th>
          <td>0.00</td>
        </tr>
        <tr class="table-active">
          <th>Total:</th>
          <td><strong>OMR: {{ $total_price ?? '' }}</strong></td>
        </tr>
      </table>
    </div>
  </div>

  <p class="text-center text-muted mt-4">Thank you for choosing Piyvate Clinic!</p>
</div>

<!-- Optional Print Button -->
<div class="text-center mt-4 no-print">
  <button onclick="window.print()" class="btn btn-primary">Print Receipt</button>
</div>

</body>
</html>
