@extends('layouts.app')

@php
    $registry = $payment?->registry;
    $arazi = $registry?->arazi;
    $customer = $payment?->customer ?? $registry?->customer;
    $kisan = $payment?->kisan ?? $arazi?->kisan;
    $agent = $registry?->agent;

    $amount = (float) ($payment?->amount ?? 0);
    $amountWords = null;

    if (class_exists(NumberFormatter::class)) {
        $formatter = new NumberFormatter('en_IN', NumberFormatter::SPELLOUT);
        $amountWords = ucfirst((string) $formatter->format($amount)) . ' rupees only';
    }
@endphp

@push('styles')
    <style>
        .receipt-shell {
            border: 4px solid #a32929;
            background: #ffffff;
        }

        .receipt-row {
            border-top: 2px solid #a32929;
        }

        .receipt-cell {
            padding: 10px;
            border-right: 2px solid #a32929;
        }

        .receipt-cell:last-child {
            border-right: 0;
        }

        .receipt-banner {
            background: #d13bb2;
            color: #fff;
            font-weight: 700;
            text-align: center;
            padding: 6px 8px;
            font-size: 1.1rem;
        }

        .receipt-title {
            font-size: 3rem;
            line-height: 1;
            font-weight: 800;
            color: #111;
            text-align: center;
            text-transform: uppercase;
            letter-spacing: 1px;
            text-shadow: 2px 2px 0 #cc0b0b;
        }

        .receipt-subtitle {
            font-size: 2rem;
            text-align: center;
            font-weight: 700;
            text-transform: uppercase;
            color: #cb3cae;
        }

        .field-box {
            min-height: 40px;
            border: 1px solid #888;
            background: #f7f7f7;
            padding: 6px 8px;
            display: flex;
            align-items: center;
        }

        .field-label {
            font-weight: 700;
            font-size: 1.1rem;
        }

        @media print {
            .app-header,
            .app-sidebar,
            .app-content-header,
            .app-footer,
            .no-print {
                display: none !important;
            }

            .app-main,
            .app-content,
            .container-fluid {
                margin: 0 !important;
                padding: 0 !important;
            }

            .receipt-shell {
                border-width: 3px;
            }
        }
    </style>
@endpush

@section('content')
    <div class="no-print card card-outline card-primary mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('payments.print') }}" class="row g-2 align-items-end">
                <div class="col-md-4">
                    <label for="receipt_no" class="form-label">RECEIPT. No.</label>
                    <input type="text" id="receipt_no" name="receipt_no" value="{{ $receiptNo }}" class="form-control" placeholder="Enter receipt no / reference no">
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary w-100">Check Rec</button>
                </div>
                <div class="col-md-3">
                    <button type="button" class="btn btn-success w-100" onclick="window.print()">Print</button>
                </div>
            </form>
            @if($receiptNo !== '' && !$payment)
                <div class="alert alert-warning mt-3 mb-0">No receipt record found for this number.</div>
            @endif
        </div>
    </div>

    <div class="receipt-shell">
        <div class="row g-0">
            <div class="col-lg-6 receipt-cell">
                <div class="receipt-title">HEED REAL</div>
                <div class="h2 text-center text-uppercase mb-2">Estate Private Limited</div>
                <div class="receipt-banner mb-2">300/5, PAC Road, PAC Lane, Gadiyana, Kanpur, Uttar Pradesh</div>
                <div class="receipt-subtitle mb-2">Renewal Subscription Receipt</div>
                <p class="text-center mb-1">Received with thanks the amount of installment towards the subscription payable according to terms & conditions.</p>
                <p class="text-center mb-0">Only official receipt with company stamp and authorized signature is valid proof of payment.</p>
            </div>
            <div class="col-lg-6 receipt-cell">
                <div class="row g-2">
                    <div class="col-6 field-label">RECEIPT. No.</div>
                    <div class="col-6"><div class="field-box">{{ $payment?->receipt_no ?? '-' }}</div></div>

                    <div class="col-6 field-label">ASC Name</div>
                    <div class="col-6"><div class="field-box">{{ $customer?->name ?? '-' }}</div></div>

                    <div class="col-6 field-label">Reg no No.</div>
                    <div class="col-6"><div class="field-box" style="background:#c5f35f;">{{ $registry?->customer_reg_no ?? '-' }}</div></div>

                    <div class="col-6 field-label">ASC Code</div>
                    <div class="col-6"><div class="field-box">{{ $customer?->legacy_customer_code ?? '-' }}</div></div>

                    <div class="col-6 field-label">Date</div>
                    <div class="col-6"><div class="field-box">{{ optional($payment?->payment_date)->format('d-m-Y') ?? '-' }}</div></div>

                    <div class="col-6 field-label">Due Date</div>
                    <div class="col-6"><div class="field-box">{{ optional($registry?->due_date)->format('d-m-Y') ?? '-' }}</div></div>

                    <div class="col-6 field-label">End Of Term</div>
                    <div class="col-6"><div class="field-box">{{ optional($registry?->expected_registry_date)->format('d-m-Y') ?? '-' }}</div></div>

                    <div class="col-6 field-label">Installment No.</div>
                    <div class="col-6"><div class="field-box">{{ $payment?->reference_no ?? '-' }}</div></div>

                    <div class="col-6 field-label">BOOK BY / REF. NO.</div>
                    <div class="col-6"><div class="field-box">{{ $agent?->name ?? '-' }}</div></div>
                </div>
            </div>
        </div>

        <div class="receipt-row p-3">
            <div class="row g-2 align-items-center">
                <div class="col-lg-2 field-label">SCHEDULE</div>
                <div class="col-lg-2 field-label">ASC Address</div>
                <div class="col-lg-8"><div class="field-box">{{ $customer?->address ?? '-' }}</div></div>
            </div>
        </div>

        <div class="receipt-row p-3">
            <div class="row g-3">
                <div class="col-lg-8">
                    <div class="row g-2">
                        <div class="col-sm-5 field-label">Total Land Value</div>
                        <div class="col-sm-7"><div class="field-box">{{ number_format((float) ($registry?->registry_amount ?? 0), 2) }}</div></div>

                        <div class="col-sm-5 field-label">Balance Amount Rs.</div>
                        <div class="col-sm-7"><div class="field-box">{{ number_format((float) (($registry?->registry_amount ?? 0) - $amount), 2) }}</div></div>

                        <div class="col-sm-5 field-label">Amount Received</div>
                        <div class="col-sm-7"><div class="field-box">{{ number_format($amount, 2) }}</div></div>

                        <div class="col-sm-5 field-label">2% Late Charges</div>
                        <div class="col-sm-7"><div class="field-box">0.00</div></div>

                        <div class="col-sm-5 field-label">Cheque Bounce Charge</div>
                        <div class="col-sm-7"><div class="field-box">0.00</div></div>

                        <div class="col-sm-5 field-label text-danger">Balance Received Amount</div>
                        <div class="col-sm-7"><div class="field-box text-danger">{{ number_format($amount, 2) }}</div></div>
                    </div>
                </div>

                <div class="col-lg-4" style="border-left:2px solid #2f4fff;">
                    <div class="field-label mb-2">Associate's Name & Address</div>
                    <div class="field-box" style="min-height:110px;">{{ $kisan?->name ?? '-' }}{{ $kisan?->address ? ', '.$kisan->address : '' }}</div>
                    <div class="mt-3 h4">For : HEED REAL ESTATE PRIVATE LIMITED</div>
                </div>
            </div>
        </div>

        <div class="receipt-row p-3">
            <div class="row g-2 align-items-center">
                <div class="col-lg-2 receipt-banner text-start">Amount in word Rs.</div>
                <div class="col-lg-8"><div class="field-box">{{ $amountWords ?? '-' }}</div></div>
                <div class="col-lg-2 text-center h3 m-0">Auth Signatory</div>
            </div>
        </div>
    </div>
@endsection
