@component('mail::message')
# Đặt lịch thành công!

Xin chào **{{ $booking['guest_name'] ?? 'Khách hàng' }}**,

Bạn đã đặt lịch thành công tại **Tiệm sửa xe máy** với thông tin sau:

- 🛠 **Dịch vụ**: {{ $booking['service_name'] }}
- 📅 **Ngày**: {{ $booking['booking_date'] }}
- ⏰ **Thời gian**: {{ $booking['start_time'] }} - {{ $booking['end_time'] }}



Cảm ơn bạn đã sử dụng dịch vụ của chúng tôi.
Nếu có bất kì vấn đề gì xin liên hệ tới email: vukhang.51189@gmail.
Hotline: 0332168695


Trân trọng,  
**Tiệm sửa xe máy**
@endcomponent
