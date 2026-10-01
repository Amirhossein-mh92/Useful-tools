<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes" />
    <link rel="icon" href="fav.png" type="image/x-icon" />
    <title>ساخت رمز قوی | ابزار رمزساز</title>
    <style>
        @font-face { font-family: titr-bold; src: url(B\ Titr\ Bold_0.ttf); }
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: titr-bold; }
        body {
            background: linear-gradient(145deg, #eef2fa 0%, #dce3ef 100%);
            min-height: 100vh;
            padding: 2rem 1.5rem;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: rgba(250, 252, 255, 0.65);
            backdrop-filter: blur(3px);
            border-radius: 64px;
            padding: 2rem;
            box-shadow: 0 25px 45px -12px rgba(0, 0, 0, 0.25);
        }
        h1 {
            font-size: 2rem;
            background: linear-gradient(130deg, #1f2e3c, #0a2b3a);
            background-clip: text;
            -webkit-background-clip: text;
            color: transparent;
            margin-bottom: 1.5rem;
        }
        .result-box {
            background: #0f1a24e0;
            backdrop-filter: blur(16px);
            border-radius: 48px;
            padding: 1.5rem;
            text-align: center;
            margin-bottom: 2rem;
        }
        .password-display {
            font-size: 1.8rem;
            color: #ffefc0;
            word-break: break-all;
            background: #030e16;
            padding: 1rem;
            border-radius: 40px;
            letter-spacing: 2px;
        }
        .options {
            background: #eef3fc30;
            border-radius: 28px;
            padding: 1.5rem;
            margin: 1.5rem 0;
        }
        .option-row {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            margin-bottom: 1rem;
            align-items: center;
        }
        .option-row label { flex: 1; color: #1a2c38; font-weight: bold; }
        input[type="number"], select {
            flex: 2;
            padding: 10px;
            border-radius: 40px;
            border: none;
            background: #fff;
            font-family: titr-bold;
        }
        button {
            background: #ffb347;
            border: none;
            padding: 12px 24px;
            border-radius: 40px;
            font-weight: bold;
            cursor: pointer;
            transition: 0.2s;
            font-family: titr-bold;
            margin: 5px;
        }
        button:hover { background: #ffa01a; transform: scale(0.96); }
        .copy-btn { background: #2c4a5e; color: white; margin-top: 1rem; }
        .clear-btn { background: #8b5a2b; color: white; }
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
        @media (max-width: 680px) {
            .password-display { font-size: 1.2rem; }
            h1 { font-size: 1.5rem; }
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
        <h1>🔐 ساخت رمز قوی (Password Generator)</h1>

        <div class="result-box">
            <div class="password-display" id="password">********</div>
            <button class="copy-btn" onclick="copyPassword()">📋 کپی رمز</button>
            <button class="clear-btn" onclick="clearPassword()">🗑️ پاک کردن نتیجه</button>
        </div>

        <div class="options">
            <div class="option-row">
                <label>طول رمز:</label>
                <input type="number" id="length" min="4" max="64" value="12" />
            </div>
            <div class="option-row">
                <label>🔠 شامل حروف بزرگ (A-Z) :</label>
                <input type="checkbox" id="uppercase" checked />
            </div>
            <div class="option-row">
                <label>🔡 شامل حروف کوچک (a-z) :</label>
                <input type="checkbox" id="lowercase" checked />
            </div>
            <div class="option-row">
                <label>🔢 شامل اعداد (0-9) :</label>
                <input type="checkbox" id="numbers" checked />
            </div>
            <div class="option-row">
                <label>✨ شامل کاراکترهای خاص (!@#$%^&*) :</label>
                <input type="checkbox" id="symbols" checked />
            </div>
            <button onclick="generatePassword()">🔁 تولید رمز جدید</button>
        </div>
    </div>

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

        function generatePassword() {
            let length = parseInt(document.getElementById('length').value);
            let upper = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
            let lower = 'abcdefghijklmnopqrstuvwxyz';
            let numbers = '0123456789';
            let symbols = '!@#$%^&*()_+-=[]{}|;:,.<>?';

            let charSet = '';
            if (document.getElementById('uppercase').checked) charSet += upper;
            if (document.getElementById('lowercase').checked) charSet += lower;
            if (document.getElementById('numbers').checked) charSet += numbers;
            if (document.getElementById('symbols').checked) charSet += symbols;

            if (charSet === '') { showToast('⚠️ حداقل یک گزینه را انتخاب کنید!'); return; }

            let password = '';
            for (let i = 0; i < length; i++) {
                let randomIndex = Math.floor(Math.random() * charSet.length);
                password += charSet[randomIndex];
            }
            document.getElementById('password').innerText = password;
        }

        function copyPassword() {
            let pass = document.getElementById('password').innerText;
            if (pass === '********') { showToast('⚠️ اول رمز بسازید'); return; }
            navigator.clipboard.writeText(pass).then(() => {
                showToast('✅ رمز کپی شد');
            }).catch(() => {
                const ta = document.createElement('textarea');
                ta.value = pass;
                document.body.appendChild(ta);
                ta.select();
                document.execCommand('copy');
                document.body.removeChild(ta);
                showToast('✅ رمز کپی شد');
            });
        }

        function clearPassword() {
            document.getElementById('password').innerText = '********';
        }

        generatePassword();
    </script>
</body>
</html>