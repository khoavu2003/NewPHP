@extends('layouts.admin')
@section('title', 'Danh Sách khách hàng')
@section('form')
@include('employee.add')
@endsection

@section('search')
@include('employee.search')
@endsection

@section('content')
<div class="container mt-4">
    <div id="userListSummary" class="mb-3">
        <p>Hiển thị từ <span id="start-index">1</span> đến <span id="end-index">10</span> trong tổng
            số <span id="total-employees">0</span> Nhân viên.</p>
    </div>
    <div class="pagination" id="pagination-top">

    </div>

    <!-- Pagination controls at the top -->
    <div class="pagination" id="pagination-top">

    </div>

    <div id="message" class="alert alert-danger" style="display:none;"></div>

    <table class="table table-bordered table-striped" id="employeeTable">
        <thead style="background-color: red">
            <tr style="color: white">
                <th>Tên nhân viên</th>
                <th>Email nhân viên</th>
                <th>Số điện thoại nhân viên</th>
                <th>Trạng thái</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>

        </tbody>
    </table>

    <div class="pagination" id="pagination-bottom">

    </div>

</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
@endsection
@push('scripts')
    @include('script.employee')
@endpush