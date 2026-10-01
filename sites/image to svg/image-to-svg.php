<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="icon" href="fav.png" type="image/png" />
    <title>تبدیل عکس به SVG + فیلترهای حرفه‌ای</title>
    <style>
        @font-face { font-family: titr-bold; src: url(B\ Titr\ Bold_0.ttf); }
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: titr-bold; }
        body {
            background: linear-gradient(145deg, #eef2fa 0%, #dce3ef 100%);
            min-height: 100vh;
            padding: 2rem 1.5rem;
        }
        .container {
            max-width: 1400px;
            margin: 0 auto;
            background: rgba(250, 252, 255, 0.65);
            backdrop-filter: blur(3px);
            border-radius: 64px;
            padding: 2rem;
        }
        h1 {
            font-size: 2rem;
            margin-bottom: 0.5rem;
            background: linear-gradient(130deg, #1f2e3c, #0a2b3a);
            background-clip: text;
            -webkit-background-clip: text;
            color: transparent;
        }
        .subtitle { color: #1a2c38; margin-bottom: 2rem; font-size: 0.9rem; opacity: 0.8; }
        .upload-area {
            background: #0f1a24e0;
            border-radius: 48px;
            padding: 2rem;
            text-align: center;
            border: 2px dashed #ffb347;
            cursor: pointer;
            transition: all 0.3s;
            margin-bottom: 2rem;
        }
        .upload-area:hover { background: #0f1a24; border-color: #ffa01a; transform: scale(0.98); }
        .upload-icon { font-size: 4rem; margin-bottom: 1rem; }
        .upload-text { color: #ddf4ff; font-size: 1.2rem; margin-bottom: 0.5rem; }
        .upload-hint { color: #ffb347; font-size: 0.85rem; }
        #fileInput { display: none; }
        .preview-section { display: none; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 2rem; }
        .preview-box { background: #0f1a24e0; border-radius: 28px; padding: 1rem; }
        .preview-title { color: #ffb347; margin-bottom: 1rem; font-size: 1.1rem; }
        .preview-image { background: #030e16; border-radius: 20px; padding: 1rem; text-align: center; min-height: 200px; display: flex; align-items: center; justify-content: center; }
        .preview-image img, .preview-image svg { max-width: 100%; max-height: 300px; border-radius: 12px; }
        .svg-code-section { background: #0f1a24e0; border-radius: 28px; padding: 1.5rem; margin-top: 1.5rem; }
        .section-title { color: #ffb347; margin-bottom: 1rem; font-size: 1.1rem; }
        .code-container { background: #030e16; border-radius: 20px; padding: 1rem; position: relative; }
        pre { color: #ffefc0; font-family: monospace; font-size: 0.8rem; overflow-x: auto; white-space: pre-wrap; word-wrap: break-word; max-height: 300px; overflow-y: auto; }
        .copy-btn-wrapper { text-align: left; margin-bottom: 0.5rem; }
        .copy-btn {
            background: #ffb347;
            border: none;
            padding: 8px 20px;
            border-radius: 40px;
            cursor: pointer;
            font-family: titr-bold;
            font-size: 0.9rem;
            transition: 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        .copy-btn:hover { background: #ffa01a; transform: scale(0.96); }
        .download-btn {
            background: #ffb347;
            border: none;
            padding: 12px 30px;
            border-radius: 40px;
            cursor: pointer;
            font-family: titr-bold;
            margin-top: 1rem;
            width: 100%;
            transition: 0.2s;
        }
        .download-btn:hover { background: #ffa01a; transform: scale(0.98); }
        .settings { background: #0f1a24e0; border-radius: 28px; padding: 1.5rem; margin-bottom: 1.5rem; display: none; }
        .settings-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1rem; }
        .setting-item { display: flex; flex-direction: column; gap: 0.5rem; }
        .setting-item label { color: #ffb347; font-size: 0.9rem; }
        select, input { background: #030e16; color: #ddf4ff; border: none; padding: 8px 15px; border-radius: 20px; cursor: pointer; }
        .quality-badge { color: #4caf50; font-size: 0.85rem; margin-top: 0.5rem; }
        .loading { text-align: center; padding: 2rem; color: #ffb347; }
        .usage-guide { background: linear-gradient(135deg, #0f1a24, #0a1219); border-radius: 28px; padding: 1.5rem; margin-top: 2rem; }
        .usage-guide h3 { color: #ffb347; margin-bottom: 1rem; }
        .usage-guide code { background: #030e16; padding: 0.5rem; border-radius: 10px; display: inline-block; margin: 0.5rem 0; color: #ffefc0; font-family: monospace; }
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
        @media (max-width: 768px) { .preview-section { grid-template-columns: 1fr; } .container { padding: 1rem; } h1 { font-size: 1.5rem; } }
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
        <h1>تبدیل عکس به SVG + فیلترهای حرفه‌ای</h1>
        <div class="subtitle">تبدیل PNG, JPG, JPEG, WEBP, BMP به SVG با حفظ کیفیت + اعمال فیلترهای پیشرفته</div>

        <div class="upload-area" onclick="document.getElementById('fileInput').click()">
            <div class="upload-icon">📸</div>
            <div class="upload-text">برای آپلود عکس کلیک کنید</div>
            <div class="upload-hint">فرمت‌های مجاز: PNG, JPG, JPEG, WEBP, BMP</div>
        </div>
        <input type="file" id="fileInput" accept="image/png,image/jpeg,image/jpg,image/webp,image/bmp" onchange="handleUpload(event)" />

        <div class="settings" id="settingsPanel">
            <div class="settings-grid">
                <div class="setting-item">
                    <label>🔧 کیفیت تبدیل:</label>
                    <select id="quality"><option value="high">کیفیت بالا</option><option value="medium" selected>کیفیت متوسط</option><option value="low">کیفیت معمولی</option></select>
                </div>
                <div class="setting-item">
                    <label>🎨 رنگ‌بندی:</label>
                    <select id="colorMode"><option value="color" selected>رنگی</option><option value="grayscale">طوسی</option><option value="blackwhite">سیاه و سفید</option><option value="sepia">سپیا</option><option value="invert">نگاتیو</option></select>
                </div>
                <div class="setting-item">
                    <label>🖼️ فیلتر تصویر:</label>
                    <select id="filter"><option value="none">بدون فیلتر</option><option value="blur">محو</option><option value="brightness">روشنایی</option><option value="contrast">کنتراست</option><option value="saturation">اشباع</option><option value="edge">لبه‌یاب</option><option value="sharpen">تیزکننده</option><option value="emboss">برجسته</option></select>
                </div>
                <div class="setting-item">
                    <label>📏 اندازه:</label>
                    <select id="size"><option value="auto">اندازه اصلی</option><option value="icon">آیکون (64x64)</option><option value="small">کوچک (128x128)</option><option value="medium">متوسط (256x256)</option><option value="large">بزرگ (512x512)</option></select>
                </div>
            </div>
            <div class="quality-badge" id="qualityBadge">✨ کیفیت اصلی تصویر حفظ می‌شود</div>
        </div>

        <div class="preview-section" id="previewSection">
            <div class="preview-box">
                <div class="preview-title">📷 عکس اصلی</div>
                <div class="preview-image" id="originalPreview"></div>
                <div class="quality-badge" id="originalInfo"></div>
            </div>
            <div class="preview-box">
                <div class="preview-title">🎨 SVG نهایی</div>
                <div class="preview-image" id="svgPreview"></div>
                <button class="download-btn" onclick="downloadSVG()">⬇️ دانلود فایل SVG</button>
                <button class="download-btn" onclick="downloadPNG()" style="background:#2196f3;margin-top:0.5rem;">📸 دانلود PNG</button>
            </div>
        </div>

        <div class="svg-code-section" id="codeSection" style="display:none;">
            <div class="section-title">📄 کد SVG</div>
            <div class="copy-btn-wrapper"><button class="copy-btn" onclick="copySVG()">📋 کپی کد SVG</button></div>
            <div class="code-container"><pre id="svgCode"></pre></div>
            <div class="section-title" style="margin-top:1rem;">🎨 کد CSS</div>
            <div class="copy-btn-wrapper"><button class="copy-btn" onclick="copyCSS()">📋 کپی CSS</button></div>
            <div class="code-container"><pre id="cssCode"></pre></div>
        </div>

        <div class="usage-guide">
            <h3>📚 آموزش استفاده از SVG در سایت</h3>
            <p style="color:#ddf4ff;margin-bottom:1rem;">3 روش مختلف برای استفاده از کد SVG در سایت:</p>
            <h4 style="color:#ffb347;margin-top:1rem;">روش اول: مستقیم در HTML</h4>
            <code>&lt;img src="data:image/svg+xml;utf8,&lt;svg...&lt;/svg&gt;" alt="icon"&gt;</code>
            <h4 style="color:#ffb347;margin-top:1rem;">روش دوم: فایل جداگانه SVG</h4>
            <code>&lt;img src="images/icon.svg" alt="icon"&gt;</code>
            <h4 style="color:#ffb347;margin-top:1rem;">روش سوم: Inline SVG</h4>
            <code>&lt;svg width="50" height="50" viewBox="0 0 100 100"&gt;...&lt;/svg&gt;</code>
        </div>
    </div>

    <script>
        let currentSVG = null, currentImageData = null;
        let currentWidth = 0, currentHeight = 0;

        function showToast(msg) {
            const old = document.querySelector('.toast-msg');
            if (old) old.remove();
            const div = document.createElement('div');
            div.className = 'toast-msg';
            div.textContent = msg;
            document.body.appendChild(div);
            setTimeout(() => div.remove(), 2200);
        }

        function handleUpload(e) {
            const file = e.target.files[0];
            if (!file) return;
            const valid = ['image/png','image/jpeg','image/jpg','image/webp','image/bmp'];
            if (!valid.includes(file.type)) { showToast('فرمت پشتیبانی نمی‌شود'); return; }
            const reader = new FileReader();
            reader.onload = function(ev) {
                currentImageData = ev.target.result;
                showOriginal(currentImageData);
                convertToSVG(currentImageData);
            };
            reader.readAsDataURL(file);
        }

        function showOriginal(dataUrl) {
            document.getElementById('originalPreview').innerHTML = `<img src="${dataUrl}" alt="Original" />`;
            const img = new Image();
            img.onload = function() {
                document.getElementById('originalInfo').innerHTML = `📏 ابعاد: ${img.width}×${img.height}`;
                currentWidth = img.width; currentHeight = img.height;
            };
            img.src = dataUrl;
            document.getElementById('previewSection').style.display = 'grid';
            document.getElementById('settingsPanel').style.display = 'block';
        }

        function applyFilter(imageData, filterType) {
            const data = imageData.data;
            const w = imageData.width, h = imageData.height;
            switch(filterType) {
                case 'blur':
                    const td = new Uint8ClampedArray(data);
                    for (let y=1; y<h-1; y++) for (let x=1; x<w-1; x++) {
                        const idx = (y*w+x)*4;
                        for (let c=0; c<3; c++) {
                            let sum=0;
                            for (let dy=-1; dy<=1; dy++) for (let dx=-1; dx<=1; dx++) {
                                sum += td[((y+dy)*w+(x+dx))*4 + c];
                            }
                            data[idx+c] = sum/9;
                        }
                    }
                    break;
                case 'brightness':
                    for (let i=0; i<data.length; i+=4) {
                        data[i]=Math.min(255,data[i]+50); data[i+1]=Math.min(255,data[i+1]+50); data[i+2]=Math.min(255,data[i+2]+50);
                    }
                    break;
                case 'contrast':
                    for (let i=0; i<data.length; i+=4) {
                        data[i]=Math.min(255,Math.max(0,(data[i]-128)*1.5+128));
                        data[i+1]=Math.min(255,Math.max(0,(data[i+1]-128)*1.5+128));
                        data[i+2]=Math.min(255,Math.max(0,(data[i+2]-128)*1.5+128));
                    }
                    break;
                case 'saturation':
                    for (let i=0; i<data.length; i+=4) {
                        const g = (data[i]+data[i+1]+data[i+2])/3;
                        data[i]=Math.min(255,g*0.3+data[i]*0.7);
                        data[i+1]=Math.min(255,g*0.3+data[i+1]*0.7);
                        data[i+2]=Math.min(255,g*0.3+data[i+2]*0.7);
                    }
                    break;
                case 'edge':
                    for (let i=0; i<data.length; i+=4) {
                        const g = (data[i]+data[i+1]+data[i+2])/3;
                        data[i]=data[i+1]=data[i+2]=g>128?255:0;
                    }
                    break;
                case 'sharpen':
                    const sd = new Uint8ClampedArray(data);
                    for (let y=1; y<h-1; y++) for (let x=1; x<w-1; x++) {
                        const idx = (y*w+x)*4;
                        for (let c=0; c<3; c++) {
                            let val = sd[idx+c]*5 - sd[(y-1)*w*4+(x)*4+c] - sd[(y+1)*w*4+(x)*4+c] - sd[idx-4+c] - sd[idx+4+c];
                            data[idx+c] = Math.min(255,Math.max(0,val));
                        }
                    }
                    break;
                case 'emboss':
                    for (let y=1; y<h-1; y++) for (let x=1; x<w-1; x++) {
                        const idx = (y*w+x)*4;
                        for (let c=0; c<3; c++) {
                            let val = data[idx+c]*2 - data[(y-1)*w*4+(x-1)*4+c] - data[(y-1)*w*4+(x+1)*4+c];
                            data[idx+c] = Math.min(255,Math.max(0,val+128));
                        }
                    }
                    break;
            }
            return imageData;
        }

        function getSize(opt, ow, oh) {
            const s = { icon:{w:64,h:64}, small:{w:128,h:128}, medium:{w:256,h:256}, large:{w:512,h:512}, auto:{w:ow,h:oh} };
            return s[opt] || s.auto;
        }

        function convertToSVG(dataUrl) {
            const quality = document.getElementById('quality').value;
            const colorMode = document.getElementById('colorMode').value;
            const filterType = document.getElementById('filter').value;
            const sizeOpt = document.getElementById('size').value;

            document.getElementById('svgPreview').innerHTML = '<div class="loading">🔄 در حال تبدیل...</div>';

            const img = new Image();
            img.onload = function() {
                const canvas = document.createElement('canvas');
                const ctx = canvas.getContext('2d');
                let w = img.width, h = img.height;
                const target = getSize(sizeOpt, w, h);
                w = target.w; h = target.h;
                let scale = 1;
                if (quality === 'medium') scale = Math.min(1, 800/Math.max(w,h));
                else if (quality === 'low') scale = Math.min(1, 400/Math.max(w,h));
                w = Math.floor(w*scale); h = Math.floor(h*scale);
                canvas.width = w; canvas.height = h;
                ctx.drawImage(img, 0, 0, w, h);

                let imageData = ctx.getImageData(0, 0, w, h);
                let data = imageData.data;

                if (colorMode === 'grayscale') {
                    for (let i=0; i<data.length; i+=4) {
                        const g = (data[i]+data[i+1]+data[i+2])/3;
                        data[i]=data[i+1]=data[i+2]=g;
                    }
                } else if (colorMode === 'blackwhite') {
                    for (let i=0; i<data.length; i+=4) {
                        const b = (data[i]+data[i+1]+data[i+2])/3 > 128 ? 255 : 0;
                        data[i]=data[i+1]=data[i+2]=b;
                    }
                } else if (colorMode === 'sepia') {
                    for (let i=0; i<data.length; i+=4) {
                        const r=data[i], g=data[i+1], b=data[i+2];
                        data[i]=Math.min(255, r*0.393+g*0.769+b*0.189);
                        data[i+1]=Math.min(255, r*0.349+g*0.686+b*0.168);
                        data[i+2]=Math.min(255, r*0.272+g*0.534+b*0.131);
                    }
                } else if (colorMode === 'invert') {
                    for (let i=0; i<data.length; i+=4) {
                        data[i]=255-data[i]; data[i+1]=255-data[i+1]; data[i+2]=255-data[i+2];
                    }
                }

                if (filterType !== 'none') imageData = applyFilter(imageData, filterType);
                ctx.putImageData(imageData, 0, 0);

                const pngData = canvas.toDataURL('image/png');
                const svgStr = `<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="${w}" height="${h}" viewBox="0 0 ${w} ${h}"><image href="${pngData}" width="${w}" height="${h}"/></svg>`;
                currentSVG = svgStr;

                document.getElementById('svgPreview').innerHTML = svgStr;
                document.getElementById('svgCode').innerText = svgStr;
                document.getElementById('cssCode').innerHTML = generateCSS();
                document.getElementById('codeSection').style.display = 'block';
                document.getElementById('qualityBadge').innerHTML = `✅ حجم SVG: ${(svgStr.length/1024).toFixed(1)} KB | ابعاد: ${w}×${h}`;
            };
            img.src = dataUrl;
        }

        function generateCSS() {
            return `.icon-svg { width:50px; height:50px; transition: transform 0.3s; }
.icon-svg:hover { transform: scale(1.1); }
.icon-svg path { fill: #ffb347; transition: fill 0.3s; }
.icon-svg:hover path { fill: #ffa01a; }
.icon-svg { filter: drop-shadow(2px 4px 6px rgba(0,0,0,0.3)); }`;
        }

        function downloadSVG() {
            if (!currentSVG) { showToast('هیچ تصویری برای دانلود وجود ندارد'); return; }
            const blob = new Blob([currentSVG], { type: 'image/svg+xml' });
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url; a.download = `icon_${Date.now()}.svg`;
            document.body.appendChild(a); a.click(); document.body.removeChild(a);
            URL.revokeObjectURL(url);
        }

        function downloadPNG() {
            const svg = document.querySelector('#svgPreview svg');
            if (!svg) { showToast('هیچ تصویری وجود ندارد'); return; }
            const serializer = new XMLSerializer();
            const svgStr = serializer.serializeToString(svg);
            const img = new Image();
            img.onload = function() {
                const canvas = document.createElement('canvas');
                canvas.width = img.width; canvas.height = img.height;
                const ctx = canvas.getContext('2d');
                ctx.drawImage(img, 0, 0);
                const a = document.createElement('a');
                a.href = canvas.toDataURL('image/png');
                a.download = `icon_${Date.now()}.png`;
                document.body.appendChild(a); a.click(); document.body.removeChild(a);
            };
            img.src = 'data:image/svg+xml;utf8,' + encodeURIComponent(currentSVG);
        }

        function copySVG() {
            const code = document.getElementById('svgCode').innerText;
            if (!code) { showToast('کدی برای کپی وجود ندارد'); return; }
            navigator.clipboard.writeText(code).then(() => showToast('✅ کد SVG کپی شد!'))
                .catch(() => { const ta=document.createElement('textarea'); ta.value=code; document.body.appendChild(ta); ta.select(); document.execCommand('copy'); document.body.removeChild(ta); showToast('✅ کپی شد!'); });
        }

        function copyCSS() {
            const code = document.getElementById('cssCode').innerText;
            if (!code) { showToast('کدی برای کپی وجود ندارد'); return; }
            navigator.clipboard.writeText(code).then(() => showToast('✅ کد CSS کپی شد!'))
                .catch(() => { const ta=document.createElement('textarea'); ta.value=code; document.body.appendChild(ta); ta.select(); document.execCommand('copy'); document.body.removeChild(ta); showToast('✅ کپی شد!'); });
        }

        ['quality','colorMode','filter','size'].forEach(id => {
            document.getElementById(id).addEventListener('change', () => { if (currentImageData) convertToSVG(currentImageData); });
        });
    </script>
</body>
</html>