<!DOCTYPE html>
<html>

<head>
    <title>Order Confirmation</title>
</head>

<body>
    <h2>Thank you for your order, {{ $name }}!</h2>
    <p>Your order has been successfully placed. Below are the details:</p>

    <p><strong>Order Number:</strong> {{ $order_number }}</p>
    <p><strong>Total Amount:</strong> ${{ $amount }}</p>
    <p><strong>Payment Method:</strong> {{ $method }}</p>
    <p><strong>Order Status:</strong> {{ $status }}</p>
    <p><strong>Booking Date:</strong> {{ $booking_date }}</p>

    <h3>🔑 Your Serial Numbers:</h3>
    <ul id="serialList">
        @foreach ($serial_numbers as $product_id => $serials)
            <p><strong>Product ID: {{ $product_id }}</strong></p>
            <ul>
                @foreach ($serials as $serial)
                    <li class="serial-item">{{ $serial }}</li>
                @endforeach
            </ul>
        @endforeach
    </ul>

    <h3>📥 Access or Download Your Files:</h3>
    <p>Click the button below to download your purchased file.</p>

    <button onclick="showSerialsAndDownload()"
        style="display: inline-block; padding: 10px 20px; background-color: #4CAF50; color: white; text-decoration: none; border-radius: 5px; font-size: 16px; cursor: pointer;">
        Download Now
    </button>

    <br><br>

    <p>If you have any issues, feel free to contact us at <a
            href="mailto:support@antivirus.com">support@antivirus.com</a>.</p>

    <p>Best regards,<br> The Antivirus Team</p>

    <script>
        function showSerialsAndDownload() {
            let serials = [];
            document.querySelectorAll('.serial-item').forEach(item => {
                serials.push(item.textContent);
            });

            if (serials.length > 0) {
                alert("Your Serial Numbers:\n" + serials.join("\n"));
            } else {
                alert("No serial numbers found.");
            }

            // Redirect to download after showing the alert
            window.location.href = "{{ asset('storage/virtual_products/sample-file.jpg') }}";
        }
    </script>

</body>

</html>
