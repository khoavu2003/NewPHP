    <!DOCTYPE html>
    <html lang="vi">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Tra cứu lịch sử sửa chữa</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
         <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <style>
            :root {
                --primary-color: #2563eb;
                --secondary-color: #64748b;
                --success-color: #10b981;
                --warning-color: #f59e0b;
                --danger-color: #ef4444;
                --dark-color: #1e293b;
                --light-color: #f8fafc;
            }

            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }

            body {
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                min-height: 100vh;
                color: var(--dark-color);
            }

            .main-container {
                min-height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 20px;
            }

            .content-wrapper {
                width: 100%;
                max-width: 1200px;
            }

            .header {
                text-align: center;
                margin-bottom: 40px;
            }

            .header h1 {
                color: white;
                font-size: 2.5rem;
                font-weight: 700;
                margin-bottom: 10px;
                text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            }

            .header p {
                color: rgba(255, 255, 255, 0.9);
                font-size: 1.1rem;
            }

            .search-card {
                background: rgba(255, 255, 255, 0.95);
                backdrop-filter: blur(10px);
                border: none;
                border-radius: 20px;
                box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
                padding: 40px;
                margin-bottom: 30px;
                transition: all 0.3s ease;
            }

            .search-card:hover {
                transform: translateY(-5px);
                box-shadow: 0 30px 60px rgba(0, 0, 0, 0.15);
            }

            .search-input-group {
                position: relative;
                margin-bottom: 20px;
            }

            .search-input {
                border: 2px solid #e2e8f0;
                border-radius: 12px;
                padding: 15px 20px 15px 50px;
                font-size: 1.1rem;
                transition: all 0.3s ease;
                background: white;
            }

            .search-input:focus {
                border-color: var(--primary-color);
                box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
                outline: none;
            }

            .search-icon {
                position: absolute;
                left: 18px;
                top: 50%;
                transform: translateY(-50%);
                color: var(--secondary-color);
                font-size: 1.2rem;
            }

            .search-btn {
                background: linear-gradient(135deg, var(--primary-color), #3b82f6);
                border: none;
                border-radius: 12px;
                padding: 15px 40px;
                font-size: 1.1rem;
                font-weight: 600;
                color: white;
                transition: all 0.3s ease;
                box-shadow: 0 4px 15px rgba(37, 99, 235, 0.3);
            }

            .search-btn:hover {
                background: linear-gradient(135deg, #1d4ed8, var(--primary-color));
                transform: translateY(-2px);
                box-shadow: 0 8px 25px rgba(37, 99, 235, 0.4);
            }

            .search-btn:active {
                transform: translateY(0);
            }

            .result-card {
                background: rgba(255, 255, 255, 0.95);
                backdrop-filter: blur(10px);
                border: none;
                border-radius: 20px;
                box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
                overflow: hidden;
                animation: slideInUp 0.5s ease;
            }

            @keyframes slideInUp {
                from {
                    opacity: 0;
                    transform: translateY(30px);
                }

                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            .result-header {
                background: linear-gradient(135deg, var(--primary-color), #3b82f6);
                color: white;
                padding: 25px 30px;
                position: relative;
                overflow: hidden;
            }

            .result-header::before {
                content: '';
                position: absolute;
                top: 0;
                right: 0;
                width: 100px;
                height: 100px;
                background: rgba(255, 255, 255, 0.1);
                border-radius: 50%;
                transform: translate(30px, -30px);
            }

            .result-header h3 {
                margin: 0;
                font-size: 1.5rem;
                font-weight: 700;
            }

            .vehicle-info {
                padding: 30px;
                background: white;
            }

            .star-rating {
                display: flex;
                gap: 5px;
            }

            .star {
                font-size: 1.2rem;
                color: #d1d5db;
                cursor: pointer;
                transition: color 0.2s ease;
            }

            .star:hover,
            .star.selected {
                color: var(--warning-color);
            }

            .rating-section {
                display: flex;
                align-items: center;
                gap: 10px;
                margin-top: 10px;
            }

            .rating-label {
                font-size: 0.9rem;
                color: var(--secondary-color);
            }

            .comment-input {
                display: none;
                margin-top: 10px;
            }

            .comment-input textarea {
                width: 100%;
                border: 1px solid #e2e8f0;
                border-radius: 8px;
                padding: 10px;
                font-size: 0.9rem;
                resize: vertical;
            }

            .comment-input button {
                margin-top: 10px;
                background: var(--primary-color);
                color: white;
                border: none;
                border-radius: 8px;
                padding: 8px 16px;
                font-size: 0.9rem;
                cursor: pointer;
            }

            .comment-input button:hover {
                background: #1d4ed8;
            }

            .comment-display {
                margin-top: 10px;
                font-size: 0.9rem;
                color: var(--dark-color);
                background: #f1f5f9;
                padding: 10px;
                border-radius: 8px;
            }

            .info-item {
                display: flex;
                align-items: center;
                padding: 15px 0;
                border-bottom: 1px solid #f1f5f9;
            }

            .info-item:last-child {
                border-bottom: none;
            }

            .info-icon {
                width: 40px;
                height: 40px;
                border-radius: 10px;
                display: flex;
                align-items: center;
                justify-content: center;
                margin-right: 15px;
                font-size: 1.2rem;
            }

            .info-icon.license {
                background: linear-gradient(135deg, var(--success-color), #22c55e);
                color: white;
            }

            .info-icon.model {
                background: linear-gradient(135deg, var(--warning-color), #fbbf24);
                color: white;
            }

            .info-content {
                flex: 1;
            }

            .info-label {
                font-size: 0.9rem;
                color: var(--secondary-color);
                margin-bottom: 5px;
            }

            .info-value {
                font-size: 1.1rem;
                font-weight: 600;
                color: var(--dark-color);
            }

            .history-section {
                background: #f8fafc;
                padding: 30px;
            }

            .history-title {
                display: flex;
                align-items: center;
                margin-bottom: 25px;
                font-size: 1.3rem;
                font-weight: 700;
                color: var(--dark-color);
            }

            .history-icon {
                width: 35px;
                height: 35px;
                border-radius: 8px;
                background: linear-gradient(135deg, var(--primary-color), #3b82f6);
                color: white;
                display: flex;
                align-items: center;
                justify-content: center;
                margin-right: 12px;
            }

            .history-item {
                background: white;
                border-radius: 12px;
                padding: 20px;
                margin-bottom: 15px;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
                transition: all 0.3s ease;
                border-left: 4px solid var(--primary-color);
            }

            .history-item:hover {
                transform: translateX(5px);
                box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            }

            .history-date {
                font-size: 0.9rem;
                color: var(--secondary-color);
                margin-bottom: 8px;
            }

            .history-service {
                font-size: 1rem;
                font-weight: 600;
                color: var(--dark-color);
                margin-bottom: 5px;
            }

            .history-cost {
                font-size: 1.1rem;
                font-weight: 700;
                color: var(--success-color);
            }

            .loading {
                text-align: center;
                padding: 40px;
                color: var(--secondary-color);
            }

            .loading i {
                font-size: 2rem;
                margin-bottom: 15px;
                animation: spin 1s linear infinite;
            }

            @keyframes spin {
                0% {
                    transform: rotate(0deg);
                }

                100% {
                    transform: rotate(360deg);
                }
            }

            .no-results {
                text-align: center;
                padding: 40px;
                color: var(--secondary-color);
            }

            .no-results i {
                font-size: 3rem;
                margin-bottom: 15px;
                color: var(--warning-color);
            }

            .fade-in {
                animation: fadeIn 0.5s ease;
            }

            @keyframes fadeIn {
                from {
                    opacity: 0;
                }

                to {
                    opacity: 1;
                }
            }

            /* Responsive */
            @media (max-width: 768px) {
                .header h1 {
                    font-size: 2rem;
                }

                .search-card {
                    padding: 25px;
                    margin: 0 10px 20px 10px;
                }

                .vehicle-info {
                    padding: 20px;
                }

                .history-section {
                    padding: 20px;
                }

                .result-header {
                    padding: 20px;
                }

                .info-item {
                    flex-direction: column;
                    align-items: flex-start;
                    text-align: left;
                }

                .info-icon {
                    margin-bottom: 10px;
                    margin-right: 0;
                }
            }
        </style>
    </head>

    <body>
        <div class="main-container">
            <div class="content-wrapper">
                <!-- Header -->
                <div class="header">
                    <h1><i class="fas fa-tools"></i> Tra cứu lịch sử sửa chữa</h1>
                    <p>Tra cứu nhanh chóng lịch sử bảo dưỡng và sửa chữa xe của bạn</p>
                </div>

                <!-- Search Form -->
                <div class="search-card">
                    <form id="searchForm">
                        <div class="search-input-group">
                            <i class="fas fa-search search-icon"></i>
                            <input type="text" id="searchInput" class="form-control search-input"
                                placeholder="Nhập số điện thoại hoặc biển số xe (VD: 0123456789 hoặc 30A-12345)" required>

                        </div>
                        <div class="text-center">
                            <button type="submit" class="search-btn">
                                <i class="fas fa-search"></i> Tìm kiếm
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Results -->
                <div id="resultContainer" style="display: none;">
                    <!-- Loading -->
                    <div id="loadingState" class="result-card loading">
                        <i class="fas fa-spinner"></i>
                        <p>Đang tìm kiếm...</p>
                    </div>

                    <!-- No Results -->
                    <div id="noResults" class="result-card no-results" style="display: none;">
                        <i class="fas fa-exclamation-triangle"></i>
                        <h4>Không tìm thấy kết quả</h4>
                        <p>Vui lòng kiểm tra lại số điện thoại hoặc biển số xe</p>
                    </div>

                    <!-- Results Content -->
                    <div id="resultsContent" class="result-card" style="display: none;">
                        <div class="result-header">
                            <h3><i class="fas fa-car"></i> Thông tin lịch sử sửa chữa</h3>
                        </div>
                        <div class="history-section">
                            <div class="history-title">
                                <div class="history-icon">
                                    <i class="fas fa-history"></i>
                                </div>
                                Lịch sử sửa chữa
                            </div>
                            <div id="bookingHistory"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        @include('script.history')
    </body>

    </html>