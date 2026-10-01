<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="icon" href="fav.png" type="image/x-icon" />
    <title>رمزگذاری و رمزگشایی متن</title>
    <style>
        @font-face { font-family: titr-bold; src: url(B\ Titr\ Bold_0.ttf); }
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: titr-bold; }
        body {
            background: linear-gradient(145deg, #eef2fa 0%, #dce3ef 100%);
            min-height: 100vh;
            padding: 2rem 1.5rem;
        }
        .container {
            max-width: 900px;
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
        textarea, input {
            width: 100%;
            padding: 1rem;
            border-radius: 28px;
            border: none;
            background: #030e16;
            color: #ddf4ff;
            font-family: titr-bold;
            margin-bottom: 1rem;
        }
        label { color: #1a2c38; font-weight: bold; display: block; margin-bottom: 8px; }
        button {
            background: #ffb347;
            border: none;
            padding: 10px 24px;
            border-radius: 40px;
            cursor: pointer;
            margin: 5px;
            font-family: titr-bold;
            transition: all 0.2s;
        }
        button:hover { background: #ffa01a; transform: scale(0.96); }
        .result-box {
            background: #0f1a24e0;
            border-radius: 28px;
            padding: 1rem;
            margin-top: 1rem;
        }
        .hash-value { color: #ffefc0; word-break: break-all; }
        .btn-clear { background: #dc3545; color: white; }
        .btn-clear:hover { background: #c82333; }
        .toast-msg {
            position: fixed; bottom: 30px; left: 50%; transform: translateX(-50%);
            background: #1e293b; color: #fff; padding: 10px 26px; border-radius: 50px;
            font-size: 0.9rem; z-index: 99999; animation: toastFade 2.2s ease forwards;
            font-family: system-ui; backdrop-filter: blur(4px);
            border: 1px solid rgba(255,255,200,0.2);
        }
        @keyframes toastFade {
            0% { opacity: 0; transform: translateX(-50%) translateY(20px); }
            15% { opacity: 1; transform: translateX(-50%) translateY(0); }
            85% { opacity: 1; transform: translateX(-50%) translateY(0); }
            100% { opacity: 0; transform: translateX(-50%) translateY(20px); }
        }
        @media (max-width: 680px) { h1 { font-size: 1.5rem; } }
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
        <h1>🔐 رمزگذاری / رمزگشایی متن (AES)</h1>

        <label>✏️ متن خود را وارد کنید:</label>
        <textarea id="message" rows="3" placeholder="متن اصلی..."></textarea>

        <label>🔑 کلید رمز (حداقل ۴ کاراکتر):</label>
        <input type="text" id="secretKey" placeholder="کلید خود را وارد کنید" />

        <div style="margin: 15px 0;">
            <button onclick="encrypt()">🔒 رمزگذاری</button>
            <button onclick="decrypt()">🔓 رمزگشایی</button>
            <button onclick="clearAll()" class="btn-clear">🗑️ پاک کردن همه</button>
        </div>

        <div class="result-box">
            <div class="hash-title" style="color:#ffb347;">✅ نتیجه:</div>
            <div class="hash-value" id="output">---</div>
        </div>
        <button onclick="copyResult()">📋 کپی نتیجه</button>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/4.2.0/crypto-js.min.js"></script>
    <script>
        function showToast(msg) {
            const old = document.querySelector('.toast-msg');
            if (old) old.remove();
            const div = document.createElement('div');
            div.className = 'toast-msg';
            div.textContent = msg;
            document.body.appendChild(div);
            setTimeout(() => div.remove(), 2200);
        }

        function encrypt() {
            const message = document.getElementById('message').value;
            const key = document.getElementById('secretKey').value;
            if (!message || !key) { showToast("⚠️ متن و کلید را وارد کنید!"); return; }
            const encrypted = CryptoJS.AES.encrypt(message, key).toString();
            document.getElementById('output').innerText = encrypted;
            showToast("✅ متن با موفقیت رمزگذاری شد!");
        }

        function decrypt() {
            const encryptedMessage = document.getElementById('message').value;
            const key = document.getElementById('secretKey').value;
            if (!encryptedMessage || !key) { showToast("⚠️ متن رمز شده و کلید را وارد کنید!"); return; }
            try {
                const decrypted = CryptoJS.AES.decrypt(encryptedMessage, key).toString(CryptoJS.enc.Utf8);
                if (!decrypted) throw new Error();
                document.getElementById('output').innerText = decrypted;
                showToast("✅ متن با موفقیت رمزگشایی شد!");
            } catch (e) {
                document.getElementById('output').innerText = "❌ رمزگشایی ناموفق بود";
                showToast("❌ رمزگشایی ناموفق بود!");
            }
        }

        function copyResult() {
            const result = document.getElementById('output').innerText;
            if (result === "---") { showToast("⚠️ چیزی برای کپی وجود ندارد!"); return; }
            navigator.clipboard.writeText(result).then(() => {
                showToast("✅ کپی شد!");
            }).catch(() => {
                const ta = document.createElement('textarea');
                ta.value = result;
                document.body.appendChild(ta);
                ta.select();
                document.execCommand('copy');
                document.body.removeChild(ta);
                showToast("✅ کپی شد!");
            });
        }

        function clearAll() {
            document.getElementById('message').value = '';
            document.getElementById('secretKey').value = '';
            document.getElementById('output').innerText = '---';
            showToast("🗑️ تمام فیلدها پاک شدند!");
        }
    </script>
</body>
</html>