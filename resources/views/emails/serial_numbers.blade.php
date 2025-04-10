<!DOCTYPE html>
<html>

<head>
    <title>Your Product Serial Numbers</title>
</head>

<body>
    <h2>Thank you for your purchase!</h2>
    <p>Here are your serial numbers and download links:</p>

    @foreach ($orderedSerials as $item)
        <h3>Product: {{ $item['product_name'] }}</h3>
        <p><strong>Product Detalis :</strong> {{ implode(', ', $item['serial_numbers']) }}</p>

        @if (count($item['file_paths']) > 0)
            <p><strong>Download Files:</strong></p>
            <ul>
                @foreach ($item['file_paths'] as $filePath)
                    <li><a href="{{ asset("$filePath") }}" download>{{ basename($filePath) }}</a>
                    </li>
                @endforeach
            </ul>
        @endif

        <hr>
    @endforeach

    <p>Best regards,<br>Support Team</p>
</body>

</html>
