<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Session Receipt</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
  <style>
    body {
      background: #f8f9fa;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      padding: 2rem 0;
    }
    .receipt {
      max-width: 600px;
      margin: auto;
      background: #fff;
      border-radius: 10px;
      box-shadow: 0 8px 18px rgba(0, 0, 0, 0.1);
      overflow: hidden;
    }
    .receipt-header {
      background: linear-gradient(135deg, #e8e7e7, #e8e7e7);
      color: #000000;
      text-align: center;
      padding: 1.2rem 1rem;
      font-weight: 700;
      font-size: 1.4rem;
    }
    .receipt-subtitle {
      font-weight: 500;
      font-size: 1rem;
      margin-top: 0.25rem;
      opacity: 0.85;
    }
    .receipt-body {
      padding: 1.5rem 1.5rem 2rem;
      font-size: 0.95rem;
      color: #333;
    }
    .info-row {
      display: flex;
      justify-content: space-between;
      margin-bottom: 0.5rem;
      border-bottom: 1px solid #eee;
      padding-bottom: 0.3rem;
    }
    .info-label {
      font-weight: 600;
      color: #555;
    }
    .info-value {
      font-weight: 500;
      color: #222;
      max-width: 60%;
      text-align: right;
      word-wrap: break-word;
    }
    .amount-box {
      margin-top: 1.5rem;
      background: #e9f7ef;
      border-left: 5px solid #198754;
      padding: 0.8rem 1rem;
      font-weight: 700;
      font-size: 1.2rem;
      text-align: right;
      color: #198754;
      border-radius: 0 8px 8px 0;
    }
    .receipt-footer {
      text-align: center;
      font-size: 0.85rem;
      padding: 1rem 1rem;
      color: #666;
      background: #f9f9f9;
      border-top: 1px solid #ddd;
    }
    .btn-print {
      display: flex;
      justify-content: center;
      margin-top: 1.5rem;
    }
    @media print {
      .btn-print { display: none !important; }
    }
  </style>
</head>
<body>
  <div class="receipt">
    <div class="receipt-header">
        <img src="{{ asset('images/logo/piyalogo-1.png') }}" alt="Company Logo" style="max-height: 60px; margin-bottom: 0.5rem;">
        <div>Piyavate Rehabilitation & Physiotherapy</div>
        <div class="receipt-subtitle">Session Completion Receipt</div>
      </div>

    <div class="receipt-body">
      <div class="info-row">
        <div class="info-label">Patient</div>
        <div class="info-value">{{ $patient->full_name ?? 'N/A' }}</div>
      </div>
      <div class="info-row">
        <div class="info-label">Hospital Number</div>
        <div class="info-value">{{ $patient->HN ?? 'N/A' }}</div>
      </div>
      <div class="info-row">
        <div class="info-label">Doctor</div>
        <div class="info-value">{{ $doctor->doctor_name ?? 'N/A' }}</div>
      </div>
      <div class="info-row">
        <div class="info-label">Session Date</div>
        <div class="info-value">{{ \Carbon\Carbon::parse($session->session_date)->format('d M Y') }}</div>
      </div>
      <div class="info-row">
        <div class="info-label">Time</div>
        <div class="info-value">{{ $session->start_time }} – {{ $session->end_time }}</div>
      </div>
      <div class="info-row">
        <div class="info-label">Type</div>
        <div class="info-value">{{ $session->session_cat ?? 'N/A' }}</div>
      </div>
      <div class="info-row">
        <div class="info-label">Status</div>
        <div class="info-value text-success">Completed</div>
      </div>

      <div class="amount-box">
        Amount Paid: OMR {{ $session->session_price ?? '0.00' }}
      </div>
    </div>

    <div class="receipt-footer">
      Thank you for choosing Piyavate.<br />
      This is a computer-generated receipt and does not require a signature.
    </div>
  </div>

  <div class="btn-print">
    <button class="btn btn-primary" onclick="window.print()">
      <i class="bi bi-printer"></i> Print Receipt
    </button>
  </div>
</body>
</html>
