@extends('layouts.admin')
@section('title', 'Danh Sách Người Dùng')
@section('form')
@endsection

@section('search')
@include('services.search')
@endsection

@section('content')
<div id="userListSummary" class="mb-3">
    <p>Hiển thị từ <span id="start-index">1</span> đến <span id="end-index">10</span> trong tổng số <span id="total-services">0</span> dịch vụ.</p>
</div>

<!-- Pagination controls at the top -->
<div class="pagination" id="pagination-top"></div>

<!-- Error message display -->
<div id="message" class="alert alert-danger" style="display:none;"></div>

<!-- User table -->
<table class="table table-bordered table-striped" id="serviceTable">
    <thead style="background-color: red">
        <tr style="color:white">
            <th><input type="checkbox" id="select-all" /></th>
            <th>Tên dịch vụ</th>
            <th>Ảnh</th>
            <th>Mô tả</th>
            <th>Giá</th>
            <th>Thời gian</th>
            <th>Hành Động</th>
        </tr>
    </thead>
    <tbody>

    </tbody>
</table>

<div class="pagination" id="pagination-bottom"></div>

@endsection
@push('scripts')
@include('script.services')
@endpush