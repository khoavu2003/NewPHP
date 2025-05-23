<!DOCTYPE html>
@extends('layouts.base')
@section('title','Lịch hẹn của tôi')
@section('content')
<style>
     body {
        font-family: Roboto, Helvetica, Arial, Verdana, sans-serif;
    }
    .card-header {
        background-color: #007bff;
        color: white;
        font-weight: bold;
        font-size: 1.5rem;
        text-align: center;
    }
    .table thead th {
        background-color: #e9ecef;
        font-weight: bold;
        text-align: center;
        vertical-align: middle;
    }
    .table tbody td {
        vertical-align: middle;
        text-align: center;
    }
    .badge {
        font-size: 0.9rem;
        padding: 0.5em 1em;
    }
    .loading-spinner {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100px;
    }
</style>
<div class="container my-5">
    <div class="card shadow-md">
        <div class="card-header">
            lịch hẹn của tôi
        </div>
        <div class="card-body">
            <p class="text-muted text-center mb-4" style="font: size 1.25rem;">
                Danh sách các lịch hẹn bạn đã đặt
            </p>
            <div id="bookingList" class="loading-spinner">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Đang tải...</span>
                </div>
            </div>
        </div>

    </div>

</div>
@push('scripts')
@include('script.mybooking')
@endpush
@endsection