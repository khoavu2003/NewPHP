<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Không Có Quyền Truy Cập</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: linear-gradient(135deg, #e2e8f0, #f1f5f9);
            font-family: 'Inter', sans-serif;
            margin: 0;
            padding: 1rem;
        }
        .container {
            max-width: 28rem;
            background: white;
            border-radius: 1rem;
            box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            text-align: center;
        }
        .image-container {
            padding: 2rem;
            background: #f8fafc;
        }
        .error-image {
            max-width: 12rem;
            height: auto;
        }
        .error-code {
            font-size: 3rem;
            font-weight: bold;
            color: #dc2626;
            margin-bottom: 0.5rem;
        }
        .message {
            font-size: 1.25rem;
            color: #1f2937;
            margin-bottom: 1.5rem;
            padding: 0 1.5rem;
        }
        .button {
            display: inline-block;
            background: #2563eb;
            color: white;
            padding: 0.75rem 2rem;
            border-radius: 0.5rem;
            text-decoration: none;
            font-weight: 600;
            transition: background 0.3s ease, transform 0.2s ease;
            margin-bottom: 2rem;
        }
        .button:hover {
            background: #1d4ed8;
            transform: translateY(-2px);
        }
        @media (max-width: 640px) {
            .container {
                margin: 0 1rem;
            }
            .error-code {
                font-size: 2.5rem;
            }
            .message {
                font-size: 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="image-container">
            
        </div>
        <div class="error-code">{{ $statusCode ?? '403' }}</div>
        <p class="message">Bạn không có quyền truy cập. Vui lòng đăng nhập với tài khoản quản trị.</p>
        <a href="/" class="button">Quay về trang chủ</a>
    </div>
</body>
</html>