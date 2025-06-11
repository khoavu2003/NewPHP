<!DOCTYPE html>
<html>
<head>
    <title>Thông báo lịch bảo trì</title>
</head>
<body>
    <h1>Thông báo lịch bảo trì xe</h1>
    <p>Kính gửi Quý khách,</p>
    <p>Xe của bạn với thông tin sau cần được bảo trì định kỳ:</p>
    <ul>
        <li><strong>Biển số xe:</strong> {{ $vehicle->license_plate }}</li>
        <li><strong>Mẫu xe:</strong> {{ $vehicle->model }}</li>
        <li><strong>Ngày bảo trì tiếp theo:</strong> {{ $maintenance->next_maintenance_date->format('d/m/Y') }}</li>
    </ul>
    <p>Vui lòng liên hệ với chúng tôi để đặt lịch bảo trì. Cảm ơn bạn đã sử dụng dịch vụ của chúng tôi!</p>
    <p>Trân trọng,<br>{{ config('app.name') }}</p>
</body>
</html>