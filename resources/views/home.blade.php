<!DOCTYPE html>
@extends('layouts.base')

@section('title', 'Trang chủ')

@section('content')
<style>
    body {
        font-family: Roboto, Helvetica, Arial, Verdana, sans-serif;
    }
    .service-card {
        transition: transform 0.2s;
        height: 100%;
        max-width: 400px;
        display: flex;
        flex-direction: column;
    }
    .service-card:hover {
        transform: translateY(-5px);
        background-color: #DCDCDC;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }
    .card-title {
        font-size: 1.25rem;
        font-weight: bold;
        color: #343a40;
    }
    .card-text {
        flex-grow: 1;
        color: #6c757d;
        text-align: justify;
    }
    .card-footer {
        background-color: transparent;
        border-top: none;
        padding: 1rem;
        text-align: center;
    }
    .btn-book {
        background-color: #000000;
        border-color: #007bff;
        font-weight: bold;
        color: white;
    }
    .btn-book:hover {
        background-color: #FF0033;
        border-color: #0056b3;
        color:white;
    }
    .price {
        font-size: 1.1rem;
        color: #28a745;
        font-weight: bold;
    }
    .service-image {
        width: 100%;
        height: 200px;
        padding: 10px;
        object-fit: cover;
        border-top-left-radius: 5px;
        border-top-right-radius: 5px;
    }
    .image-placeholder {
        width: 100%;
        height: 200px;
        background-color: #f8f9fa;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #6c757d;
        font-style: italic;
        border-top-left-radius: 5px;
        border-top-right-radius: 5px;
    }
</style>

<div class="container my-5">
    <h1 class="text-center mb-4" style="font-size: 2rem; font-weight: bold; color: #343a40;">
        Dịch vụ của chúng tôi
    </h1>
    <p class="text-center text-muted mb-5" style="font-size: 1.25rem;">
        Khám phá các dịch vụ chất lượng cao với giá cả hợp lý
    </p>
    <div class="row row-cols-1 row-cols-md-3 g-4">
        @forelse ($services as $service)
            <div class="col">
                <div class="card service-card shadow-sm">
                    @if ($service->image_url)
                        <img src="{{ $service->image_url }}" class="service-image" alt="{{ $service->service_name }}" onerror="this.onerror=null; this.src='https://via.placeholder.com/300x200?text=Không+có+ảnh';">
                    @else
                        <div class="image-placeholder">Không có ảnh</div>
                    @endif
                    <div class="card-body">
                        <h5 class="card-title">{{ $service->service_name }}</h5>
                        <p class="card-text">{{ $service->description }}</p>
                        <p class="price">{{ number_format($service->price, 0, ',', '.') }} VND</p>
                        <p class="card-text" style="font-weight: bold;">Thời gian: {{ $service->duration_minute }} phút</p>
                    </div>
                    <div class="card-footer">
                        <a href="/booking?service_id={{ $service->service_id }}" class="btn btn-book w-100">Đặt ngay</a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <p class="text-muted text-center">Hiện tại chưa có dịch vụ nào.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection