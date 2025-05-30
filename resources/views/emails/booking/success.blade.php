@component('mail::message')
# Đặt lịch thành công!

Xin chào **{{ $booking['guest_name'] ?? 'Khách hàng' }}**,

Bạn đã đặt lịch thành công tại **Tiệm sửa xe máy** với thông tin sau:

- 🛠 **Dịch vụ**: {{ $booking['service_name'] }}
- 📅 **Ngày**: {{ $booking['booking_date'] }}
- ⏰ **Thời gian**: {{ $booking['start_time'] }} - {{ $booking['end_time'] }}



Cảm ơn bạn đã sử dụng dịch vụ của chúng tôi.

Trân trọng,  
**Tiệm sửa xe máy**
@endcomponent
