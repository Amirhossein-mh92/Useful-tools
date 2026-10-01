<!doctype html>
<html><!-- Coded By MoFraD - نهایی با اسکریپت اصلی و اصلاح دکمه‌ها -->

<head>
    <title>Farsi Text Fixed</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="fav.jpeg" type="image/png">
    <style>
        @font-face {
            font-family: titr-bold;
            src: url('B Titr Bold_0.ttf');
        }
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: titr-bold, 'Vazirmatn', system-ui, sans-serif;
        }
        body {
            background: linear-gradient(145deg, #12161c 0%, #1a1f2a 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        .glass-card {
            background: rgba(30, 35, 48, 0.75);
            backdrop-filter: blur(12px);
            border-radius: 48px;
            padding: 32px 24px;
            box-shadow: 0 25px 45px rgba(0,0,0,0.4), 0 0 0 1px rgba(255,255,200,0.1);
            width: 100%;
            max-width: 650px;
        }
        h1 {
            font-size: 1.7rem;
            text-align: center;
            color: #ffec99;
            margin-bottom: 8px;
        }
        .sub {
            text-align: center;
            color: #b9c7d9;
            margin-bottom: 28px;
            font-size: 0.9rem;
            border-bottom: 1px dashed #4a5568;
            display: inline-block;
            width: auto;
            margin-left: auto;
            margin-right: auto;
            padding-bottom: 6px;
        }
        .input-group {
            margin-bottom: 24px;
        }
        label {
            display: block;
            color: #ffec99;
            font-weight: bold;
            margin-bottom: 8px;
        }
        textarea, input[type="text"] {
            width: 100%;
            border: 2px solid #2d3748;
            border-radius: 28px;
            background: #0f1219;
            padding: 14px 20px;
            color: #f0f3fa;
            font-size: 1.2rem;
            transition: all 0.2s;
            outline: none;
            resize: vertical;
        }
        textarea:focus, input:focus {
            border-color: #ffd966;
            box-shadow: 0 0 0 3px rgba(255,217,102,0.3);
        }
        #resultbox {
            direction: ltr;
            text-align: left;
            background: #0b0e14;
            font-size: 1.3rem;
            letter-spacing: 1px;
        }
        .button-group {
            display: flex;
            flex-wrap: wrap;
            gap: 14px;
            margin: 28px 0 20px;
            justify-content: center;
        }
        .btn {
            border: none;
            background: #ffea80;
            color: #1e293b;
            font-weight: bold;
            padding: 12px 24px;
            border-radius: 40px;
            font-size: 1rem;
            cursor: pointer;
            transition: 0.2s;
            box-shadow: 0 5px 12px rgba(0,0,0,0.3);
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        .btn-copy {
            background: #2b9348;
            color: white;
        }
        .btn-clear {
            background: #dc2f02;
            color: white;
        }
        .btn:hover {
            transform: translateY(-2px);
            filter: brightness(1.05);
        }
        .message {
            text-align: center;
            font-size: 0.85rem;
            padding: 8px;
            border-radius: 40px;
            background: #2c3e2e80;
            color: #c7f9cc;
            display: none;
            margin-top: 16px;
        }
        .credit {
            text-align: center;
            margin-top: 28px;
            font-size: 0.7rem;
            color: #6c7a91;
        }
        @media (max-width: 550px) {
            .glass-card { padding: 20px 16px; }
            .btn { padding: 8px 18px; font-size: 0.9rem; }
            textarea, input { font-size: 1rem; }
            h1 { font-size: 1.4rem; }
        }
        #outbox {
            display: none;
        }
        #message {
            display: none;
        }
        /* استایل جدید برای بخش آپلود فایل زبان */
        .lang-upload-section {
            margin-top: 20px;
            padding: 15px;
            background: rgba(0,0,0,0.3);
            border-radius: 24px;
            border: 1px dashed #ffd966;
        }
        .lang-upload-section label {
            color: #ffec99;
            font-weight: bold;
            display: block;
            margin-bottom: 8px;
        }
        .lang-upload-section input[type="file"] {
            width: 100%;
            padding: 10px;
            border-radius: 20px;
            background: #0f1219;
            color: #f0f3fa;
            border: 2px solid #2d3748;
            font-size: 0.9rem;
        }
        .lang-upload-section textarea {
            width: 100%;
            padding: 12px 18px;
            border-radius: 20px;
            background: #0f1219;
            color: #f0f3fa;
            border: 2px solid #2d3748;
            font-size: 0.9rem;
            resize: vertical;
            min-height: 120px;
            margin-top: 10px;
        }
        .lang-upload-section .btn {
            margin-top: 10px;
            padding: 8px 20px;
            font-size: 0.9rem;
        }
    </style>
    <script>
        // ========== اسکریپت اصلی شما (فقط حذف خط برعکس کردن) ==========
        var _0x4af261 = _0x5b51; (function (_0x5cbceb, _0x434c17) { var _0x411a67 = _0x5b51, _0x13983f = _0x5cbceb(); while (!![]) { try { var _0x4a3b25 = parseInt(_0x411a67(0x168)) / 0x1 + -parseInt(_0x411a67(0x18b)) / 0x2 * (-parseInt(_0x411a67(0x161)) / 0x3) + -parseInt(_0x411a67(0x1a4)) / 0x4 * (parseInt(_0x411a67(0x188)) / 0x5) + parseInt(_0x411a67(0x185)) / 0x6 + -parseInt(_0x411a67(0x1a0)) / 0x7 * (parseInt(_0x411a67(0x18a)) / 0x8) + parseInt(_0x411a67(0x191)) / 0x9 + parseInt(_0x411a67(0x1a6)) / 0xa; if (_0x4a3b25 === _0x434c17) break; else _0x13983f['push'](_0x13983f['shift']()); } catch (_0x73df8c) { _0x13983f['push'](_0x13983f['shift']()); } } }(_0xba78, 0xa973a)); var e_harakat = 0x1, dir = _0x4af261(0x178), old = '', tstr = '', csr1 = csr2 = 0x0, laIndex = 0xd0, left = 'ڤـئظشسيیبلپتنمككگطضصثقفغعهخحچجٹہےڈڑۇۆۈک', right = _0x4af261(0x189), harakat = _0x4af261(0x165), symbols = 'ـ.،؟\x20@#$%^&*-+|/=~,:', unicode = _0x4af261(0x17d) + _0x4af261(0x1a2) + _0x4af261(0x198) + _0x4af261(0x166) + _0x4af261(0x16b) + _0x4af261(0x19e) + _0x4af261(0x193) + _0x4af261(0x195) + _0x4af261(0x1aa) + _0x4af261(0x187) + 'ﺩﺩﺪﺪ' + _0x4af261(0x172) + _0x4af261(0x16d) + _0x4af261(0x167) + 'ﺱﺳﺴﺲ' + 'ﺵﺷﺸﺶ' + 'ﺹﺻﺼﺺ' + _0x4af261(0x17f) + _0x4af261(0x19f) + _0x4af261(0x1a3) + 'ﻉﻋﻌﻊ' + _0x4af261(0x192) + 'ﻑﻓﻔﻒ' + _0x4af261(0x1ad) + _0x4af261(0x15c) + _0x4af261(0x15d) + _0x4af261(0x18c) + _0x4af261(0x19b) + _0x4af261(0x179) + _0x4af261(0x19c) + _0x4af261(0x180) + _0x4af261(0x1ac) + _0x4af261(0x199) + _0x4af261(0x1a8) + _0x4af261(0x177) + _0x4af261(0x18f) + 'چﭼﭽﭻ' + _0x4af261(0x1ab) + _0x4af261(0x183) + _0x4af261(0x182) + _0x4af261(0x16e) + _0x4af261(0x160) + 'ےﮰﮱﮯ' + _0x4af261(0x15b) + _0x4af261(0x171) + 'ڑﮌﮍﮍ' + _0x4af261(0x15f) + 'ۆﯙﯚﯚ' + _0x4af261(0x1a1) + _0x4af261(0x17a) + _0x4af261(0x176) + 'ﻷﻷﻸﻸ' + _0x4af261(0x163) + _0x4af261(0x184), arabic = 'آ' + 'أ' + 'إ' + 'ا' + 'ب' + 'ت' + 'ث' + 'ج' + 'ح' + 'خ' + 'د' + 'ذ' + 'ر' + 'ز' + 'س' + 'ش' + 'ص' + 'ض' + 'ط' + 'ظ' + 'ع' + 'غ' + 'ف' + 'ق' + 'ك' + 'ل' + 'م' + 'ن' + 'ه' + 'و' + 'ي' + 'ة' + 'ؤ' + 'ئ' + 'ى' + 'پ' + 'چ' + 'ژ' + 'ڤ' + 'گ' + 'ٹ' + 'ہ' + 'ے' + 'ی' + 'ڈ' + 'ڑ' + 'ۇ' + 'ۆ' + 'ۈ' + 'ک', notEng = arabic + harakat + _0x4af261(0x17b), brackets = _0x4af261(0x196), msie = opera = 0x0, agent = navigator[_0x4af261(0x174)]; if (agent[_0x4af261(0x16f)]('MSIE') >= 0x0) msie = 0x1; if (agent[_0x4af261(0x16f)](_0x4af261(0x18e)) >= 0x0) opera = 0x1;
        
        // ================================================================
        // تابع ProcessInput بازنویسی شده با اصلاح کامل "لا" و ترتیب صحیح
        // ================================================================
        function ProcessInput() { 
            var _0x447a02 = _0x4af261;
            var inp = document.getElementById('inpbox');
            var out = document.getElementById('outbox');
            if (!out) return;
            out.value = '';
            old = '';
            tstr = '';
            var y = inp.value;
            var x = y.split('');
            var len = x.length;
            var result = '';
            
            // تابع کمکی برای افزودن کاراکتر به خروجی (برعکس)
            function addCharRev(ch) {
                result = ch + result;
            }
            
            for (var g = 0; g < len; g++) {
                var ch = x[g];
                var nextCh = (g + 1 < len) ? x[g + 1] : '';
                var prevCh = (g > 0) ? x[g - 1] : '';
                
                // ====== مدیریت خاص "لا" ======
                if (ch === 'ل' && (nextCh === 'ا' || nextCh === 'آ' || nextCh === 'أ' || nextCh === 'إ')) {
                    // لا، لآ، لأ، لإ
                    var prevIsConnect = (g > 0 && left.indexOf(prevCh) >= 0);
                    var nextIsConnect = (g + 2 < len && right.indexOf(x[g + 2]) >= 0);
                    
                    var lamForm = 'ﻟ';
                    if (!prevIsConnect && !nextIsConnect) {
                        lamForm = 'ﻝ';
                    } else if (prevIsConnect && nextIsConnect) {
                        lamForm = 'ﻟ';
                    } else if (prevIsConnect && !nextIsConnect) {
                        lamForm = 'ﻝ';
                    } else {
                        lamForm = 'ﻟ';
                    }
                    
                    var alefForm = '';
                    if (nextCh === 'ا') alefForm = 'ﺍ';
                    else if (nextCh === 'آ') alefForm = 'ﺁ';
                    else if (nextCh === 'أ') alefForm = 'ﺃ';
                    else if (nextCh === 'إ') alefForm = 'ﺇ';
                    
                    addCharRev(lamForm);
                    addCharRev(alefForm);
                    g++;
                    continue;
                }
                
                // ====== مدیریت حروف عادی ======
                var arPos = arabic.indexOf(ch);
                if (arPos >= 0) {
                    var prevIsConnect = (g > 0 && left.indexOf(x[g - 1]) >= 0);
                    var nextIsConnect = (g + 1 < len && right.indexOf(x[g + 1]) >= 0);
                    
                    var formIndex = 0;
                    if (prevIsConnect && nextIsConnect) formIndex = 2;
                    else if (prevIsConnect && !nextIsConnect) formIndex = 3;
                    else if (!prevIsConnect && nextIsConnect) formIndex = 1;
                    else formIndex = 0;
                    
                    var uniIndex = arPos * 4 + formIndex;
                    if (uniIndex < unicode.length) {
                        addCharRev(unicode.charAt(uniIndex));
                    } else {
                        addCharRev(ch);
                    }
                    continue;
                }
                
                // ====== مدیریت سایر کاراکترها ======
                var uniPos = unicode.indexOf(ch);
                if (uniPos >= 0) {
                    addCharRev(ch);
                    continue;
                }
                
                addCharRev(ch);
            }
            
            out.value = result;
            var resultBox = document.getElementById('resultbox');
            if (resultBox) resultBox.value = result;
            showMessage();
        }
        
        // ================================================================
        // تابع جدید برای پردازش فایل زبان (.lang)
        // ================================================================
        function ProcessLangFile() {
            var fileInput = document.getElementById('langFileInput');
            var resultArea = document.getElementById('langResult');
            
            if (!fileInput.files || fileInput.files.length === 0) {
                showToast('⚠️ لطفاً یک فایل .lang انتخاب کنید');
                return;
            }
            
            var file = fileInput.files[0];
            var reader = new FileReader();
            
            reader.onload = function(e) {
                var content = e.target.result;
                var lines = content.split('\n');
                var processedLines = [];
                var lineNumber = 0;
                var totalFixed = 0;
                
                for (var i = 0; i < lines.length; i++) {
                    var line = lines[i];
                    var originalLine = line;
                    
                    // بررسی اینکه خط شامل '=' باشد و کامنت نباشد
                    if (line.indexOf('=') > 0 && line.trim().indexOf('#') !== 0) {
                        var parts = line.split('=');
                        if (parts.length >= 2) {
                            var key = parts[0].trim();
                            var value = parts.slice(1).join('=').trim();
                            
                            // حذف کامنت‌های انتهایی برای پردازش
                            var comment = '';
                            var valueClean = value;
                            var commentIndex = value.lastIndexOf('#');
                            if (commentIndex > 0) {
                                // بررسی اینکه # داخل رشته نباشد
                                var beforeComment = value.substring(0, commentIndex);
                                if (beforeComment.indexOf('"') === -1 && beforeComment.indexOf("'") === -1) {
                                    comment = value.substring(commentIndex);
                                    valueClean = beforeComment.trim();
                                }
                            }
                            
                            // اگر مقدار فارسی دارد، برعکسش کن
                            if (valueClean && /[\u0600-\u06FF]/.test(valueClean)) {
                                // ذخیره موقت برای برعکس کردن
                                var tempInp = document.getElementById('inpbox');
                                var tempOut = document.getElementById('outbox');
                                var originalValue = tempInp.value;
                                
                                tempInp.value = valueClean;
                                ProcessInput();
                                var reversedValue = tempOut.value;
                                
                                tempInp.value = originalValue;
                                
                                // بازسازی خط با مقدار برعکس شده
                                var newLine = key + '=' + reversedValue;
                                if (comment) {
                                    newLine += '\t' + comment;
                                }
                                processedLines.push(newLine);
                                totalFixed++;
                            } else {
                                processedLines.push(originalLine);
                            }
                        } else {
                            processedLines.push(originalLine);
                        }
                    } else {
                        processedLines.push(originalLine);
                    }
                }
                
                var result = processedLines.join('\n');
                resultArea.value = result;
                showToast('✅ فایل با موفقیت پردازش شد! ' + totalFixed + ' خط اصلاح شد.');
            };
            
            reader.onerror = function() {
                showToast('❌ خطا در خواندن فایل');
            };
            
            reader.readAsText(file);
        }
        
        // ================================================================
        // تابع کپی کردن نتیجه فایل زبان
        // ================================================================
        function copyLangResult() {
            var resultArea = document.getElementById('langResult');
            if (resultArea && resultArea.value) {
                copyclip(resultArea.value);
                showToast('✅ کد فایل زبان کپی شد!');
            } else {
                showToast('⚠️ چیزی برای کپی وجود ندارد');
            }
        }
        
        // ================================================================
        // توابع کمکی
        // ================================================================
        function addChar(_0x58402d) { 
            var _0x43f65f = document.getElementById('outbox'); 
            if (_0x43f65f) _0x43f65f['value'] = _0x58402d + _0x43f65f['value']; 
        } 
        
        function update(_0x5ac43f) { return true; } 
        function getSelectionStart(_0x321cad) { return 0; } 
        function getSelectionEnd(_0x42231a) { return 0; } 
        
        function copyclip(_0x1ddc84) { 
            if (navigator['clipboard']) navigator['clipboard']['writeText'](_0x1ddc84); 
            else window['clipboardData']['setData']('text', _0x1ddc84); 
        } 
        
        function _0xba78() { 
            var _0x49b8cc = ['7545BNSkrR', 'ڤـئؤرلالآىیآةوزژظشسيپبللأاأتنمككگطضصثقفغعهخحچجدذلإإٹہےڈڑۇۆۈک', '8nLyhiU', '25742fEuFoP', 'ﻡﻣﻤﻢ', 'duplicate', 'Opera', 'پﭘﭙﭗ', 'clipboardData', '5691879JhQmGl', 'ﻍﻏﻐﻎ', 'ﺙﺛﺜﺚ', 'substring', 'ﺝﺟﺠﺞ', '(){}[]', 'createTextRange', 'ﺇﺇﺈﺈ', 'ﺅﺅﺆﺆ', 'text', 'ﻥﻧﻨﻦ', 'ﻭﻭﻮﻮ', 'style', 'ﺕﺗﺘﺖ', 'ﻁﻃﻄﻂ', '5323577frXGWB', 'ۈﯛﯜﯜ', 'ﺃﺃﺄﺄ', 'ﻅﻇﻈﻆ', '236sucUjL', 'charAt', '2137050oqsYQI', 'getElementById', 'ﺉﺋﺌﺊ', 'selection', 'ﺡﺣﺤﺢ', 'ژﮊﮋﮋ', 'ﺓﺓﺔﺔ', 'ﻕﻗﻘﻖ', 'block', 'value', 'یﯾﯿﯽ', 'ﻙﻛﻜﻚ', 'ﻝﻟﻠﻞ', 'message', 'ۇﯗﯘﯘ', 'ہﮨﮩﮧ', '54DUUCcD', 'moveEnd', 'ﻹﻹﻺﻺ', 'selectionStart', 'ًٌٍَُِّْ', 'ﺍﺍﺎﺎ', 'ﺯﺯﺰﺰ', '227753NmyhXU', 'selectionEnd', 'createRange', 'ﺏﺑﺒﺐ', 'character', 'ﺭﺭﺮﺮ', 'ٹﭨﭩﭧ', 'indexOf', 'split', 'ڈﮈﮉﮉ', 'ﺫﺫﺬﺬ', 'outbox', 'userAgent', 'none', 'ﻵﻵﻶﻶ', 'ﻯﻯﻰﻰ', 'rtl', 'ﻩﻫﻬﻪ', 'کﮐﮑﮏ', 'ء،؟', 'display', 'ﺁﺁﺂﺂ', 'inpbox', 'ﺽﺿﻀﺾ', 'ﻱﻳﻴﻲ', 'length', 'گﮔﮕﮓ', 'ﭪﭬﭭﭫ', 'ﻻﻻﻼﻼ', '1428294dwryZv', 'lastIndexOf', 'ﺥﺧﺨﺦ']; 
            _0xba78 = function () { return _0x49b8cc; }; 
            return _0xba78(); 
        } 
        
        function _0x5b51(_0x37de25, _0x1ccabe) { 
            var _0xba78c3 = _0xba78(); 
            return _0x5b51 = function (_0x5b51e7, _0x166221) { 
                _0x5b51e7 = _0x5b51e7 - 0x15a; 
                var _0x2e3edc = _0xba78c3[_0x5b51e7]; 
                return _0x2e3edc; 
            }, _0x5b51(_0x37de25, _0x1ccabe); 
        } 
        
        function showMessage() { 
            var msg = document.getElementById('copyMessage'); 
            if (msg) { 
                msg.style.display = 'block'; 
                setTimeout(function () { msg.style.display = 'none'; }, 2000); 
            } 
        } 
        
        function copyResult() { 
            var res = document.getElementById('resultbox'); 
            if (res && res.value) { 
                res.select(); 
                copyclip(res.value); 
                var msg = document.getElementById('copyMessage'); 
                if (msg) { 
                    msg.style.display = 'block'; 
                    setTimeout(function () { msg.style.display = 'none'; }, 2000); 
                } 
            } 
        } 
        
        function clearInput() { 
            document.getElementById('inpbox').value = ''; 
            document.getElementById('outbox').value = ''; 
            document.getElementById('resultbox').value = ''; 
        }
        
        // تابع Toast ساده
        function showToast(msg) {
            var old = document.querySelector('.toast-msg');
            if (old) old.remove();
            var div = document.createElement('div');
            div.className = 'toast-msg';
            div.textContent = msg;
            div.style.cssText = 'position:fixed;bottom:30px;left:50%;transform:translateX(-50%);background:#1e293b;color:#fff;padding:10px 26px;border-radius:50px;font-size:0.9rem;z-index:99999;animation:toastFade 2.2s ease forwards;font-family:system-ui;backdrop-filter:blur(4px);border:1px solid rgba(255,255,200,0.2);';
            document.body.appendChild(div);
            setTimeout(function() { if (div.parentNode) div.remove(); }, 2200);
        }
        
        // اضافه کردن استایل انیمیشن Toast
        var styleToast = document.createElement('style');
        styleToast.textContent = '@keyframes toastFade { 0%{opacity:0;transform:translateX(-50%) translateY(20px);} 15%{opacity:1;transform:translateX(-50%) translateY(0);} 85%{opacity:1;transform:translateX(-50%) translateY(0);} 100%{opacity:0;transform:translateX(-50%) translateY(20px);} }';
        document.head.appendChild(styleToast);
    </script>
</head>

<body>
    <!-- دکمه ثابت بازگشت به صفحه اصلی -->
    <div style="position: fixed; top: 24px; left: 24px; z-index: 9999; direction: ltr;">
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
    " onmouseover="this.style.backgroundColor='#0d1c26'; this.style.transform='scale(0.97)';"
            onmouseout="this.style.backgroundColor='#1e2f3c'; this.style.transform='scale(1)';">
            🏠 بازگشت به صفحه اصلی
        </a>
    </div>

    <div class="glass-card">
        <h1>🔄 برعکس‌کننده متن فارسی</h1>
        <div style="text-align:center;"><span class="sub">ویژه بازی‌ها | خروجی آماده برای کپی</span></div>

        <form name='writer' id='writer' action='#' onsubmit="return false;">
            <div class="input-group">
                <label>📝 متن اصلی خود را وارد کنید :</label>
                <textarea name='inpbox' id='inpbox' dir='rtl' rows="2" maxlength="255" placeholder='متنی را وارد کنید....'></textarea>
            </div>

            <div class="button-group">
                <button type="button" id="processtxt" class="btn" onclick="ProcessInput();">✨ تبدیل کن</button>
                <button type="button" class="btn btn-clear" onclick="clearInput();">🗑 پاک کردن همه</button>
            </div>

            <div class="input-group">
                <label>🎮 نتیجه (کپی کن و تو بازی بچسبون) :</label>
                <input type="text" id="resultbox" readonly dir="ltr" placeholder="نتیجه در اینجا نمایش داده می‌شود">
            </div>

            <div class="button-group">
                <button type="button" class="btn btn-copy" onclick="copyResult();">📋 کپی نتیجه</button>
            </div>
            <div id="copyMessage" class="message">✅ متن با موفقیت کپی شد!</div>
            
            <!-- ===== بخش جدید: اصلاح فایل زبان (.lang) ===== -->
            <div class="lang-upload-section">
                <label>📂 اصلاح فایل زبان (.lang) :</label>
                <input type="file" id="langFileInput" accept=".lang,.txt" />
                <div class="button-group" style="margin:10px 0 0 0; gap:10px;">
                    <button type="button" class="btn" onclick="ProcessLangFile();" style="background:#6c5ce7;color:white;">🔄 اصلاح فایل</button>
                    <button type="button" class="btn btn-copy" onclick="copyLangResult();">📋 کپی نتیجه</button>
                </div>
                <textarea id="langResult" readonly placeholder="نتیجه اصلاح فایل زبان در اینجا نمایش داده می‌شود..."></textarea>
            </div>
            
            <div class="credit">طراحی شده برای بازی‌های فارسی | خروجی دقیقاً مطابق نیاز بازی است</div>
        </form>
    </div>

    <!-- المان‌های مخفی مورد نیاز اسکریپت اصلی -->
    <textarea name='outbox' id='outbox' style="display:none;"></textarea>
    <div id="message" style="display:none;"></div>
</body>
</html>