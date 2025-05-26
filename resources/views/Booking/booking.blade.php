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
</style>
@section('content')
<div class="text-center mb-4">
    <h1 class="display-5" style="font-weight:bold; font-family:Roboto, Helvetica, Arial, Verdana; font-size: 36px;"> Đặt lịch sửa xe máy</h1>
    <p class="text-muted" style="font-family:Roboto, Helvetica, Arial, Verdana; font-size: 24px;">Chọn ngày, giờ và dịch vụ để đặt lịch hẹn</p>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <div id="bookingForm">

            <div class="mb-3">
                <label for="service_id" class="form-label" style="font-family: Roboto, Helvetica, Arial, Verdana; font-size: 24px; min-height: 60px;">Dịch vụ</label>
                <select name="service_id" id="service_id" class="form-select" style="font-family: Roboto, Helvetica, Arial, Verdana; font-size: 16px; min-height: 60px;" required>
                    <option value="">Chọn dịch vụ</option>
                    @foreach($services as $service)
                    <option value="{{ $service->service_id }}" {{ $selectedServiceId == $service->service_id ? 'selected' : '' }}>{{ $service->service_name }}</option>
                    @endforeach
                </select>
            </div>


            <label for="booking_date" class="form-label" style="font-family: Roboto, Helvetica, Arial, Verdana; font-size: 16px;">Ngày</label>
            <input type="date" name="booking_date" id="booking_date" class="form-control" style="min-height: 60px;"
                min="{{ \Carbon\Carbon::today()->format('Y-m-d') }}"
                max="{{ \Carbon\Carbon::today()->addDays(14)->format('Y-m-d') }}"
                required>


            <label class="form-label" style="font-family: Roboto, Helvetica, Arial, Verdana; font-size: 16px; margin-top: 17px;">Giờ bắt đầu</label>
            <input type="hidden" name="booking_time" id="booking_time" style="min-height: 60px;">
            <div class="time-slots-container" id="timeSlotsContainer" style="min-height: 60px;">
                <option value="">Vui lòng chọn ngày</option>
                <!-- Time slot buttons will be dynamically inserted here -->

            </div>
        </div>
    </div>

    <button id="submitBooking" class="btn btn-primary w-100" style="margin-top:50px; min-height:60px;font-weight:bold;font-size: 24px;">Đặt lịch</button>
</div>
</div>
</div>

@push('scripts')
@include('script.booking');
@endpush

@endsection