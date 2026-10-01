<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes" />
    <link rel="icon" href="fav.png" type="image/png" />
    <title>تکرارگر حرفه‌ای | آرام، بدون باگ، با جداکننده</title>
    <style>
        @font-face { font-family: titr-bold; src: url(B\ Titr\ Bold_0.ttf); }
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: titr-bold; }
        body {
            background: linear-gradient(145deg, #f1f5f9 0%, #e6edf4 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 1.5rem;
        }
        .card {
            max-width: 1050px;
            width: 100%;
            background: #ffffff;
            border-radius: 2rem;
            box-shadow: 0 20px 35px -12px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            border: 1px solid rgba(255,255,255,0.5);
        }
        .header {
            background: #1e293b;
            padding: 1.4rem 2rem;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }
        .header h1 { font-size: 1.75rem; display: flex; align-items: center; gap: 10px; }
        .header h1::before { content: "🔄"; font-size: 1.8rem; }
        .sub { font-size: 0.85rem; opacity: 0.8; margin-top: 6px; }
        .refresh-btn {
            background: rgba(255,255,255,0.15);
            border: none;
            font-size: 1.4rem;
            width: 44px;
            height: 44px;
            border-radius: 40px;
            cursor: pointer;
            color: white;
        }
        .form-section { padding: 1.8rem 2rem 1rem 2rem; }
        .input-group { margin-bottom: 1.5rem; }
        label { display: block; font-weight: 600; margin-bottom: 0.5rem; color: #0f172a; font-size: 0.9rem; }
        input, select {
            width: 100%;
            padding: 0.85rem 1.2rem;
            font-size: 1rem;
            border: 2px solid #e2e8f0;
            border-radius: 1.2rem;
            background: #fefefe;
            transition: all 0.2s;
            outline: none;
        }
        input:focus, select:focus { border-color: #3b82f6; box-shadow: 0 0 0 3px rgba(59,130,246,0.2); }
        .row-2col { display: flex; gap: 1rem; flex-wrap: wrap; }
        .row-2col .input-group { flex: 1; min-width: 150px; }
        .mode-group {
            background: #f8fafc;
            border-radius: 1.2rem;
            padding: 0.8rem;
            border: 1px solid #e2edf7;
        }
        .mode-options { display: flex; flex-wrap: wrap; gap: 0.8rem; row-gap: 0.6rem; }
        .mode-option {
            display: flex;
            align-items: center;
            gap: 8px;
            background: white;
            padding: 0.5rem 1rem;
            border-radius: 2rem;
            border: 1px solid #cbd5e1;
            cursor: pointer;
            transition: all 0.2s;
        }
        .mode-option.selected { background: #eef2ff; border-color: #3b82f6; }
        .mode-option input { width: 18px; height: 18px; accent-color: #3b82f6; margin: 0; }
        .mode-option span { font-size: 0.85rem; font-weight: 500; }
        .actions { display: flex; gap: 1rem; margin-top: 1.8rem; flex-wrap: wrap; }
        button {
            flex: 1;
            padding: 0.8rem 1.2rem;
            font-size: 1rem;
            font-weight: 600;
            border: none;
            border-radius: 1.8rem;
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        button:active { transform: scale(0.97); }
        .btn-primary { background: #3b82f6; color: white; box-shadow: 0 4px 8px rgba(59,130,246,0.3); }
        .btn-primary:hover:not(:disabled) { background: #2563eb; transform: translateY(-1px); }
        .btn-danger { background: #ef4444; color: white; }
        .btn-danger:hover:not(:disabled) { background: #dc2626; }
        button:disabled { opacity: 0.55; cursor: not-allowed; transform: none; }
        .progress-area { padding: 0.2rem 2rem 0.8rem 2rem; }
        .progress-bar-container {
            background: #e2e8f0;
            border-radius: 40px;
            height: 12px;
            overflow: hidden;
            margin-top: 8px;
        }
        .progress-fill { width: 0%; height: 100%; background: #3b82f6; border-radius: 40px; transition: width 0.2s ease; }
        .progress-stats { display: flex; justify-content: space-between; font-size: 0.75rem; color: #334155; margin-top: 6px; }
        .output-container { padding: 0rem 2rem 2rem 2rem; }
        .output-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
            gap: 12px;
            flex-wrap: wrap;
        }
        .output-header span:first-child { font-weight: 700; background: #e2e8f0; padding: 5px 14px; border-radius: 30px; font-size: 0.8rem; }
        .action-buttons { display: flex; gap: 12px; }
        .icon-btn {
            background: #f1f5f9;
            border: none;
            font-size: 0.85rem;
            font-weight: 600;
            padding: 8px 20px;
            border-radius: 2rem;
            cursor: pointer;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .icon-btn-copy { background: #10b981; color: white; }
        .icon-btn-copy:hover { background: #059669; }
        .icon-btn-clear { background: #f97316; color: white; }
        .icon-btn-clear:hover { background: #ea580c; }
        .output-box {
            background: #0f172a;
            color: #e2e8f0;
            border-radius: 1.2rem;
            padding: 1.2rem;
            min-height: 260px;
            max-height: 400px;
            overflow-y: auto;
            font-family: monospace;
            font-size: 0.9rem;
            line-height: 1.5;
            white-space: pre-wrap;
            word-break: break-word;
            direction: ltr;
            text-align: left;
        }
        .output-box::-webkit-scrollbar { width: 6px; }
        .output-box::-webkit-scrollbar-track { background: #1e293b; border-radius: 8px; }
        .output-box::-webkit-scrollbar-thumb { background: #3b82f6; border-radius: 8px; }
        .numbered-item, .break-item { margin: 4px 0; padding: 2px 0; border-bottom: 1px dashed #334155; }
        .numbered-item:last-child, .break-item:last-child { border-bottom: none; }
        footer { font-size: 0.7rem; text-align: center; padding: 1rem; color: #94a3b8; border-top: 1px solid #eef2ff; }
        @media (max-width: 550px) {
            .card { border-radius: 1.5rem; }
            .form-section, .output-container, .progress-area { padding-left: 1.2rem; padding-right: 1.2rem; }
            .mode-option { padding: 0.3rem 0.7rem; }
            .icon-btn { padding: 5px 12px; font-size: 0.75rem; }
        }
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
    <div class="card">
        <div class="header">
            <div>
                <h1>تکرارگر آرام متن</h1>
                <div class="sub">۵ حالت تکرار &nbsp;|&nbsp; بدون هنگ با پیشرفت روان</div>
            </div>
            <button class="refresh-btn" onclick="location.reload()">🔄</button>
        </div>

        <div class="form-section">
            <div class="input-group">
                <label>📝 متن یا کلمه</label>
                <input type="text" id="textInput" placeholder="متنی را وارد کنید...." />
            </div>

            <div class="row-2col">
                <div class="input-group">
                    <label>🔢 تعداد تکرار</label>
                    <input type="number" id="repeatCount" min="1" max="250000" value="10" />
                </div>
                <div class="input-group">
                    <label>🎚️ حالت تکرار</label>
                    <div class="mode-group">
                        <div class="mode-options">
                            <label class="mode-option" data-mode="space">
                                <input type="radio" name="repeatMode" value="space" checked /> <span>🔹 پشت سر هم (با فاصله)</span>
                            </label>
                            <label class="mode-option" data-mode="linebreak">
                                <input type="radio" name="repeatMode" value="linebreak" /> <span>📄 زیر هم (خط جدید)</span>
                            </label>
                            <label class="mode-option" data-mode="numbered">
                                <input type="radio" name="repeatMode" value="numbered" /> <span>🔢 شماره‌دار</span>
                            </label>
                            <label class="mode-option" data-mode="comma">
                                <input type="radio" name="repeatMode" value="comma" /> <span>🟰 جداکننده کاما</span>
                            </label>
                            <label class="mode-option" data-mode="dash">
                                <input type="radio" name="repeatMode" value="dash" /> <span>➖ جداکننده خط تیره</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="actions">
                <button id="startBtn" class="btn-primary">▶ شروع تکرار آرام</button>
                <button id="stopBtn" class="btn-danger" disabled>⏹️ توقف</button>
            </div>
        </div>

        <div class="progress-area">
            <div class="progress-bar-container">
                <div class="progress-fill" id="progressFill"></div>
            </div>
            <div class="progress-stats">
                <span id="progressPercent">۰%</span>
                <span id="progressCount">۰</span>
                <span>از <span id="totalCountLabel">۰</span> تکرار</span>
            </div>
        </div>

        <div class="output-container">
            <div class="output-header">
                <span>📦 خروجی</span>
                <div class="action-buttons">
                    <button id="copyOutputBtn" class="icon-btn icon-btn-copy">📋 کپی متن</button>
                    <button id="clearOutputBtn" class="icon-btn icon-btn-clear">🗑️ پاک کردن</button>
                </div>
            </div>
            <div id="outputBox" class="output-box">
                <span style="opacity:0.6; font-style:italic;">نتیجه تکرار اینجا ظاهر می‌شود...</span>
            </div>
        </div>
        <footer>✅ پردازش بچ‌های هوشمند &nbsp;|&nbsp; حداکثر ۲۵۰٬۰۰۰ تکرار ایمن</footer>
    </div>

    <script>
        const textInput = document.getElementById('textInput');
        const repeatCountInput = document.getElementById('repeatCount');
        const startBtn = document.getElementById('startBtn');
        const stopBtn = document.getElementById('stopBtn');
        const progressFill = document.getElementById('progressFill');
        const progressPercentSpan = document.getElementById('progressPercent');
        const progressCountSpan = document.getElementById('progressCount');
        const totalCountLabel = document.getElementById('totalCountLabel');
        const outputBox = document.getElementById('outputBox');
        const clearOutputBtn = document.getElementById('clearOutputBtn');
        const copyOutputBtn = document.getElementById('copyOutputBtn');

        let selectedMode = 'space';
        let isProcessing = false;
        let cancelFlag = false;

        function showToast(msg) {
            const old = document.querySelector('.toast-msg');
            if (old) old.remove();
            const div = document.createElement('div');
            div.className = 'toast-msg';
            div.textContent = msg;
            document.body.appendChild(div);
            setTimeout(() => div.remove(), 2200);
        }

        document.querySelectorAll('input[name="repeatMode"]').forEach(r => {
            r.addEventListener('change', e => { if (e.target.checked) selectedMode = e.target.value; });
        });
        selectedMode = document.querySelector('input[name="repeatMode"]:checked').value;

        function clearOutputArea() {
            outputBox.innerHTML = '<span style="opacity:0.6;font-style:italic;">نتیجه تکرار اینجا ظاهر می‌شود...</span>';
        }

        function appendBatch(startIndex, batchSize, total, mode, baseText) {
            if (cancelFlag) return false;
            if (startIndex === 0 && outputBox.children.length === 1 && outputBox.children[0].innerText.includes('نتیجه تکرار')) {
                outputBox.innerHTML = '';
            }
            const end = Math.min(startIndex + batchSize, total);
            const fragment = document.createDocumentFragment();

            if (mode === 'space' || mode === 'comma' || mode === 'dash') {
                let sep = mode === 'space' ? ' ' : (mode === 'comma' ? ' , ' : ' - ');
                let span = outputBox.querySelector('.dynamic-separator-span');
                if (!span) { span = document.createElement('span'); span.className = 'dynamic-separator-span'; outputBox.appendChild(span); }
                let chunk = '';
                for (let i = startIndex; i < end; i++) {
                    chunk += baseText;
                    if (i !== total - 1) chunk += sep;
                }
                span.textContent += chunk;
                outputBox.scrollTop = outputBox.scrollHeight;
                return true;
            } else if (mode === 'linebreak') {
                for (let i = startIndex; i < end; i++) {
                    const div = document.createElement('div');
                    div.className = 'break-item';
                    div.textContent = baseText;
                    fragment.appendChild(div);
                }
                outputBox.appendChild(fragment);
                outputBox.scrollTop = outputBox.scrollHeight;
                return true;
            } else if (mode === 'numbered') {
                for (let i = startIndex; i < end; i++) {
                    const div = document.createElement('div');
                    div.className = 'numbered-item';
                    div.textContent = `${i+1}. ${baseText}`;
                    fragment.appendChild(div);
                }
                outputBox.appendChild(fragment);
                outputBox.scrollTop = outputBox.scrollHeight;
                return true;
            }
            return false;
        }

        function trimTrailingSeparator() {
            const span = outputBox.querySelector('.dynamic-separator-span');
            if (!span) return;
            let txt = span.textContent;
            if (selectedMode === 'space' && txt.endsWith(' ')) span.textContent = txt.slice(0, -1);
            else if (selectedMode === 'comma' && txt.endsWith(' , ')) span.textContent = txt.slice(0, -3);
            else if (selectedMode === 'dash' && txt.endsWith(' - ')) span.textContent = txt.slice(0, -3);
        }

        async function startRepeat() {
            if (isProcessing) { cancelFlag = true; await new Promise(r => setTimeout(r, 100)); }
            const raw = textInput.value.trim();
            if (!raw) { showToast('❌ متن ورودی خالی است!'); return; }
            let total = parseInt(repeatCountInput.value) || 1;
            if (total < 1) total = 1;
            if (total > 280000 && !confirm(`تعداد ${total.toLocaleString()} ممکن است سنگین باشد. ادامه؟`)) return;

            cancelFlag = false;
            clearOutputArea();
            isProcessing = true;
            startBtn.disabled = true;
            stopBtn.disabled = false;

            totalCountLabel.innerText = total.toLocaleString();
            let processed = 0;
            const updateProgress = () => {
                const pct = total === 0 ? 0 : (processed / total) * 100;
                progressFill.style.width = `${pct}%`;
                progressPercentSpan.innerText = `${Math.floor(pct)}%`;
                progressCountSpan.innerText = processed.toLocaleString();
            };
            updateProgress();

            const getChunk = () => {
                if (total <= 1000) return 45;
                if (total <= 6000) return 100;
                if (total <= 25000) return 200;
                if (total <= 80000) return 380;
                return 550;
            };

            let idx = 0;
            while (idx < total && !cancelFlag) {
                const chunk = getChunk();
                const next = Math.min(idx + chunk, total);
                appendBatch(idx, next - idx, total, selectedMode, raw);
                processed = next;
                idx = next;
                updateProgress();
                if (idx >= total) break;
                await new Promise(r => setTimeout(r, 7));
            }

            if (cancelFlag) {
                showToast('⛔ عملیات لغو شد');
                progressPercentSpan.innerText = 'لغو شد';
            } else {
                if (['space', 'comma', 'dash'].includes(selectedMode)) trimTrailingSeparator();
                progressFill.style.width = '100%';
                progressPercentSpan.innerText = '۱۰۰%';
                progressCountSpan.innerText = total.toLocaleString();
                showToast(`✅ تکمیل شد! ${total.toLocaleString()} بار`);
            }

            isProcessing = false;
            startBtn.disabled = false;
            stopBtn.disabled = true;
            cancelFlag = false;
        }

        function cancelProcess() {
            if (isProcessing) { cancelFlag = true; showToast('درخواست توقف ثبت شد'); }
        }

        startBtn.addEventListener('click', startRepeat);
        stopBtn.addEventListener('click', cancelProcess);

        clearOutputBtn.addEventListener('click', () => {
            if (isProcessing) { showToast('ابتدا توقف کنید'); return; }
            clearOutputArea();
        });

        copyOutputBtn.addEventListener('click', async () => {
            if (isProcessing) { showToast('لحظاتی صبر کنید'); return; }
            const txt = outputBox.innerText.trim();
            if (!txt || txt === 'نتیجه تکرار اینجا ظاهر می‌شود...') { showToast('خروجی خالی است'); return; }
            try {
                await navigator.clipboard.writeText(txt);
                showToast('✅ کپی شد!');
            } catch {
                const ta = document.createElement('textarea');
                ta.value = txt;
                document.body.appendChild(ta);
                ta.select();
                document.execCommand('copy');
                document.body.removeChild(ta);
                showToast('✅ کپی شد!');
            }
        });

        repeatCountInput.addEventListener('change', () => {
            let v = parseInt(repeatCountInput.value) || 1;
            if (v < 1) v = 1;
            if (v > 500000) v = 500000;
            repeatCountInput.value = v;
            totalCountLabel.innerText = v.toLocaleString();
        });
        repeatCountInput.dispatchEvent(new Event('change'));

        document.querySelectorAll('.mode-option').forEach(opt => {
            opt.addEventListener('click', () => {
                const radio = opt.querySelector('input[type="radio"]');
                if (radio && !radio.checked) { radio.checked = true; radio.dispatchEvent(new Event('change')); }
            });
        });
    </script>
</body>
</html>