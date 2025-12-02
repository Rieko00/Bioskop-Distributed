<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tiket Bioskop - #{{ str_pad($header->ID, 3, '0', STR_PAD_LEFT) }}</title>
    <style>
        body {
            margin: 0;
            padding: 20px;
            font-family: 'Courier New', monospace;
            background-color: #f5f5f5;
        }

        .ticket-container {
            max-width: 400px;
            margin: 0 auto;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            position: relative;
        }

        .ticket-header {
            background: white;
            padding: 20px;
            text-align: center;
            position: relative;
        }

        .cinema-logo {
            font-size: 24px;
            font-weight: bold;
            color: #333;
            margin-bottom: 5px;
        }

        .cinema-tagline {
            font-size: 12px;
            color: #666;
            margin-bottom: 15px;
        }

        .ticket-id {
            background: #667eea;
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: bold;
            display: inline-block;
        }

        .perforation {
            background: repeating-linear-gradient(to right,
                    transparent 0px,
                    transparent 8px,
                    #f5f5f5 8px,
                    #f5f5f5 16px);
            height: 2px;
            width: 100%;
        }

        .ticket-body {
            background: white;
            padding: 25px 20px;
        }

        .movie-info {
            text-align: center;
            margin-bottom: 25px;
            padding-bottom: 20px;
            border-bottom: 2px dashed #ddd;
        }

        .movie-title {
            font-size: 20px;
            font-weight: bold;
            color: #333;
            margin-bottom: 8px;
            text-transform: uppercase;
        }

        .ticket-details {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-bottom: 20px;
        }

        .detail-item {
            text-align: center;
        }

        .detail-label {
            font-size: 10px;
            color: #666;
            text-transform: uppercase;
            font-weight: bold;
            margin-bottom: 3px;
        }

        .detail-value {
            font-size: 14px;
            color: #333;
            font-weight: bold;
        }

        .seat-info {
            text-align: center;
            margin: 20px 0;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 10px;
        }

        .seat-label {
            font-size: 12px;
            color: #666;
            text-transform: uppercase;
            margin-bottom: 5px;
        }

        .seat-number {
            font-size: 24px;
            font-weight: bold;
            color: #667eea;
        }

        .price-info {
            text-align: center;
            margin: 20px 0;
            padding: 15px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            border-radius: 10px;
        }

        .price-label {
            font-size: 12px;
            margin-bottom: 5px;
            opacity: 0.8;
        }

        .price-amount {
            font-size: 20px;
            font-weight: bold;
        }

        .ticket-footer {
            background: #f8f9fa;
            padding: 15px 20px;
            text-align: center;
            font-size: 10px;
            color: #666;
            line-height: 1.4;
        }

        .qr-code {
            text-align: center;
            margin: 15px 0;
            padding: 20px;
            border: 2px dashed #ddd;
            border-radius: 10px;
        }

        .qr-placeholder {
            width: 80px;
            height: 80px;
            background: #f0f0f0;
            margin: 0 auto;
            border-radius: 5px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            color: #999;
        }

        .payment-method {
            background: #e8f5e8;
            color: #2d5a2d;
            padding: 8px 12px;
            border-radius: 15px;
            font-size: 11px;
            font-weight: bold;
            display: inline-block;
            margin-top: 10px;
        }

        @media print {
            body {
                background: white;
                padding: 0;
            }

            .ticket-container {
                box-shadow: none;
                max-width: none;
                margin: 0;
            }
        }

        .multiple-tickets {
            page-break-after: always;
        }

        .multiple-tickets:last-child {
            page-break-after: auto;
        }
    </style>
</head>

<body>
    @foreach ($details as $index => $detail)
        <div class="ticket-container {{ $details->count() > 1 ? 'multiple-tickets' : '' }}">
            <!-- Ticket Header -->
            <div class="ticket-header">
                <div class="cinema-logo">🎬 BIOSKOP CHAIN</div>
                <div class="cinema-tagline">Experience The Magic of Movies</div>
                <div class="ticket-id">TICKET #{{ str_pad($detail->{'ID Detail'}, 3, '0', STR_PAD_LEFT) }}</div>
            </div>

            <!-- Perforation -->
            <div class="perforation"></div>

            <!-- Ticket Body -->
            <div class="ticket-body">
                <!-- Movie Info -->
                <div class="movie-info">
                    <div class="movie-title">{{ $detail->Film }}</div>
                    <div style="font-size: 12px; color: #666;">
                        {{ $detail->Cabang }}
                    </div>
                </div>

                <!-- Ticket Details -->
                <div class="ticket-details">
                    <div class="detail-item">
                        <div class="detail-label">Studio</div>
                        <div class="detail-value">{{ $detail->Studio }}</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Tanggal</div>
                        <div class="detail-value">{{ \Carbon\Carbon::parse($detail->Waktu)->format('d/m/Y') }}</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Waktu</div>
                        <div class="detail-value">{{ \Carbon\Carbon::parse($detail->Waktu)->format('H:i') }}</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Transaksi</div>
                        <div class="detail-value">#{{ str_pad($detail->ID, 3, '0', STR_PAD_LEFT) }}</div>
                    </div>
                </div>

                <!-- Seat Info -->
                <div class="seat-info">
                    <div class="seat-label">Nomor Kursi</div>
                    <div class="seat-number">{{ $detail->{'No Kursi'} }}</div>
                </div>

                <!-- Price Info -->
                <div class="price-info">
                    <div class="price-label">Harga Tiket</div>
                    <div class="price-amount">Rp {{ number_format($detail->{'Harga Tiket'}, 0, ',', '.') }}</div>
                </div>

                <!-- Customer Info -->
                <div style="text-align: center; margin: 15px 0;">
                    <div style="font-size: 11px; color: #666; margin-bottom: 5px;">PELANGGAN</div>
                    <div style="font-weight: bold; color: #333;">{{ $detail->Pelanggan }}</div>
                    <div class="payment-method">{{ $detail->Pembayaran }}</div>
                </div>

                <!-- QR Code Placeholder -->
                <div class="qr-code">
                    <div class="qr-placeholder">
                        <div>QR CODE<br>SCAN ME</div>
                    </div>
                    <div style="font-size: 10px; color: #999; margin-top: 8px;">
                        Scan untuk validasi tiket
                    </div>
                </div>
            </div>

            <!-- Ticket Footer -->
            <div class="ticket-footer">
                <strong>SYARAT DAN KETENTUAN:</strong><br>
                • Tiket tidak dapat dikembalikan atau ditukar<br>
                • Harap tiba 15 menit sebelum pertunjukan<br>
                • Dilarang membawa makanan dan minuman dari luar<br>
                • Simpan tiket ini sebagai bukti masuk<br><br>

                <strong>Dicetak pada:</strong> {{ now()->format('d/m/Y H:i:s') }}<br>
                <strong>Petugas:</strong> {{ Auth::user()->name }}<br>

                <div style="margin-top: 10px; font-size: 8px;">
                    ID: {{ $detail->{'ID Detail'} }} | TRX: {{ $detail->ID }} |
                    Waktu: {{ \Carbon\Carbon::parse($detail->Waktu)->format('d/m/Y H:i') }}
                </div>
            </div>
        </div>

        @if ($index < $details->count() - 1)
            <div style="margin: 30px 0; text-align: center; page-break-before: always;">
                <!-- Page break for multiple tickets -->
            </div>
        @endif
    @endforeach

    <script>
        // Auto print when page loads
        window.onload = function() {
            window.print();
        };

        // Close window after printing (optional)
        window.onafterprint = function() {
            window.close();
        };
    </script>
</body>

</html>
