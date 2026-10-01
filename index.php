<!DOCTYPE html>
<html lang="fa" dir="rtl">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes" />
    <link rel="icon" href="images/fav.png" type="image/png" />
    <link rel="stylesheet" href="css/style.css" />
    <title>ابزار های کاربردی</title>
</head>

<body>
    <div class="container">

        <header class="header">
            <div class="title-section">
                <h1>
                    <span class="logo-icon">
                    </span>
                    ابزار های کاربردی 
                </h1>
                <p>🔖 داشبورد اختصاصی</p>
            </div>
            <!-- <div class="header-actions">
                <button class="btn-ghost" onclick="copyTemplateCode()" title="کپی الگوی دکمه">📋 الگو</button>
                <button class="btn-ghost" onclick="copyHomeSnippet()" title="کپی دکمه خانه">🏠 دکمه</button>
            </div> -->
        </header>

        <div class="sites-grid">
            <a href="sites/name fixer/NameFixer.php" rel="noopener noreferrer" class="btn-card" style="--card-color: #2b3a67; --card-glow: #4a6a9a;">
                <div class="btn-content">
                    <span class="btn-name">برعکس کردن متن فارسی</span>
                    <span class="btn-sub">برای بازی‌هایی مثل ماینکرفت</span>
                </div>
            </a>

            <a href="sites/text repeater/text-repeater.php" rel="noopener noreferrer" class="btn-card" style="--card-color: #2d5a27; --card-glow: #4a9a4a;">
                <div class="btn-content">
                    <span class="btn-name">تکرار کننده متن</span>
                    <span class="btn-sub">بدون باگ، با حالت‌های متنوع</span>
                </div>
            </a>

            <a href="sites/password generator/password-generator.php" rel="noopener noreferrer" class="btn-card" style="--card-color: #a34d0a; --card-glow: #c47a3a;">
                <div class="btn-content">
                    <span class="btn-name">ساخت پسورد</span>
                    <span class="btn-sub">امن و قوی با تنظیمات دلخواه</span>
                </div>
            </a>

            <a href="sites/hash tools/hash-tools.php" rel="noopener noreferrer" class="btn-card" style="--card-color: #4a2b7a; --card-glow: #7a4aaa;">
                <div class="btn-content">
                    <span class="btn-name">هش کننده متن</span>
                    <span class="btn-sub">دقیق، امن و معتبر</span>
                </div>
            </a>

            <a href="sites/encrypt decrypt/encrypt-decrypt.php" rel="noopener noreferrer" class="btn-card" style="--card-color: #1e5f6b; --card-glow: #3a9aaa;">
                <div class="btn-content">
                    <span class="btn-name">رمزگذاری متن</span>
                    <span class="btn-sub">قابل استفاده در پیام‌رسان‌ها</span>
                </div>
            </a>

            <a href="sites/image to svg/image-to-svg.php" rel="noopener noreferrer" class="btn-card" style="--card-color: #6b3a2b; --card-glow: #aa6a3a;">
                <div class="btn-content">
                    <span class="btn-name">تبدیل عکس به SVG</span>
                    <span class="btn-sub">برای برنامه‌نویسی و طراحی</span>
                </div>
            </a>

            <a href="sites/mc color code generator/color code generator.php" rel="noopener noreferrer" class="btn-card" style="--card-color: #7eff22; --card-glow: #3aaa5f;">
                <div class="btn-content">
                    <span class="btn-name">ساخت کد متن رنگی</span>
                    <span class="btn-sub">برای بازی ماینکرفت</span>
                </div>
            </a>
            <!-- <a href="https://hoselam-pokid.ir/fun/life-stats" rel="noopener noreferrer" class="btn-card" style="--card-color: #6b2b64; --card-glow: #a54aaa;">
                <div class="btn-content">
                    <span class="btn-name">زندگی‌سنج کوانتومی</span>
                    <span class="btn-sub">عجیب و جذاب</span>
                </div>
            </a> -->
        </div>

        <!-- <section class="code-snippet-section">
            <div class="snippet-title">📐 الگوی دکمه جدید</div>
            <div class="instruction-box">
                برای افزودن سرویس جدید، کد زیر را کپی کرده و در بخش <code>.sites-grid</code> قرار دهید.
            </div>
            <pre id="templateCode">&lt;a href="https://your-link.com" target="_blank" rel="noopener noreferrer" class="btn-card" style="--card-color: #2b3a67; --card-glow: #4a6a9a;"&gt;
    &lt;div class="btn-content"&gt;
        &lt;span class="btn-name"&gt;نام سرویس&lt;/span&gt;
        &lt;span class="btn-sub"&gt;توضیحات کوتاه&lt;/span&gt;
    &lt;/div&gt;
&lt;/a&gt;</pre>
            <button class="copy-btn" onclick="copyTemplateCode()">📋 کپی الگو</button>
        </section> -->

        <footer>
            <p>طراحی شده توسط Amirhossein Mohammdi با ❤️ برای استفاده روزمره ✨ </p>
        </footer>
    </div>

    <script>
        function copyTemplateCode() {
            const raw = `<a href="https://your-link.com" target="_blank" rel="noopener noreferrer" class="btn-card" style="--card-color: #2b3a67; --card-glow: #4a6a9a;">
    <div class="btn-content">
        <span class="btn-name">نام سرویس</span>
        <span class="btn-sub">توضیحات کوتاه</span>
    </div>
</a>`;
            copyToClipboard(raw);
            showToast('✅ الگوی دکمه کپی شد!');
        }

        function copyHomeSnippet() {
            const snippet = `<div style="position: fixed; top: 24px; left: 24px; z-index: 9999; direction: ltr;">
    <a href="../../index.php" style="
        background: #1e2f3c;
        color: white;
        padding: 10px 24px;
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
    " onmouseover="this.style.backgroundColor='#0d1c26'; this.style.transform='scale(0.97)';" onmouseout="this.style.backgroundColor='#1e2f3c'; this.style.transform='scale(1)';">
        🏠 بازگشت به صفحه اصلی
    </a>
</div>`;
            copyToClipboard(snippet);
            showToast('✅ کد دکمه خانه کپی شد!');
        }

        function copyToClipboard(text) {
            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText(text).catch(() => fallbackCopy(text));
            } else {
                fallbackCopy(text);
            }
        }

        function fallbackCopy(text) {
            const ta = document.createElement('textarea');
            ta.value = text;
            document.body.appendChild(ta);
            ta.select();
            document.execCommand('copy');
            document.body.removeChild(ta);
        }

        function showToast(msg) {
            const old = document.querySelector('.toast-msg');
            if (old) old.remove();
            const div = document.createElement('div');
            div.className = 'toast-msg';
            div.textContent = msg;
            document.body.appendChild(div);
            setTimeout(() => div.remove(), 2200);
        }

        const styleToast = document.createElement('style');
        styleToast.textContent = `
            .toast-msg {
                position: fixed;
                bottom: 30px;
                left: 50%;
                transform: translateX(-50%);
                background: #1e293b;
                color: #fff;
                padding: 10px 26px;
                border-radius: 50px;
                font-size: 0.9rem;
                font-weight: 500;
                z-index: 99999;
                box-shadow: 0 8px 24px rgba(0,0,0,0.35);
                animation: toastFade 2.2s ease forwards;
                font-family: system-ui;
                backdrop-filter: blur(4px);
                border: 1px solid rgba(255,255,200,0.2);
            }
            @keyframes toastFade {
                0% { opacity: 0; transform: translateX(-50%) translateY(20px); }
                15% { opacity: 1; transform: translateX(-50%) translateY(0); }
                85% { opacity: 1; transform: translateX(-50%) translateY(0); }
                100% { opacity: 0; transform: translateX(-50%) translateY(20px); }
            }
        `;
        document.head.appendChild(styleToast);
    </script>
</body>

</html>