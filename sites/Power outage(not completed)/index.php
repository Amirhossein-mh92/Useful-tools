<?php
// ============================================================
// ۱. مدیریت فایل JSON
// ============================================================
$jsonFile = 'locations.json';

function getLocations() {
    global $jsonFile;
    if (file_exists($jsonFile)) {
        $content = file_get_contents($jsonFile);
        $data = json_decode($content, true);
        return is_array($data) ? $data : [];
    }
    return [];
}

function saveLocations($locations) {
    global $jsonFile;
    file_put_contents($jsonFile, json_encode($locations, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}

// ============================================================
// ۲. پردازش AJAX
// ============================================================
$action = isset($_POST['action']) ? $_POST['action'] : (isset($_GET['action']) ? $_GET['action'] : '');

if ($action === 'save') {
    header('Content-Type: application/json');
    $name = trim($_POST['name'] ?? '');
    $data = json_decode($_POST['data'] ?? '{}', true);
    
    if (empty($name) || empty($data)) {
        echo json_encode(['success' => false, 'message' => 'نام یا داده‌ها خالی است']);
        exit;
    }
    
    $locations = getLocations();
    foreach ($locations as $loc) {
        if ($loc['name'] === $name) {
            echo json_encode(['success' => false, 'message' => 'مکانی با این نام قبلاً ذخیره شده است']);
            exit;
        }
    }
    
    $locations[] = [
        'id' => time() . rand(100, 999),
        'name' => $name,
        'data' => $data
    ];
    saveLocations($locations);
    echo json_encode(['success' => true, 'message' => 'مکان "' . $name . '" ذخیره شد']);
    exit;
}

if ($action === 'load') {
    header('Content-Type: application/json');
    $id = $_POST['id'] ?? $_GET['id'] ?? 0;
    $locations = getLocations();
    foreach ($locations as $loc) {
        if ($loc['id'] == $id) {
            echo json_encode(['success' => true, 'data' => $loc['data']]);
            exit;
        }
    }
    echo json_encode(['success' => false, 'message' => 'مکان پیدا نشد']);
    exit;
}

if ($action === 'delete') {
    header('Content-Type: application/json');
    $id = $_POST['id'] ?? $_GET['id'] ?? 0;
    $locations = getLocations();
    $newLocations = array_filter($locations, function($loc) use ($id) {
        return $loc['id'] != $id;
    });
    if (count($newLocations) !== count($locations)) {
        saveLocations(array_values($newLocations));
        echo json_encode(['success' => true, 'message' => 'مکان حذف شد']);
    } else {
        echo json_encode(['success' => false, 'message' => 'مکان پیدا نشد']);
    }
    exit;
}

if ($action === 'list') {
    header('Content-Type: application/json');
    $locations = getLocations();
    echo json_encode(['success' => true, 'locations' => $locations]);
    exit;
}

// ============================================================
// ۳. Manifest + Service Worker
// ============================================================
if (isset($_GET['manifest'])) {
    header('Content-Type: application/json');
    echo json_encode([
        'name' => 'استعلام خاموشی برق مازندران',
        'short_name' => 'خاموشی برق',
        'description' => 'استعلام خاموشی برق مازندران',
        'start_url' => './',
        'display' => 'standalone',
        'background_color' => '#0f0c29',
        'theme_color' => '#f7c948',
        'icons' => [
            [
                'src' => "data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 512 512'%3E%3Crect width='512' height='512' rx='50' fill='%230f0c29'/%3E%3Ccircle cx='256' cy='256' r='80' fill='%23f7c948'/%3E%3Cpath d='M220 150l100 60-100 60z' fill='%231a163a'/%3E%3Cpath d='M256 100v350' stroke='%23f7c948' stroke-width='8' stroke-linecap='round'/%3E%3C/svg%3E",
                'sizes' => '512x512',
                'type' => 'image/svg+xml',
                'purpose' => 'any maskable'
            ]
        ]
    ]);
    exit;
}

if (isset($_GET['sw'])) {
    header('Content-Type: application/javascript');
    echo <<<SW
const CACHE_NAME = 'outage-cache-v1';
const urlsToCache = [
    './',
    '?manifest=1'
];

self.addEventListener('install', event => {
    event.waitUntil(
        caches.open(CACHE_NAME)
            .then(cache => cache.addAll(urlsToCache))
    );
});

self.addEventListener('fetch', event => {
    event.respondWith(
        caches.match(event.request)
            .then(response => response || fetch(event.request))
    );
});
SW;
    exit;
}

// ============================================================
// ۴. مقداردهی اولیه
// ============================================================
$locations = getLocations();
$cacheBuster = time();
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <title>استعلام خاموشی برق مازندران</title>
    
    <link rel="manifest" href="?manifest=1&t=<?php echo $cacheBuster; ?>" />
    <link rel="icon" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 512 512'%3E%3Crect width='512' height='512' rx='50' fill='%230f0c29'/%3E%3Ccircle cx='256' cy='256' r='80' fill='%23f7c948'/%3E%3Cpath d='M220 150l100 60-100 60z' fill='%231a163a'/%3E%3Cpath d='M256 100v350' stroke='%23f7c948' stroke-width='8' stroke-linecap='round'/%3E%3C/svg%3E" />
    <meta name="theme-color" content="#0f0c29" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent" />
    
    <link href="https://cdn.jsdelivr.net/gh/rastikerdar/vazirmatn@v33.003/Vazirmatn-font-face.css" rel="stylesheet" />
    
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Vazirmatn', sans-serif;
            background: linear-gradient(135deg, #0f0c29, #302b63, #24243e);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(18px);
            -webkit-backdrop-filter: blur(18px);
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 32px;
            padding: 30px 28px;
            max-width: 820px;
            width: 100%;
            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.7);
            animation: fadeUp 0.6s ease-out;
        }
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .header { text-align: center; margin-bottom: 20px; }
        .header .icon {
            font-size: 36px;
            background: rgba(255, 215, 0, 0.15);
            width: 60px;
            height: 60px;
            line-height: 60px;
            border-radius: 50%;
            display: inline-block;
            margin-bottom: 6px;
            border: 1px solid rgba(255, 215, 0, 0.25);
        }
        .header h1 { color: #fff; font-size: 22px; font-weight: 700; }
        .header p { color: rgba(255, 255, 255, 0.5); font-size: 13px; }

        .toolbar {
            display: flex;
            gap: 10px;
            margin-bottom: 15px;
            flex-wrap: wrap;
        }
        .toolbar .btn {
            padding: 9px 16px;
            font-family: 'Vazirmatn', sans-serif;
            font-size: 13px;
            font-weight: 600;
            border: none;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 6px;
            flex: 1;
            justify-content: center;
            min-width: 80px;
        }
        .btn-primary {
            background: linear-gradient(135deg, #4CAF50, #2E7D32);
            color: #fff;
            box-shadow: 0 4px 15px rgba(76, 175, 80, 0.3);
        }
        .btn-primary:hover { transform: scale(1.02); }
        .btn-orange {
            background: linear-gradient(135deg, #FF9800, #E65100);
            color: #fff;
            box-shadow: 0 4px 15px rgba(255, 152, 0, 0.3);
        }
        .btn-orange:hover { transform: scale(1.02); }
        .btn:active { transform: scale(0.95); }

        .location-list {
            background: rgba(255, 255, 255, 0.04);
            border-radius: 14px;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid rgba(255, 255, 255, 0.06);
            max-height: 180px;
            overflow-y: auto;
        }
        .location-list::-webkit-scrollbar { width: 4px; }
        .location-list::-webkit-scrollbar-thumb { background: #f7c948; border-radius: 10px; }
        .location-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 8px 12px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 10px;
            margin-bottom: 6px;
            transition: 0.2s;
            border: 1px solid transparent;
        }
        .location-item:hover {
            background: rgba(255, 255, 255, 0.08);
            border-color: rgba(247, 201, 72, 0.3);
        }
        .location-item .info { display: flex; flex-direction: column; gap: 2px; flex: 1; }
        .location-item .name { color: #fff; font-weight: 600; font-size: 14px; }
        .location-item .details { color: rgba(255, 255, 255, 0.45); font-size: 11px; }
        .location-item .actions { display: flex; gap: 6px; flex-shrink: 0; }
        .location-item .actions button {
            padding: 4px 10px;
            font-size: 12px;
            font-family: 'Vazirmatn', sans-serif;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: 0.2s;
            font-weight: 500;
        }
        .location-item .actions .search-btn {
            background: rgba(247, 201, 72, 0.2);
            color: #f7c948;
        }
        .location-item .actions .search-btn:hover {
            background: rgba(247, 201, 72, 0.35);
        }
        .location-item .actions .delete-btn {
            background: rgba(244, 67, 54, 0.2);
            color: #ef5350;
        }
        .location-item .actions .delete-btn:hover {
            background: rgba(244, 67, 54, 0.35);
        }
        .empty-list {
            color: rgba(255, 255, 255, 0.3);
            text-align: center;
            padding: 20px;
            font-size: 14px;
        }

        .form-group { margin-bottom: 12px; }
        .form-group label {
            display: block;
            color: rgba(255, 255, 255, 0.8);
            font-size: 13px;
            font-weight: 500;
            margin-bottom: 4px;
            padding-right: 4px;
        }
        .form-control {
            width: 100%;
            padding: 10px 14px;
            font-family: 'Vazirmatn', sans-serif;
            font-size: 14px;
            background: rgba(255, 255, 255, 0.07);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 12px;
            color: #fff;
            transition: all 0.25s ease;
            outline: none;
            -webkit-appearance: none;
            appearance: none;
        }
        .form-control:focus {
            border-color: #f7c948;
            background: rgba(255, 255, 255, 0.12);
            box-shadow: 0 0 0 4px rgba(247, 201, 72, 0.15);
        }
        .form-control::placeholder { color: rgba(255, 255, 255, 0.3); }
        select.form-control {
            cursor: pointer;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'%3E%3Cpath d='M1 1l5 5 5-5' stroke='white' stroke-width='1.5' fill='none' stroke-linecap='round'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: left 14px center;
            padding-left: 38px;
        }
        select.form-control option { background: #1e1b3a; color: #fff; }
        
        .date-wrapper {
            position: relative;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .date-wrapper .form-control {
            flex: 1;
            cursor: pointer;
            direction: ltr;
            text-align: center;
        }
        .date-wrapper .calendar-btn {
            background: rgba(247, 201, 72, 0.15);
            border: 1px solid rgba(247, 201, 72, 0.2);
            border-radius: 10px;
            padding: 8px 12px;
            cursor: pointer;
            font-size: 18px;
            color: #f7c948;
            transition: 0.3s;
            flex-shrink: 0;
            line-height: 1;
        }
        .date-wrapper .calendar-btn:hover {
            background: rgba(247, 201, 72, 0.25);
        }
        
        .radio-group {
            display: flex;
            gap: 20px;
            background: rgba(255, 255, 255, 0.05);
            padding: 8px 14px;
            border-radius: 12px;
        }
        .radio-group label {
            color: rgba(255, 255, 255, 0.8);
            font-size: 14px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .radio-group label.inactive { color: rgba(255, 255, 255, 0.4); }
        .radio-group input[type="radio"] {
            accent-color: #f7c948;
            width: 16px;
            height: 16px;
            cursor: pointer;
        }
        
        .btn-submit {
            width: 100%;
            padding: 14px;
            margin-top: 6px;
            font-family: 'Vazirmatn', sans-serif;
            font-size: 17px;
            font-weight: 700;
            color: #1a163a;
            background: linear-gradient(135deg, #f7c948, #f5a623);
            border: none;
            border-radius: 14px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 8px 25px rgba(247, 201, 72, 0.25);
        }
        .btn-submit:hover {
            transform: scale(1.01);
            box-shadow: 0 12px 35px rgba(247, 201, 72, 0.4);
        }
        .btn-submit:active { transform: scale(0.97); }
        
        .footer {
            margin-top: 16px;
            text-align: center;
            color: rgba(255, 255, 255, 0.25);
            font-size: 12px;
            border-top: 1px solid rgba(255, 255, 255, 0.06);
            padding-top: 12px;
        }
        .date-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }
        @media (max-width: 480px) {
            .date-row { grid-template-columns: 1fr; }
            .toolbar .btn { font-size: 12px; padding: 8px 12px; min-width: 60px; }
            .location-item { flex-wrap: wrap; gap: 6px; }
            .location-item .actions { width: 100%; justify-content: flex-end; }
        }
        .hidden-field { display: none !important; }
        .toggle-group { transition: all 0.4s ease; }

        .modal-overlay {
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(0,0,0,0.7);
            backdrop-filter: blur(8px);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 999;
            padding: 20px;
        }
        .modal-overlay.active { display: flex; animation: fadeIn 0.3s ease; }
        @keyframes fadeIn {
            from { opacity: 0; transform: scale(0.9); }
            to { opacity: 1; transform: scale(1); }
        }
        .modal {
            background: #1e1b3a;
            border-radius: 24px;
            padding: 30px;
            max-width: 450px;
            width: 100%;
            border: 1px solid rgba(255,255,255,0.1);
            box-shadow: 0 30px 60px rgba(0,0,0,0.8);
        }
        .modal h3 { color: #fff; font-size: 20px; margin-bottom: 6px; }
        .modal p { color: rgba(255,255,255,0.5); font-size: 14px; margin-bottom: 18px; }
        .modal .form-control { margin-bottom: 12px; }
        .modal .modal-actions { display: flex; gap: 10px; margin-top: 6px; }
        .modal .modal-actions button {
            flex: 1;
            padding: 12px;
            font-family: 'Vazirmatn', sans-serif;
            font-size: 15px;
            font-weight: 600;
            border: none;
            border-radius: 12px;
            cursor: pointer;
            transition: 0.3s;
        }
        .modal .modal-actions .confirm {
            background: linear-gradient(135deg, #4CAF50, #2E7D32);
            color: #fff;
        }
        .modal .modal-actions .cancel {
            background: rgba(255,255,255,0.08);
            color: rgba(255,255,255,0.6);
        }
        .modal .modal-actions button:hover { transform: scale(1.02); }

        .toast {
            position: fixed;
            bottom: 30px;
            left: 50%;
            transform: translateX(-50%) translateY(100px);
            background: rgba(0,0,0,0.85);
            color: #fff;
            padding: 12px 28px;
            border-radius: 16px;
            font-family: 'Vazirmatn', sans-serif;
            font-size: 14px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.1);
            opacity: 0;
            transition: all 0.5s ease;
            pointer-events: none;
            z-index: 9999;
        }
        .toast.show { opacity: 1; transform: translateX(-50%) translateY(0); }
        .toast.success { border-color: #4CAF50; }
        .toast.info { border-color: #2196F3; }
        .toast.error { border-color: #f44336; }

        /* ===== تقویم ===== */
        .persian-calendar {
            position: fixed;
            background: #1e1b3a;
            border: 1px solid rgba(255,255,255,0.15);
            border-radius: 16px;
            padding: 15px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.9);
            z-index: 10000;
            display: none;
            width: 290px;
            max-width: 90vw;
            backdrop-filter: blur(20px);
        }
        .persian-calendar .cal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
            color: #fff;
            font-weight: 600;
        }
        .persian-calendar .cal-header button {
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 8px;
            color: #fff;
            padding: 4px 12px;
            cursor: pointer;
            font-size: 16px;
            transition: 0.3s;
        }
        .persian-calendar .cal-header button:hover {
            background: rgba(247, 201, 72, 0.2);
        }
        .persian-calendar .cal-header .cal-month-year {
            font-size: 14px;
            color: #f7c948;
        }
        .persian-calendar table {
            width: 100%;
            border-collapse: collapse;
            direction: rtl;
        }
        .persian-calendar th {
            color: rgba(255,255,255,0.4);
            font-weight: 400;
            font-size: 12px;
            padding: 4px;
            text-align: center;
        }
        .persian-calendar td {
            text-align: center;
            padding: 4px;
            cursor: pointer;
            color: rgba(255,255,255,0.7);
            border-radius: 8px;
            transition: 0.2s;
            font-size: 14px;
        }
        .persian-calendar td:hover {
            background: rgba(247, 201, 72, 0.15);
            color: #fff;
        }
        .persian-calendar td.selected {
            background: rgba(247, 201, 72, 0.25);
            color: #f7c948;
            font-weight: 600;
        }
        .persian-calendar td.empty {
            color: rgba(255,255,255,0.1);
            cursor: default;
        }
        .persian-calendar td.empty:hover {
            background: transparent;
        }
        .persian-calendar .cal-footer {
            margin-top: 10px;
            display: flex;
            justify-content: space-between;
            gap: 6px;
        }
        .persian-calendar .cal-footer button {
            flex: 1;
            padding: 6px 8px;
            font-family: 'Vazirmatn', sans-serif;
            font-size: 12px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: 0.3s;
            font-weight: 500;
        }
        .persian-calendar .cal-footer .today-btn {
            background: rgba(247, 201, 72, 0.15);
            color: #f7c948;
        }
        .persian-calendar .cal-footer .today-btn:hover {
            background: rgba(247, 201, 72, 0.25);
        }
        .persian-calendar .cal-footer .clear-btn {
            background: rgba(244, 67, 54, 0.15);
            color: #ef5350;
        }
        .persian-calendar .cal-footer .clear-btn:hover {
            background: rgba(244, 67, 54, 0.25);
        }
        .persian-calendar .cal-footer .close-btn {
            background: rgba(255,255,255,0.05);
            color: rgba(255,255,255,0.5);
        }
        .persian-calendar .cal-footer .close-btn:hover {
            background: rgba(255,255,255,0.1);
        }
        
        /* ===== لودینگ ===== */
        .loading-overlay {
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(0,0,0,0.5);
            backdrop-filter: blur(4px);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 9998;
        }
        .loading-overlay.active { display: flex; }
        .spinner {
            width: 50px;
            height: 50px;
            border: 4px solid rgba(255,255,255,0.1);
            border-top-color: #f7c948;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>
</head>
<body>

<!-- ===== لودینگ ===== -->
<div class="loading-overlay" id="loadingOverlay">
    <div class="spinner"></div>
</div>

<!-- ===== مودال ذخیره ===== -->
<div class="modal-overlay" id="saveModal">
    <div class="modal">
        <h3>📌 ذخیره مکان</h3>
        <p>یک نام برای این مکان انتخاب کنید تا بعداً سریع‌تر به آن دسترسی داشته باشید.</p>
        <input type="text" id="locationNameInput" class="form-control" placeholder="مثلاً: خانه، اداره، مدرسه، ..." />
        <div class="modal-actions">
            <button class="confirm" onclick="confirmSaveLocation()">✅ ذخیره</button>
            <button class="cancel" onclick="closeSaveModal()">لغو</button>
        </div>
    </div>
</div>

<!-- ===== کارت اصلی ===== -->
<div class="card">
    <div class="header">
        <div class="icon">⚡</div>
        <h1>استعلام خاموشی برق</h1>
        <p>استان مازندران &bull; شرکت توزیع نیروی برق</p>
    </div>

    <div class="toolbar">
        <button class="btn btn-primary" onclick="openSaveModal()">➕ ذخیره مکان</button>
        <button class="btn btn-orange" id="installBtn" style="display:none;" onclick="installApp()">📲 نصب برنامه</button>
    </div>

    <div class="location-list" id="locationList">
        <div class="empty-list">⏳ در حال بارگذاری...</div>
    </div>

    <form action="https://khamooshi.maztozi.ir/" method="POST" target="_blank" id="mainForm">
        <input type="hidden" name="__VIEWSTATE" value="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" />
        <input type="hidden" name="__EVENTVALIDATION" value="/wEdAEKCYFHB8FXIM9ZPEIHHWdW9Piv65ZntIkjSmoihBMIOVAm/tOtIg5OQPHxo80A2lE78iscQAbpxQv9TPlQf8H+dtWFVq4eZvz1PTxjHTQ/sPCMRRzERuszqbE5jy0hzaFE0Pyt1PGgZQsYIQZ6EB72qyadl/FogqIS4vbNvxUD6syV8u5DiBJQG6UDn592WixbGslO18TNjsuvy6S3qK6QCAn9Q4HjvQYbAkUmF6EabHpZlu3RbN4SLYUjyOSNTX6gjEkIBaqfua97BKb5fyZwuxKvYjTSZRBciqAnEqYb5AwvfqViVKx0iU+UZCXQdeQOOb3LsUQ3w24N48Q66l8ZsAB9IoAZl9TwbtcWjKJNdBneuA+mIeITSRgYB3CiM9b/URK7eNRcHhJoJUpyS2UQdelC9wALUrELMBpV8BFXuHZoAubCOsyEi+LE3RLQUI/1FP2xcv3VLKaA+ljcByxErpugDmEr1h+AZ8SWahWyeDVVHsuWDZphBti9/BFt7c80nopgXTOeJ/Pd0+jDYrCLcbukiNJFOLzmz1fj6gjj8TiciRFTbTcILG8eh0AYAbzAUW/Ab8/KgD3WgUwgAU01n9PDismm5rfvTX8v2U0eA17ocj8xhMsYGC+M9yWCg2engfvY+RBFEVQ5MRib0JE1V/B1TJ1m+MknYd6E86I3F9l6TqtHX0CGntIJOeT7q9eBjmGvfIFsYBDfTxdETXGAqi02+vf/vyIClyLC4p5kdcR8Nd2BtTe9cJFkZ9tRtgEHoCpNT6exWNA19T350g+kSVDaOjJgv7w+Yagj/CGo5ZS0GPRDmp3Q+uM4DdfqPkcRCZIbCRu+yt9k/cijND7B719vN2RsbkYp9m8adRLj0ToOGiR/s/syTh+vlgiGk0D0lxWcx155teYWybyxn0e5ZFyOpsoEYgnZRCtHgOPDl0VXr1G0peQjgueIs+BoGDkp4ahysklvv84C+fDOweQKrCQqqIWdI++RlIlMhSRsUGEwgTIZrlJfBoeqriUULAHcVjtW892A9lnAJl/lTVsFbjA2T92qTRqBghWdEcWZibT/k0JuUOnhkWq7kHA2QyoNQwwsdvk9YeEP3r/IPfI/EWgmDIMtGBD0Y3sRDS9qr4jqytJnt7eHPu/1W5ZaDBeJzuUC4IEYaVF2AoVsTEfTbCWlJoUhsqgKHsYJ+WAmfbbLIbWRRxmEmaMzYiF2c9//O5SlA1z6u9wbuXkS2+EahgcnPmWyxCgBX10uM5qFeNL1levlXNpu0DuclTX08jEM3aJxCHdHDHQeckpe/yiswetEU/OToAzmzzuNN4Oa79vhVTbQrZ6Dh9TUwr6Rjzlbag7tBHw+/7mSjaq4CCTPspStN+AFm27krVLT00XChifKVjKv6fMoB3GrtUXs4SSgRD39m1ZCR/r0Y15VeXGcTmHE4OUxmCPDO+FnSkGTlog==" />
        <input type="hidden" name="__EVENTTARGET" value="" />
        <input type="hidden" name="__EVENTARGUMENT" value="" />
        <input type="hidden" name="__LASTFOCUS" value="" />
        <input type="hidden" name="ctl00$ScriptManager1" value="ctl00$ScriptManager1|ctl00$ContentPlaceHolder1$btnSearchOutage" />
        <input type="hidden" name="ctl00$ContentPlaceHolder1$upOutage" value="ctl00$ContentPlaceHolder1$upOutage" />

        <div class="form-group">
            <label>روش جستجو</label>
            <div class="radio-group">
                <label id="lblAddress" class="active">
                    <input type="radio" name="ctl00$ContentPlaceHolder1$outage" value="rbIsAddress" checked id="radioAddress" />
                    مکان
                </label>
                <label id="lblCode" class="inactive">
                    <input type="radio" name="ctl00$ContentPlaceHolder1$outage" value="rbIsSubscriberCode" id="radioCode" />
                    کد اشتراک
                </label>
            </div>
        </div>

        <div class="form-group toggle-group" id="subscriberCodeGroup" style="display:none;">
            <label>کد اشتراک (۱۲ رقمی)</label>
            <input type="text" name="ctl00$ContentPlaceHolder1$txtSubscriberCode" id="txtSubscriberCode" class="form-control" placeholder="مثلاً ۹۹۰۰۹۰۳۴۴" />
        </div>

        <div id="locationGroup">
            <div class="form-group">
                <label>شهرستان</label>
                <select name="ctl00$ContentPlaceHolder1$ddlCity" class="form-control" id="ddlCity">
                    <option value="-1" selected>-- انتخاب نمایید --</option>
                    <option value="990090344">آمل</option>
                    <option value="990090345">بابل</option>
                    <option value="990090351">بابلسر</option>
                    <option value="990090348">بهشهر</option>
                    <option value="990090347">جويبار</option>
                    <option value="1">ساري</option>
                    <option value="990090349">سوادكوه شمالي</option>
                    <option value="990830068">سوادکوه</option>
                    <option value="339250577">سيمرغ</option>
                    <option value="990090941">فريدون کنار</option>
                    <option value="990090350">قائمشهر</option>
                    <option value="990090940">گلوگاه</option>
                    <option value="990601365">مياندرود</option>
                    <option value="990090346">نكا</option>
                </select>
            </div>

            <div class="form-group">
                <label>امور برق</label>
                <select name="ctl00$ContentPlaceHolder1$ddlArea" class="form-control" id="ddlArea">
                    <option value="-1" selected>-- انتخاب نمایید --</option>
                    <option value="2">جنوب ساري</option>
                    <option value="3">شمال ساري</option>
                    <option value="4">كياسر</option>
                    <option value="5">محمد آباد</option>
                    <option value="6">برق امره</option>
                    <option value="7">مياندورود</option>
                    <option value="13">زرگرشهر</option>
                    <option value="14">نكا</option>
                    <option value="21">گلوگاه</option>
                    <option value="22">رستمكلا</option>
                    <option value="23">زاغمرز</option>
                    <option value="25">گتاب</option>
                    <option value="26">بهشهر</option>
                    <option value="31">قائمشهر</option>
                    <option value="32">ناحيه 2 قائمشهر</option>
                    <option value="33">سيمرغ</option>
                    <option value="34">شبکه دو قائمشهر</option>
                    <option value="42">پل سفيد</option>
                    <option value="43">سوادكوه شمالي</option>
                    <option value="44">آلاشت</option>
                    <option value="46">سوادكوه</option>
                    <option value="51">جويبار</option>
                    <option value="52">كوهي خيل</option>
                    <option value="53">بهنمير</option>
                    <option value="61">بابل شمال</option>
                    <option value="62">بابل جنوب</option>
                    <option value="64">امير كلا</option>
                    <option value="65">بابل كنار</option>
                    <option value="66">بندپي شرقي</option>
                    <option value="67">خوشرودپی</option>
                    <option value="68">لاله آباد</option>
                    <option value="71">آمل غرب</option>
                    <option value="72">دشت سر</option>
                    <option value="73">رينه</option>
                    <option value="74">دابودشت</option>
                    <option value="75">امامزاده عبدالله</option>
                    <option value="76">آمل شرق</option>
                    <option value="84">هزار جریب</option>
                    <option value="85">بابلسر</option>
                    <option value="86">فريدونكنار</option>
                    <option value="87">فرح آباد</option>
                </select>
            </div>

            <div class="form-group">
                <label>آدرس (اختیاری)</label>
                <input type="text" name="ctl00$ContentPlaceHolder1$txtAddress" id="txtAddress" class="form-control" placeholder="مثلاً خیابان اصلی، نبش فلان کوچه" />
            </div>
        </div>

        <!-- ===== تاریخ با تقویم (خالی در ابتدا) ===== -->
        <div class="form-group toggle-group" id="dateGroup">
            <label>بازه تاریخ (اختیاری - به شمسی)</label>
            <div class="date-row">
                <div class="date-wrapper">
                    <input type="text" name="ctl00$ContentPlaceHolder1$txtPDateFrom" id="txtDateFrom" class="form-control" placeholder="از تاریخ" value="" />
                    <button type="button" class="calendar-btn" onclick="openCalendar('txtDateFrom')">📅</button>
                </div>
                <div class="date-wrapper">
                    <input type="text" name="ctl00$ContentPlaceHolder1$txtPDateTo" id="txtDateTo" class="form-control" placeholder="تا تاریخ" value="" />
                    <button type="button" class="calendar-btn" onclick="openCalendar('txtDateTo')">📅</button>
                </div>
            </div>
        </div>

        <button type="submit" name="ctl00$ContentPlaceHolder1$btnSearchOutage" class="btn-submit" id="searchBtn">
            ⚡ جستجوی خاموشی‌ها
        </button>

        <div class="footer">
            با کلیک بر روی دکمه، به سایت اصلی شرکت برق مازندران هدایت می‌شوید.
        </div>
    </form>
</div>

<!-- ===== تقویم شمسی ===== -->
<div class="persian-calendar" id="persianCalendar">
    <div class="cal-header">
        <button type="button" onclick="changeMonth(-1)">‹</button>
        <span class="cal-month-year" id="calMonthYear"></span>
        <button type="button" onclick="changeMonth(1)">›</button>
    </div>
    <table>
        <thead>
            <tr>
                <th>ش</th><th>ی</th><th>د</th><th>س</th><th>چ</th><th>پ</th><th>ج</th>
            </tr>
        </thead>
        <tbody id="calBody"></tbody>
    </table>
    <div class="cal-footer">
        <button type="button" class="today-btn" onclick="selectToday()">امروز</button>
        <button type="button" class="clear-btn" onclick="clearDate()">پاک کردن</button>
        <button type="button" class="close-btn" onclick="closeCalendar()">بستن</button>
    </div>
</div>

<div id="toast" class="toast"></div>

<script>
// ============================================================
// ۱. تقویم شمسی
// ============================================================
let currentCalYear = 1404;
let currentCalMonth = 3;
let activeInput = null;
let calVisible = false;

const monthNames = ['فروردین', 'اردیبهشت', 'خرداد', 'تیر', 'مرداد', 'شهریور', 'مهر', 'آبان', 'آذر', 'دی', 'بهمن', 'اسفند'];

function isLeapYear(year) {
    return (year % 4) === 3;
}

function getDaysInMonth(year, month) {
    if (month < 6) return 31;
    if (month < 11) return 30;
    return isLeapYear(year) ? 30 : 29;
}

function getWeekDay(year, month, day) {
    const baseYear = 1400;
    let totalDays = 0;
    for (let y = baseYear; y < year; y++) {
        totalDays += isLeapYear(y) ? 366 : 365;
    }
    for (let m = 0; m < month; m++) {
        totalDays += getDaysInMonth(year, m);
    }
    totalDays += day - 1;
    return (3 + totalDays) % 7;
}

function openCalendar(inputId) {
    activeInput = document.getElementById(inputId);
    if (!activeInput) return;
    
    // بستن تقویم قبلی
    closeCalendar();
    
    const val = activeInput.value.trim();
    if (val && val.match(/^\d{4}\/\d{1,2}\/\d{1,2}$/)) {
        const parts = val.split('/');
        currentCalYear = parseInt(parts[0]);
        currentCalMonth = parseInt(parts[1]) - 1;
    } else {
        const now = new Date();
        currentCalYear = now.getFullYear() - 621;
        currentCalMonth = now.getMonth();
        if (currentCalMonth < 0) { currentCalMonth += 12; currentCalYear--; }
    }
    
    renderCalendar();
    const cal = document.getElementById('persianCalendar');
    cal.style.display = 'block';
    calVisible = true;
    
    // موقعیت‌یابی هوشمند
    const rect = activeInput.getBoundingClientRect();
    let left = rect.left + rect.width / 2 - 145;
    let top = rect.bottom + 8;
    
    if (left < 10) left = 10;
    if (left + 290 > window.innerWidth - 10) left = window.innerWidth - 300;
    if (top + 300 > window.innerHeight - 10) {
        top = rect.top - 310;
    }
    if (top < 10) top = 10;
    
    cal.style.left = left + 'px';
    cal.style.top = top + 'px';
}

function closeCalendar() {
    document.getElementById('persianCalendar').style.display = 'none';
    calVisible = false;
    activeInput = null;
}

function renderCalendar() {
    const year = currentCalYear;
    const month = currentCalMonth;
    const daysInMonth = getDaysInMonth(year, month);
    const startDay = getWeekDay(year, month, 1);
    
    document.getElementById('calMonthYear').textContent = monthNames[month] + ' ' + year;
    
    let html = '';
    let day = 1;
    for (let week = 0; week < 6; week++) {
        html += '<tr>';
        for (let wd = 0; wd < 7; wd++) {
            if (week === 0 && wd < startDay) {
                html += '<td class="empty"></td>';
            } else if (day > daysInMonth) {
                html += '<td class="empty"></td>';
            } else {
                const val = activeInput ? activeInput.value : '';
                const isSelected = (val === (year + '/' + String(month + 1).padStart(2, '0') + '/' + String(day).padStart(2, '0')));
                html += '<td class="' + (isSelected ? 'selected' : '') + '" onclick="selectDate(' + day + ')">' + day + '</td>';
                day++;
            }
        }
        html += '</tr>';
        if (day > daysInMonth) break;
    }
    document.getElementById('calBody').innerHTML = html;
}

function changeMonth(delta) {
    currentCalMonth += delta;
    if (currentCalMonth < 0) {
        currentCalMonth = 11;
        currentCalYear--;
    } else if (currentCalMonth > 11) {
        currentCalMonth = 0;
        currentCalYear++;
    }
    renderCalendar();
}

function selectDate(day) {
    if (!activeInput) return;
    const value = currentCalYear + '/' + String(currentCalMonth + 1).padStart(2, '0') + '/' + String(day).padStart(2, '0');
    activeInput.value = value;
    closeCalendar();
}

function selectToday() {
    if (!activeInput) return;
    const now = new Date();
    const year = now.getFullYear() - 621;
    const month = now.getMonth() + 1;
    const day = now.getDate();
    const value = year + '/' + String(month).padStart(2, '0') + '/' + String(day).padStart(2, '0');
    activeInput.value = value;
    closeCalendar();
}

function clearDate() {
    if (!activeInput) return;
    activeInput.value = '';
    closeCalendar();
}

// بستن تقویم با کلیک خارج
document.addEventListener('click', function(e) {
    if (calVisible) {
        const cal = document.getElementById('persianCalendar');
        const isClickInside = cal.contains(e.target) || 
                             e.target.closest('.calendar-btn') ||
                             e.target.closest('.date-wrapper');
        if (!isClickInside) {
            closeCalendar();
        }
    }
});

// ============================================================
// ۲. مدیریت رادیو باتن
// ============================================================
const radioAddress = document.getElementById('radioAddress');
const radioCode = document.getElementById('radioCode');
const locationGroup = document.getElementById('locationGroup');
const subscriberGroup = document.getElementById('subscriberCodeGroup');
const dateGroup = document.getElementById('dateGroup');
const lblAddress = document.getElementById('lblAddress');
const lblCode = document.getElementById('lblCode');

function toggleFields() {
    if (radioAddress.checked) {
        locationGroup.style.display = 'block';
        subscriberGroup.style.display = 'none';
        dateGroup.style.display = 'block';
        lblAddress.className = 'active';
        lblCode.className = 'inactive';
        locationGroup.querySelectorAll('input, select').forEach(el => el.disabled = false);
        dateGroup.querySelectorAll('input').forEach(el => el.disabled = false);
        const codeInput = subscriberGroup.querySelector('input');
        if (codeInput) codeInput.disabled = true;
    } else {
        locationGroup.style.display = 'none';
        subscriberGroup.style.display = 'block';
        dateGroup.style.display = 'none';
        lblAddress.className = 'inactive';
        lblCode.className = 'active';
        locationGroup.querySelectorAll('input, select').forEach(el => el.disabled = true);
        dateGroup.querySelectorAll('input').forEach(el => el.disabled = true);
        const codeInput = subscriberGroup.querySelector('input');
        if (codeInput) codeInput.disabled = false;
    }
}
radioAddress.addEventListener('change', toggleFields);
radioCode.addEventListener('change', toggleFields);
toggleFields();

// ============================================================
// ۳. مدیریت مکان‌ها
// ============================================================
function getLocations() {
    fetch('?action=list&t=<?php echo $cacheBuster; ?>')
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                renderList(data.locations);
            }
        })
        .catch(() => {
            document.getElementById('locationList').innerHTML = '<div class="empty-list">❌ خطا در ارتباط با سرور</div>';
        });
}

function renderList(locations) {
    const container = document.getElementById('locationList');
    if (!locations || locations.length === 0) {
        container.innerHTML = `<div class="empty-list">📭 هیچ مکانی ذخیره نشده است<br/><span style="font-size:12px;">با دکمه «➕ ذخیره مکان» شروع کنید</span></div>`;
        return;
    }
    let html = '';
    locations.forEach(loc => {
        const data = loc.data;
        let detail = '';
        if (data.method === 'address') {
            const cityEl = document.querySelector(`#ddlCity option[value="${data.city}"]`);
            const areaEl = document.querySelector(`#ddlArea option[value="${data.area}"]`);
            const cityText = cityEl ? cityEl.textContent : data.city;
            const areaText = areaEl ? areaEl.textContent : data.area;
            detail = `📍 ${cityText} | ${areaText}`;
            if (data.address) detail += ` | ${data.address}`;
        } else {
            detail = `🔑 کد اشتراک: ${data.subscriberCode}`;
        }
        if (data.dateFrom && data.dateTo) {
            detail += ` | 📅 ${data.dateFrom} تا ${data.dateTo}`;
        }
        html += `
            <div class="location-item">
                <div class="info">
                    <div class="name">🏠 ${loc.name}</div>
                    <div class="details">${detail}</div>
                </div>
                <div class="actions">
                    <button class="search-btn" onclick="loadAndSearch('${loc.id}')">🔍 جستجو</button>
                    <button class="delete-btn" onclick="deleteLocation('${loc.id}')">✕</button>
                </div>
            </div>
        `;
    });
    container.innerHTML = html;
}

// ============================================================
// ۴. بارگذاری و جستجوی خودکار
// ============================================================
function loadAndSearch(id) {
    document.getElementById('loadingOverlay').classList.add('active');
    
    fetch('?action=load', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'id=' + id
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            applyLocationData(data.data);
            showToast('🏠 مکان بارگذاری شد، در حال جستجو...', 'success');
            setTimeout(() => {
                document.getElementById('mainForm').submit();
                document.getElementById('loadingOverlay').classList.remove('active');
            }, 500);
        } else {
            showToast('❌ ' + data.message, 'error');
            document.getElementById('loadingOverlay').classList.remove('active');
        }
    })
    .catch(() => {
        showToast('❌ خطا در ارتباط با سرور', 'error');
        document.getElementById('loadingOverlay').classList.remove('active');
    });
}

function deleteLocation(id) {
    if (!confirm('آیا از حذف این مکان مطمئن هستید؟')) return;
    fetch('?action=delete', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'id=' + id
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            showToast('🗑️ ' + data.message, 'info');
            getLocations();
        } else {
            showToast('❌ ' + data.message, 'error');
        }
    })
    .catch(() => showToast('❌ خطا در ارتباط با سرور', 'error'));
}

function getCurrentLocationData() {
    return {
        city: document.getElementById('ddlCity').value,
        area: document.getElementById('ddlArea').value,
        address: document.getElementById('txtAddress').value,
        dateFrom: document.getElementById('txtDateFrom').value,
        dateTo: document.getElementById('txtDateTo').value,
        method: radioAddress.checked ? 'address' : 'code',
        subscriberCode: document.getElementById('txtSubscriberCode').value
    };
}

function applyLocationData(data) {
    if (data.city) document.getElementById('ddlCity').value = data.city;
    if (data.area) document.getElementById('ddlArea').value = data.area;
    if (data.address) document.getElementById('txtAddress').value = data.address;
    if (data.dateFrom) document.getElementById('txtDateFrom').value = data.dateFrom;
    if (data.dateTo) document.getElementById('txtDateTo').value = data.dateTo;
    if (data.subscriberCode) document.getElementById('txtSubscriberCode').value = data.subscriberCode;
    if (data.method === 'code') {
        radioCode.checked = true;
    } else {
        radioAddress.checked = true;
    }
    toggleFields();
}

// ============================================================
// ۵. مودال ذخیره
// ============================================================
let tempLocationData = null;

function openSaveModal() {
    tempLocationData = getCurrentLocationData();
    if (tempLocationData.method === 'address') {
        if (tempLocationData.city === '-1' || tempLocationData.area === '-1') {
            showToast('❌ لطفاً شهرستان و امور برق را انتخاب کنید', 'error');
            return;
        }
    } else {
        if (!tempLocationData.subscriberCode || tempLocationData.subscriberCode.length < 5) {
            showToast('❌ لطفاً کد اشتراک معتبر وارد کنید', 'error');
            return;
        }
    }
    document.getElementById('locationNameInput').value = '';
    document.getElementById('saveModal').classList.add('active');
    setTimeout(() => document.getElementById('locationNameInput').focus(), 100);
}

function closeSaveModal() {
    document.getElementById('saveModal').classList.remove('active');
    tempLocationData = null;
}

function confirmSaveLocation() {
    const name = document.getElementById('locationNameInput').value.trim();
    if (!name) {
        showToast('❌ لطفاً یک نام برای مکان وارد کنید', 'error');
        return;
    }
    fetch('?action=save', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'name=' + encodeURIComponent(name) + '&data=' + encodeURIComponent(JSON.stringify(tempLocationData))
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            closeSaveModal();
            showToast('✅ ' + data.message, 'success');
            getLocations();
        } else {
            showToast('❌ ' + data.message, 'error');
        }
    })
    .catch(() => showToast('❌ خطا در ارتباط با سرور', 'error'));
}

document.getElementById('saveModal').addEventListener('click', function(e) {
    if (e.target === this) closeSaveModal();
});

document.getElementById('locationNameInput').addEventListener('keydown', function(e) {
    if (e.key === 'Enter') confirmSaveLocation();
    if (e.key === 'Escape') closeSaveModal();
});

// ============================================================
// ۶. نوتیفیکیشن
// ============================================================
let toastTimeout;

function showToast(message, type = '') {
    const toast = document.getElementById('toast');
    toast.textContent = message;
    toast.className = 'toast ' + type + ' show';
    clearTimeout(toastTimeout);
    toastTimeout = setTimeout(() => {
        toast.classList.remove('show');
    }, 3500);
}

// ============================================================
// ۷. PWA
// ============================================================
let deferredPrompt;

window.addEventListener('beforeinstallprompt', (e) => {
    e.preventDefault();
    deferredPrompt = e;
    document.getElementById('installBtn').style.display = 'flex';
});

function installApp() {
    if (deferredPrompt) {
        deferredPrompt.prompt();
        deferredPrompt.userChoice.then((choiceResult) => {
            if (choiceResult.outcome === 'accepted') {
                showToast('✅ برنامه نصب شد!', 'success');
            } else {
                showToast('❌ نصب لغو شد', '');
            }
            deferredPrompt = null;
            document.getElementById('installBtn').style.display = 'none';
        });
    } else {
        showToast('ℹ️ مرورگر شما از نصب پشتیبانی نمی‌کند', 'info');
    }
}

// ============================================================
// ۸. بارگذاری اولیه
// ============================================================
getLocations();

if ('serviceWorker' in navigator) {
    navigator.serviceWorker.register('?sw=1&t=<?php echo $cacheBuster; ?>')
        .then(() => console.log('Service Worker ثبت شد'))
        .catch(() => console.log('Service Worker ثبت نشد'));
}

// ============================================================
// ۹. میانبر صفحه‌کلید
// ============================================================
document.addEventListener('keydown', (e) => {
    if (e.ctrlKey && e.key === 's') {
        e.preventDefault();
        openSaveModal();
    }
    if (e.ctrlKey && e.key === 'l') {
        e.preventDefault();
        fetch('?action=list&t=<?php echo $cacheBuster; ?>')
            .then(res => res.json())
            .then(data => {
                if (data.success && data.locations.length > 0) {
                    loadAndSearch(data.locations[0].id);
                } else {
                    showToast('ℹ️ هیچ مکانی ذخیره نشده است', 'info');
                }
            });
    }
});
</script>

</body>
</html>