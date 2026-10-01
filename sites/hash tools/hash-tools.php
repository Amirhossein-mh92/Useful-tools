<?php
// =====================================================
// فایل: sites/hash tools/hash-tools.php
// راه‌حل کاملاً مبتنی بر PHP با استفاده از password_hash و password_verify
// =====================================================

// اگر درخواست Ajax باشد، عملیات را انجام می‌دهیم
if (isset($_POST['action'])) {
    header('Content-Type: application/json');
    
    // ==================================================
    // 1. تولید هش bcrypt با password_hash
    // ==================================================
    if ($_POST['action'] === 'hash_bcrypt') {
        $password = $_POST['password'] ?? '';
        $cost = (int) ($_POST['cost'] ?? 12);
        
        // اعتبارسنجی ورودی
        if (empty($password)) {
            echo json_encode(['error' => 'متنی برای هش وجود ندارد']);
            exit;
        }
        
        // تولید هش با PASSWORD_BCRYPT و هزینه دلخواه
        $hash = password_hash($password, PASSWORD_BCRYPT, ['cost' => $cost]);
        
        if ($hash === false) {
            echo json_encode(['error' => 'خطا در تولید هش']);
        } else {
            echo json_encode(['hash' => $hash]);
        }
        exit;
    }
    
    // ==================================================
    // 2. بررسی تطابق با password_verify
    // ==================================================
    if ($_POST['action'] === 'verify_bcrypt') {
        $hash = $_POST['hash'] ?? '';
        $password = $_POST['password'] ?? '';
        
        // اعتبارسنجی ورودی
        if (empty($hash) || empty($password)) {
            echo json_encode(['error' => 'هش یا رمز عبور وارد نشده است']);
            exit;
        }
        
        // بررسی تطابق
        $isValid = password_verify($password, $hash);
        
        echo json_encode([
            'valid' => $isValid,
            'message' => $isValid ? '✅ رمز عبور صحیح است' : '❌ رمز عبور اشتباه است'
        ]);
        exit;
    }
    
    // ==================================================
    // 3. تولید هش‌های دیگر (MD5, SHA1, Base64)
    // ==================================================
    if ($_POST['action'] === 'other_hashes') {
        $password = $_POST['password'] ?? '';
        
        if (empty($password)) {
            echo json_encode(['error' => 'متنی برای هش وجود ندارد']);
            exit;
        }
        
        // محاسبه هش‌ها
        $md5 = hash('md5', $password);
        $sha1 = hash('sha1', $password);
        $base64 = base64_encode($password);
        
        echo json_encode([
            'md5' => $md5,
            'sha1' => $sha1,
            'base64' => $base64
        ]);
        exit;
    }
    
    // اگر action نامعتبر بود
    echo json_encode(['error' => 'درخواست نامعتبر']);
    exit;
}

// =====================================================
// صفحه HTML (بخش نمایشی)
// =====================================================
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="icon" href="fav.png" type="image/x-icon" />
    <title>هش رمز | کامل + معکوس (bcrypt با PHP)</title>
    <style>
        @font-face { font-family: titr-bold; src: url(B\ Titr\ Bold_0.ttf); }
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: titr-bold; }
        body {
            background: linear-gradient(145deg, #eef2fa 0%, #dce3ef 100%);
            min-height: 100vh;
            padding: 2rem 1.5rem;
        }
        .container {
            max-width: 1000px;
            margin: 0 auto;
            background: rgba(250, 252, 255, 0.65);
            backdrop-filter: blur(3px);
            border-radius: 64px;
            padding: 2rem;
        }
        h1 {
            font-size: 2rem;
            margin-bottom: 1.5rem;
            background: linear-gradient(130deg, #1f2e3c, #0a2b3a);
            background-clip: text;
            -webkit-background-clip: text;
            color: transparent;
        }
        h2 {
            font-size: 1.5rem;
            margin: 1.5rem 0 1rem;
            color: #1f3e4a;
            border-right: 5px solid #ffb347;
            padding-right: 15px;
        }
        textarea, input {
            width: 100%;
            padding: 1rem;
            border-radius: 28px;
            border: none;
            background: #030e16;
            color: #ddf4ff;
            font-family: titr-bold;
            margin-bottom: 1rem;
            font-size: 0.95rem;
        }
        textarea::placeholder, input::placeholder {
            color: #6a7a8a;
        }
        label {
            color: #1a2c38;
            font-weight: bold;
            display: block;
            margin-bottom: 8px;
        }
        button {
            background: #ffb347;
            border: none;
            padding: 10px 24px;
            border-radius: 40px;
            cursor: pointer;
            margin: 5px 5px 15px 0;
            font-family: titr-bold;
            transition: 0.2s;
            font-size: 0.95rem;
        }
        button:hover { background: #ffa01a; transform: scale(0.96); }
        .hash-box {
            background: #0f1a24e0;
            border-radius: 28px;
            padding: 1rem;
            margin-bottom: 1rem;
        }
        .hash-title { color: #ffb347; margin-bottom: 8px; font-size: 1rem; }
        .hash-value {
            background: #030e16;
            padding: 12px;
            border-radius: 24px;
            color: #ffefc0;
            word-break: break-all;
            font-size: 0.85rem;
            margin-bottom: 8px;
            min-height: 50px;
            display: flex;
            align-items: center;
        }
        .copy-btn {
            background: #2c4b5e;
            color: white;
            border: none;
            padding: 6px 16px;
            font-size: 0.8rem;
            border-radius: 30px;
            cursor: pointer;
            font-family: titr-bold;
            transition: 0.2s;
        }
        .copy-btn:hover { background: #1f3a48; transform: scale(0.95); }
        .reverse-section {
            background: #eef3fc30;
            border-radius: 28px;
            padding: 1.5rem;
            margin-top: 2rem;
        }
        .flex-copy { display: flex; align-items: center; gap: 12px; flex-wrap: wrap; }
        .loading-text { color: #ffb347 !important; animation: pulse 1s infinite; }
        @keyframes pulse { 0%, 100% { opacity: 1; } 50% { opacity: 0.4; } }
        @media (max-width: 680px) { 
            h1 { font-size: 1.5rem; } 
            .hash-value { font-size: 0.7rem; }
            .container { padding: 1rem; }
        }
        .success { color: #4caf50 !important; }
        .error { color: #f44336 !important; }
        .toast-msg {
            position: fixed; bottom: 30px; left: 50%; transform: translateX(-50%);
            background: #1e293b; color: #fff; padding: 10px 26px; border-radius: 50px;
            font-size: 0.9rem; z-index: 99999; animation: toastFade 2.2s ease forwards;
            font-family: system-ui; backdrop-filter: blur(4px);
            border: 1px solid rgba(255,255,200,0.2);
            white-space: nowrap;
        }
        @keyframes toastFade {
            0% { opacity: 0; transform: translateX(-50%) translateY(20px); }
            15% { opacity: 1; transform: translateX(-50%) translateY(0); }
            85% { opacity: 1; transform: translateX(-50%) translateY(0); }
            100% { opacity: 0; transform: translateX(-50%) translateY(20px); }
        }
        .bcrypt-status {
            font-size: 0.75rem;
            color: #aabbcc;
            margin-top: 4px;
        }
        .btn-secondary {
            background: #6c7a8a;
            color: white;
        }
        .btn-secondary:hover {
            background: #5a6a7a;
        }
        .cost-input {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 10px;
        }
        .cost-input input {
            width: 80px;
            margin-bottom: 0;
            padding: 8px;
            text-align: center;
        }
        .cost-input label {
            margin-bottom: 0;
            color: #ddf4ff;
        }
    </style>
</head>
<body>
    <div style="position: fixed; top: 24px; left: 8px; z-index: 9999; direction: ltr;">
        <a href="../../index.php" style="
            background: #1e2f3c;
            color: white;
            padding: 10px 10px;
            border-radius: 40px;
            text-decoration: none;
            font-weight: bold;
            font-family: system-ui;
            box-shadow: 0 6px 14px rgba(0,0,0,0.25);
            border: 1px solid rgba(255,245,170,0.7);
            backdrop-filter: blur(8px);
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 1rem;
        " onmouseover="this.style.backgroundColor='#0d1c26'; this.style.transform='scale(0.97)';"
            onmouseout="this.style.backgroundColor='#1e2f3c'; this.style.transform='scale(1)';">
            🏠 بازگشت به صفحه اصلی
        </a>
    </div>

    <div class="container">
        <h1>🔒 هش رمز (یک‌طرفه + معکوس) – bcrypt با PHP</h1>

        <textarea id="inputText" rows="3" placeholder="متن یا رمز خود را وارد کنید..."></textarea>
        
        <div class="cost-input">
            <label style="color: black;">cost (for bcrypt):</label>
            <input type="number" id="costInput" value="12" min="4" max="31" />
            <span style="color: #6a7a8a; font-size: 0.85rem;">(پیش‌فرض ۱۲، بیشتر = امن‌تر + کندتر)</span>
        </div>
        
        <button onclick="generateAllHashes()">⚡ تولید همه هش‌ها (MD5, SHA1, Base64, bcrypt)</button>

        <div class="hash-box">
            <div class="hash-title">🔹 MD5</div>
            <div class="flex-copy">
                <div class="hash-value" id="md5">---</div>
                <button class="copy-btn" onclick="copyResult('md5')">📋 کپی</button>
            </div>
        </div>
        <div class="hash-box">
            <div class="hash-title">🔸 SHA-1</div>
            <div class="flex-copy">
                <div class="hash-value" id="sha1">---</div>
                <button class="copy-btn" onclick="copyResult('sha1')">📋 کپی</button>
            </div>
        </div>
        <div class="hash-box">
            <div class="hash-title">📦 Base64</div>
            <div class="flex-copy">
                <div class="hash-value" id="base64">---</div>
                <button class="copy-btn" onclick="copyResult('base64')">📋 کپی</button>
            </div>
        </div>
        <div class="hash-box">
            <div class="hash-title">⚡ bcrypt (PASSWORD_BCRYPT)</div>
            <div class="flex-copy">
                <div class="hash-value" id="bcrypt">---</div>
                <button class="copy-btn" onclick="copyResult('bcrypt')">📋 کپی</button>
            </div>
            <div class="bcrypt-status" id="bcryptStatus">✅ با توابع داخلی PHP</div>
        </div>

        <div class="reverse-section">
            <h2>🔁 بخش معکوس</h2>

            <label>🔓 رمزگشایی Base64</label>
            <textarea id="base64Input" rows="2" placeholder="متن Base64 شده را وارد کنید..."></textarea>
            <button onclick="decodeBase64()">🔓 رمزگشایی</button>
            <div class="flex-copy">
                <div class="hash-value" id="base64Decoded">---</div>
                <button class="copy-btn" onclick="copyResult('base64Decoded')">📋 کپی</button>
            </div>

            <label>✅ تطابق bcrypt (با password_verify)</label>
            <input type="text" id="bcryptHash" placeholder="هش bcrypt (مثل $2y$10$...)" dir="ltr" />
            <input type="text" id="bcryptPlain" placeholder="رمز عبور حدسی" />
            <button onclick="verifyBcrypt()">✅ بررسی تطابق</button>
            <div class="hash-value" id="bcryptVerifyResult">---</div>
        </div>
    </div>

    <script>
        // =====================================================
        // توابع کمکی
        // =====================================================
        
        function showToast(msg) {
            const old = document.querySelector('.toast-msg');
            if (old) old.remove();
            const div = document.createElement('div');
            div.className = 'toast-msg';
            div.textContent = msg;
            document.body.appendChild(div);
            setTimeout(() => div.remove(), 2200);
        }

        function copyResult(elementId) {
            const el = document.getElementById(elementId);
            const text = el.innerText;
            if (!text || text === "---" || text.includes("خطا") || text.includes("⏳") || text.includes("در حال")) {
                showToast("⚠️ متن معتبری برای کپی وجود ندارد!");
                return;
            }
            navigator.clipboard.writeText(text).then(() => {
                showToast("✅ کپی شد!");
            }).catch(() => {
                const ta = document.createElement('textarea');
                ta.value = text;
                document.body.appendChild(ta);
                ta.select();
                document.execCommand('copy');
                document.body.removeChild(ta);
                showToast("✅ کپی شد!");
            });
        }

        // =====================================================
        // 1. تولید همه هش‌ها (با استفاده از Ajax به PHP)
        // =====================================================
        
        function generateAllHashes() {
            const input = document.getElementById('inputText').value.trim();
            if (!input) {
                showToast("⚠️ لطفاً متنی وارد کنید");
                return;
            }

            const cost = parseInt(document.getElementById('costInput').value) || 12;

            // --- 1.1 هش‌های معمولی (MD5, SHA1, Base64) ---
            fetch(window.location.href, {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: new URLSearchParams({
                    action: 'other_hashes',
                    password: input
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.error) {
                    showToast('❌ ' + data.error);
                    return;
                }
                document.getElementById('md5').innerText = data.md5 || '---';
                document.getElementById('sha1').innerText = data.sha1 || '---';
                document.getElementById('base64').innerText = data.base64 || '---';
            })
            .catch(err => {
                showToast('❌ خطا در دریافت هش‌ها: ' + err.message);
            });

            // --- 1.2 هش bcrypt با password_hash ---
            const bcryptField = document.getElementById('bcrypt');
            const statusField = document.getElementById('bcryptStatus');
            bcryptField.innerText = "⏳ در حال تولید هش...";
            bcryptField.className = 'hash-value loading-text';
            statusField.innerText = '⏳ در حال محاسبه با password_hash...';

            fetch(window.location.href, {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: new URLSearchParams({
                    action: 'hash_bcrypt',
                    password: input,
                    cost: cost
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.error) {
                    bcryptField.innerText = '❌ ' + data.error;
                    bcryptField.className = 'hash-value error';
                    statusField.innerText = '❌ خطا در تولید هش';
                    showToast('❌ ' + data.error);
                    return;
                }
                if (data.hash) {
                    bcryptField.innerText = data.hash;
                    bcryptField.className = 'hash-value';
                    statusField.innerText = '✅ هش با موفقیت تولید شد (cost=' + cost + ')';
                    showToast('✅ bcrypt با موفقیت تولید شد');
                } else {
                    throw new Error('پاسخ نامعتبر از سرور');
                }
            })
            .catch(err => {
                bcryptField.innerText = '❌ خطا: ' + err.message;
                bcryptField.className = 'hash-value error';
                statusField.innerText = '❌ خطا در ارتباط با سرور';
                showToast('❌ خطا در تولید bcrypt');
            });
        }

        // =====================================================
        // 2. رمزگشایی Base64 (کاملاً در مرورگر)
        // =====================================================
        
        function decodeBase64() {
            const encoded = document.getElementById('base64Input').value.trim();
            if (!encoded) {
                showToast("⚠️ لطفاً متن Base64 را وارد کنید");
                return;
            }
            try {
                const decoded = decodeURIComponent(escape(atob(encoded)));
                document.getElementById('base64Decoded').innerText = decoded;
                showToast("✅ رمزگشایی شد");
            } catch(e) {
                document.getElementById('base64Decoded').innerText = "❌ فرمت Base64 نامعتبر";
                showToast("❌ فرمت Base64 نامعتبر");
            }
        }

        // =====================================================
        // 3. بررسی تطابق bcrypt (با password_verify)
        // =====================================================
        
        function verifyBcrypt() {
            const hash = document.getElementById('bcryptHash').value.trim();
            const plain = document.getElementById('bcryptPlain').value.trim();
            const resultDiv = document.getElementById('bcryptVerifyResult');

            if (!hash || !plain) {
                showToast("⚠️ هر دو فیلد را پر کنید");
                return;
            }

            // بررسی سریع فرمت هش
            if (!hash.startsWith('$2') && !hash.startsWith('$2a') && !hash.startsWith('$2b') && !hash.startsWith('$2y')) {
                resultDiv.innerText = "⚠️ فرمت هش نامعتبر - باید با $2 شروع شود";
                resultDiv.className = 'hash-value error';
                showToast("⚠️ فرمت هش نامعتبر");
                return;
            }

            resultDiv.innerText = "⏳ در حال بررسی...";
            resultDiv.className = 'hash-value loading-text';

            fetch(window.location.href, {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: new URLSearchParams({
                    action: 'verify_bcrypt',
                    hash: hash,
                    password: plain
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.error) {
                    resultDiv.innerText = '❌ ' + data.error;
                    resultDiv.className = 'hash-value error';
                    showToast('❌ ' + data.error);
                    return;
                }
                if (data.valid === true) {
                    resultDiv.innerText = "✅ رمز عبور صحیح است! 🎉";
                    resultDiv.className = 'hash-value success';
                    showToast("✅ رمز عبور صحیح است!");
                } else if (data.valid === false) {
                    resultDiv.innerText = "❌ رمز عبور اشتباه است";
                    resultDiv.className = 'hash-value error';
                    showToast("❌ رمز عبور اشتباه است");
                } else {
                    resultDiv.innerText = "❌ پاسخ نامعتبر از سرور";
                    resultDiv.className = 'hash-value error';
                }
            })
            .catch(err => {
                resultDiv.innerText = "❌ خطا: " + err.message;
                resultDiv.className = 'hash-value error';
                showToast("❌ خطا در بررسی bcrypt");
            });
        }

        // =====================================================
        // 4. تست خودکار در هنگام بارگذاری صفحه
        // =====================================================
        
        window.addEventListener('load', function() {
            // تست تولید هش با یک متن ساده برای اطمینان از کارکرد password_hash
            fetch(window.location.href, {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: new URLSearchParams({
                    action: 'hash_bcrypt',
                    password: 'test',
                    cost: 10
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.hash) {
                    document.getElementById('bcryptStatus').innerHTML = '✅ bcrypt با password_hash فعال است';
                } else {
                    document.getElementById('bcryptStatus').innerHTML = '⚠️ خطا در تست bcrypt';
                }
            })
            .catch(() => {
                document.getElementById('bcryptStatus').innerHTML = '⚠️ خطا در ارتباط با سرور';
            });
        });
    </script>
</body>
</html>