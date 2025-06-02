<!DOCTYPE html>
@extends('layouts.base')

@section('title', 'Trang chủ - Đặt lịch sửa xe')
<style>
    .time-slot {
        width: 80px;
        height: 60px;
        margin: 5px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #ddd;
        border-radius: 5px;
        background-color: #f8f9fa;
        cursor: pointer;
        font-size: 14px;
        transition: all 0.2s;
    }

    .time-slot:hover {
        background-color: #e9ecef;
    }

    .time-slot.selected {
        background-color: #007bff;
        color: white;
        border-color: #007bff;
    }

    .time-slot.disabled {
        background-color: #e9ecef;
        color: #6c757d;
        cursor: not-allowed;
        border-color: #ced4da;
    }

    .is-invalid {
        border-color: #dc3545 !important;
    }

    .invalid-feedback {
        color: #dc3545;
        font-size: 14px;
        margin-top: 5px;
        display: block;
    }

    .time-slots-container {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        max-height: 200px;
        overflow-y: auto;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 5px;
        background-color: #fff;
    }

    #loadingModal {
        transition: opacity 0.3s ease;
    }

    #loadingModal[style*="display: none"] {
        opacity: 0;
        pointer-events: none;
    }
</style>
@section('content')
<div class="text-center mb-4">
    <h1 class="display-5" style="font-weight:bold; font-family:Roboto, Helvetica, Arial, Verdana; font-size: 36px;"> Đặt lịch sửa xe máy</h1>
    <p class="text-muted" style="font-family:Roboto, Helvetica, Arial, Verdana; font-size: 24px;">Chọn ngày, giờ và dịch vụ để đặt lịch hẹn</p>
</div>

<div class="card shadow-sm ">
    <div class="card-body">
        <div id="bookingForm">
            @if (!session('customer_name'))
            <div class="mb-3">
                <label for="guest_name" class="form-label" style="font-family: Roboto, Helvetica, Arial, Verdana; font-size: 16px;">Họ và tên</label>
                <input type="text" id="guest_name" name="guest_name" class="form-control" style="font-family: Roboto, Helvetica, Arial, Verdana; font-size: 16px;min-height: 40px;" required>
            </div>
            <div class="mb-3">
                <label for="guest_email" class="form-label" style="font-family: Roboto, Helvetica, Arial, Verdana; font-size: 16px;">Email</label>
                <input type="email" id="guest_email" name="guest_email" class="form-control" style="font-family: Roboto, Helvetica, Arial, Verdana; font-size: 16px;min-height: 40px;" required>
            </div>
            <div class="mb-3">
                <label for="guest_phone" class="form-label" style="font-family: Roboto, Helvetica, Arial, Verdana; font-size: 16px;">Số điện thoại</label>
                <input type="text" id="guest_phone" name="guest_phone" class="form-control" style="font-family: Roboto, Helvetica, Arial, Verdana; font-size: 16px;min-height: 40px;" required>
            </div>
            @endif
            @if (session('customer_name'))
            <div class="mb-3">
                <label for="guest_name" class="form-label" style="font-family: Roboto, Helvetica, Arial, Verdana; font-size: 16px;">Họ và tên</label>
                <input type="text" id="guest_name" name="guest_name" class="form-control" value="{{ session('customer_name') }}" style="font-family: Roboto, Helvetica, Arial, Verdana; font-size: 16px;min-height: 40px;" required>
            </div>
            <div class="mb-3">
                <label for="guest_email" class="form-label" style="font-family: Roboto, Helvetica, Arial, Verdana; font-size: 16px;">Email</label>
                <input type="email" id="guest_email" name="guest_email" class="form-control" value="{{ session('customer_email') }}" style="font-family: Roboto, Helvetica, Arial, Verdana; font-size: 16px;min-height: 40px;" required>
            </div>
            <div class="mb-3">
                <label for="guest_phone" class="form-label" style="font-family: Roboto, Helvetica, Arial, Verdana; font-size: 16px;">Số điện thoại</label>
                <input type="text" id="guest_phone" name="guest_phone" class="form-control" value="{{ session('tel_num') }}" style="font-family: Roboto, Helvetica, Arial, Verdana; font-size: 16px;min-height: 40px;" required>
            </div>
            @endif
            <div class="mb-3">
                <label for="service_id" class="form-label" style="font-family: Roboto, Helvetica, Arial, Verdana; font-size: 16px;">Dịch vụ</label>
                <select name="service_id" id="service_id" class="form-select" style="font-family: Roboto, Helvetica, Arial, Verdana; font-size: 16px; min-height: 40px;" required>
                    <option value="">Chọn dịch vụ</option>
                    @foreach($services as $service)
                    <option value="{{ $service->service_id }}" {{ $selectedServiceId == $service->service_id ? 'selected' : '' }}>{{ $service->service_name }} - {{$service->duration_minute }} phút</option>
                    @endforeach
                </select>
            </div>
            <label for="booking_date" class="form-label" style="font-family: Roboto, Helvetica, Arial, Verdana; font-size: 16px;">Ngày</label>
            <input type="date" name="booking_date" id="booking_date" class="form-control" style="min-height: 40px;"
                min="{{ \Carbon\Carbon::today()->format('Y-m-d') }}"
                max="{{ \Carbon\Carbon::today()->addDays(14)->format('Y-m-d') }}"
                required>


            <label class="form-label" style="font-family: Roboto, Helvetica, Arial, Verdana; font-size: 16px; margin-top: 17px;">Giờ bắt đầu</label>
            <input type="hidden" name="booking_time" id="booking_time" style="min-height: 40px;">
            <div class="time-slots-container" id="timeSlotsContainer" style="min-height: 40px;">
                <option value="">Vui lòng chọn ngày</option>
            </div>

        </div>

    </div>
    <button id="submitBooking" class="btn  w-100" style="margin-top:50px;background-color:#333333 ;padding-bottom: 20; min-height:40px;font-weight:bold;font-size: 24px; color:white">Đặt lịch</button>
</div>
</div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
@push('scripts')
@include('script.booking')
@endpush
<div id="loadingModal" style="display:none; position:fixed; z-index:9999; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.4); display:flex; justify-content:center; align-items:center;">
    <div style="background:white; padding:30px 40px; border-radius:12px; text-align:center; box-shadow:0 10px 30px rgba(0,0,0,0.2); font-family: 'Roboto', sans-serif; max-width:300px;">
        <div class="spinner-border text-primary" role="status" style="width:3rem; height:3rem;">
            <span class="visually-hidden">Loading...</span>
        </div>
        <div style="margin-top:20px; font-size:18px; font-weight:500; color:#333;">
            Đang xử lý, vui lòng chờ...
        </div>
    </div>
</div>
@endsection