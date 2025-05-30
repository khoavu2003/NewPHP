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
        background-color: black;
        font-weight: bold;
        color: white;
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

    .pagination .active {
        background-color: red;
        color: white;
    }

    .pagination .disabled {
        color: #ccc;
        pointer-events: none;
    }

    .loading-spinner {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100px;
    }
</style>
<div class="container my-5">
    <h1 class="text-center mb-4" style="font-size:2rem;font-weight:bold;color: #343a40">
        Lịch hẹn của tôi
    </h1>
    <div class="card shadow-md">
        <div class="card-body">
            <div id="bookingsTable">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Ngày</th>
                            <th>Giờ bắt đầu</th>
                            <th>Giờ kết thúc</th>
                            <th>Dịch vụ</th>
                            <th>Trạng thái</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody id="bookingsBody">

                    </tbody>
                </table>
                <div id="pagination" class="d-flex justify-content-center"></div>
            </div>

        </div>
    </div>

</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
@push('scripts')
@include('script.mybooking')
@endpush
@endsection