<!DOCTYPE html>
<html lang="fa" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
        <link rel="icon" href="fav.png" type="image/png" />
    <title>🎨 Minecraft Color Text Generator</title>
    <style>
        /* ----- Reset & Base ----- */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(145deg, #12161c 0%, #1a1f2a 100%);
            min-height: 100vh;
            padding: 1.5rem;
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            color: #e0e0e0;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .container {
            max-width: 1400px;
            width: 100%;
            background: rgba(30, 35, 48, 0.75);
            backdrop-filter: blur(12px);
            border-radius: 32px;
            padding: 1.8rem;
            box-shadow: 0 25px 45px rgba(0, 0, 0, 0.4), 0 0 0 1px rgba(255, 255, 200, 0.05);
        }

        /* ----- Header ----- */
        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 1rem;
            margin-bottom: 1.5rem;
            padding-bottom: 0.8rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.06);
        }

        .header h1 {
            font-size: 1.6rem;
            background: linear-gradient(135deg, #ffec99, #ffb347);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .header h1 span {
            -webkit-text-fill-color: initial;
        }

        .mode-switch {
            display: flex;
            gap: 6px;
            background: rgba(255, 255, 255, 0.06);
            padding: 4px;
            border-radius: 40px;
            border: 1px solid rgba(255, 255, 255, 0.08);
        }

        .mode-btn {
            padding: 6px 18px;
            border: none;
            border-radius: 30px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            background: transparent;
            color: #8a9bb0;
            font-size: 0.85rem;
        }

        .mode-btn.active {
            background: #ffb347;
            color: #1a1f2a;
            box-shadow: 0 4px 12px rgba(255, 179, 71, 0.3);
        }

        .mode-btn:hover:not(.active) {
            color: #fff;
        }

        /* ----- Main Grid ----- */
        .main-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
        }

        @media (max-width: 992px) {
            .main-grid {
                grid-template-columns: 1fr;
            }
        }

        /* ----- Left Panel ----- */
        .left-panel {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        /* ----- Toolbar ----- */
        .toolbar {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
            background: rgba(15, 18, 25, 0.5);
            border-radius: 20px;
            padding: 10px 14px;
            border: 1px solid rgba(255, 255, 255, 0.06);
            align-items: center;
        }

        .toolbar-group {
            display: flex;
            align-items: center;
            gap: 4px;
            padding: 0 6px;
            border-left: 1px solid rgba(255, 255, 255, 0.06);
        }

        .toolbar-group:last-child {
            border-left: none;
        }

        .toolbar-btn {
            background: transparent;
            border: none;
            color: #b0c0d0;
            padding: 5px 10px;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.2s;
            font-size: 0.8rem;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .toolbar-btn:hover {
            background: rgba(255, 255, 255, 0.06);
            color: #fff;
        }

        .toolbar-btn.active {
            background: rgba(255, 179, 71, 0.15);
            color: #ffb347;
        }

        .toolbar-btn .badge {
            background: #ffb347;
            color: #1a1f2a;
            border-radius: 30px;
            padding: 0 8px;
            font-size: 0.65rem;
            font-weight: 700;
        }

        /* ----- Color Dropdown ----- */
        .color-dropdown {
            position: relative;
            display: inline-block;
        }

        .color-dropdown-content {
            display: none;
            position: absolute;
            top: 100%;
            left: 0;
            margin-top: 6px;
            background: #1a1f2a;
            border-radius: 16px;
            padding: 10px;
            min-width: 220px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.6);
            border: 1px solid rgba(255, 255, 255, 0.06);
            z-index: 100;
            max-height: 320px;
            overflow-y: auto;
        }

        .color-dropdown-content.show {
            display: block;
            animation: fadeDown 0.25s ease;
        }

        @keyframes fadeDown {
            from {
                opacity: 0;
                transform: translateY(-8px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .color-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 5px 10px;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.15s;
        }

        .color-item:hover {
            background: rgba(255, 255, 255, 0.06);
        }

        .color-item .swatch {
            width: 20px;
            height: 20px;
            border-radius: 6px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            flex-shrink: 0;
        }

        .color-item .name {
            font-size: 0.8rem;
            color: #d0d8e0;
            flex: 1;
        }

        .color-item .code {
            font-size: 0.65rem;
            color: #6a7a8a;
            font-family: monospace;
        }

        /* ----- Format Buttons ----- */
        .format-btn {
            background: transparent;
            border: none;
            color: #b0c0d0;
            padding: 4px 8px;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .format-btn:hover {
            background: rgba(255, 255, 255, 0.06);
            color: #fff;
        }

        .format-btn.active {
            background: rgba(255, 179, 71, 0.15);
            color: #ffb347;
        }

        /* ----- Editor ----- */
        .editor-wrapper {
            position: relative;
            flex: 1;
        }

        #editor {
            width: 100%;
            min-height: 320px;
            background: #0f1219;
            border: 2px solid #2d3748;
            border-radius: 20px;
            padding: 16px 20px;
            color: #f0f3fa;
            font-size: 1.1rem;
            font-family: 'Segoe UI', system-ui, sans-serif;
            resize: vertical;
            outline: none;
            transition: border-color 0.3s;
            line-height: 1.8;
            direction: rtl;
            text-align: right;
        }

        #editor:focus {
            border-color: #ffb347;
            box-shadow: 0 0 0 3px rgba(255, 179, 71, 0.1);
        }

        #editor::selection {
            background: rgba(255, 179, 71, 0.3);
        }

        /* ----- Stats ----- */
        .stats {
            display: flex;
            gap: 1.5rem;
            padding: 8px 4px;
            font-size: 0.8rem;
            color: #6a7a8a;
            justify-content: flex-start;
        }

        .stats span {
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .stats .num {
            color: #d0d8e0;
            font-weight: 600;
        }

        /* ----- Extra Tools ----- */
        .extra-tools {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 6px;
        }

        .extra-tools button {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.06);
            color: #b0c0d0;
            padding: 4px 14px;
            border-radius: 30px;
            cursor: pointer;
            font-size: 0.75rem;
            transition: all 0.2s;
        }

        .extra-tools button:hover {
            background: rgba(255, 255, 255, 0.1);
            color: #fff;
        }

        .gradient-picker {
            display: none;
            align-items: center;
            gap: 10px;
            padding: 6px 12px;
            background: rgba(15, 18, 25, 0.5);
            border-radius: 30px;
            border: 1px solid rgba(255, 255, 255, 0.06);
            flex-wrap: wrap;
        }

        .gradient-picker.show {
            display: flex;
        }

        .gradient-picker select {
            background: #0f1219;
            color: #f0f3fa;
            border: 1px solid #2d3748;
            border-radius: 8px;
            padding: 4px 8px;
            font-size: 0.75rem;
            cursor: pointer;
            outline: none;
        }

        .gradient-picker select:focus {
            border-color: #ffb347;
        }

        /* ----- Word Color Popup ----- */
        .word-color-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.7);
            backdrop-filter: blur(8px);
            z-index: 9999;
            justify-content: center;
            align-items: center;
            animation: fadeIn 0.3s ease;
        }

        .word-color-overlay.show {
            display: flex;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        .word-color-modal {
            background: #1a1f2a;
            border-radius: 24px;
            padding: 2rem;
            max-width: 600px;
            width: 95%;
            max-height: 80vh;
            overflow-y: auto;
            border: 1px solid rgba(255, 255, 255, 0.06);
            box-shadow: 0 30px 60px rgba(0, 0, 0, 0.6);
        }

        .word-color-modal h2 {
            color: #ffec99;
            margin-bottom: 1.5rem;
            text-align: center;
            font-size: 1.3rem;
        }

        .word-color-modal .close-btn {
            float: left;
            background: none;
            border: none;
            color: #8a9bb0;
            font-size: 1.5rem;
            cursor: pointer;
            transition: color 0.2s;
        }

        .word-color-modal .close-btn:hover {
            color: #fff;
        }

        .word-color-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 8px 12px;
            margin-bottom: 6px;
            background: rgba(15, 18, 25, 0.3);
            border-radius: 12px;
            border: 1px solid rgba(255, 255, 255, 0.04);
            transition: background 0.2s;
        }

        .word-color-item:hover {
            background: rgba(255, 255, 255, 0.04);
        }

        .word-color-item .word-index {
            color: #6a7a8a;
            font-size: 0.7rem;
            min-width: 24px;
            text-align: center;
        }

        .word-color-item .word-text {
            color: #f0f3fa;
            font-size: 1rem;
            flex: 1;
            direction: ltr;
            text-align: left;
        }

        .word-color-item select {
            background: #0f1219;
            color: #f0f3fa;
            border: 1px solid #2d3748;
            border-radius: 8px;
            padding: 4px 8px;
            font-size: 0.75rem;
            cursor: pointer;
            outline: none;
            min-width: 100px;
        }

        .word-color-item select:focus {
            border-color: #ffb347;
        }

        .word-color-item .preview-swatch {
            width: 20px;
            height: 20px;
            border-radius: 4px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            flex-shrink: 0;
        }

        .word-color-modal .apply-btn {
            display: block;
            width: 100%;
            margin-top: 1.5rem;
            padding: 12px;
            background: #ffb347;
            border: none;
            border-radius: 30px;
            color: #1a1f2a;
            font-weight: 700;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.2s;
        }

        .word-color-modal .apply-btn:hover {
            background: #ffa01a;
            transform: scale(0.98);
        }

        .word-color-modal .reset-all-btn {
            display: block;
            width: 100%;
            margin-top: 0.8rem;
            padding: 10px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.06);
            border-radius: 30px;
            color: #8a9bb0;
            font-weight: 600;
            font-size: 0.85rem;
            cursor: pointer;
            transition: all 0.2s;
        }

        .word-color-modal .reset-all-btn:hover {
            background: rgba(255, 255, 255, 0.1);
            color: #fff;
        }

        /* ----- Right Panel ----- */
        .right-panel {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        /* ----- Output ----- */
        .output-section {
            background: rgba(15, 18, 25, 0.5);
            border-radius: 20px;
            padding: 1rem;
            border: 1px solid rgba(255, 255, 255, 0.06);
        }

        .output-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
            flex-wrap: wrap;
            gap: 8px;
        }

        .output-header label {
            font-weight: 600;
            color: #b0c0d0;
            font-size: 0.9rem;
        }

        .output-actions {
            display: flex;
            gap: 6px;
        }

        .output-actions button {
            background: rgba(255, 255, 255, 0.06);
            border: none;
            color: #b0c0d0;
            padding: 4px 14px;
            border-radius: 30px;
            cursor: pointer;
            font-size: 0.75rem;
            transition: all 0.2s;
        }

        .output-actions button:hover {
            background: rgba(255, 255, 255, 0.12);
            color: #fff;
        }

        #output {
            background: #0f1219;
            border-radius: 16px;
            padding: 14px 18px;
            min-height: 80px;
            font-family: 'Courier New', monospace;
            font-size: 0.9rem;
            color: #f0f3fa;
            word-break: break-all;
            direction: ltr;
            text-align: left;
            border: 1px solid #2d3748;
            max-height: 150px;
            overflow-y: auto;
            white-space: pre-wrap;
        }

        /* ----- Preview ----- */
        .preview-section {
            background: rgba(15, 18, 25, 0.5);
            border-radius: 20px;
            padding: 1rem;
            border: 1px solid rgba(255, 255, 255, 0.06);
            flex: 1;
        }

        .preview-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        .preview-header label {
            font-weight: 600;
            color: #b0c0d0;
            font-size: 0.9rem;
        }

        #preview {
            background: #0f1219;
            border-radius: 16px;
            padding: 16px 20px;
            min-height: 100px;
            font-size: 1.3rem;
            line-height: 2;
            border: 1px solid #2d3748;
            direction: rtl;
            text-align: right;
            font-family: 'Segoe UI', system-ui, sans-serif;
            white-space: pre-wrap;
        }

        /* ----- Toast ----- */
        .toast {
            position: fixed;
            bottom: 30px;
            left: 50%;
            transform: translateX(-50%);
            background: #1e293b;
            color: #fff;
            padding: 10px 28px;
            border-radius: 50px;
            font-size: 0.9rem;
            z-index: 999;
            animation: toastAnim 2.2s ease forwards;
            border: 1px solid rgba(255, 255, 200, 0.1);
            backdrop-filter: blur(8px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.4);
            font-weight: 500;
        }

        @keyframes toastAnim {
            0% {
                opacity: 0;
                transform: translateX(-50%) translateY(20px);
            }

            15% {
                opacity: 1;
                transform: translateX(-50%) translateY(0);
            }

            85% {
                opacity: 1;
                transform: translateX(-50%) translateY(0);
            }

            100% {
                opacity: 0;
                transform: translateX(-50%) translateY(20px);
            }
        }

        /* ----- Responsive ----- */
        @media (max-width: 600px) {
            .container {
                padding: 1rem;
            }

            .header h1 {
                font-size: 1.2rem;
            }

            .toolbar {
                padding: 8px 10px;
                gap: 4px;
            }

            .toolbar-btn {
                font-size: 0.7rem;
                padding: 4px 8px;
            }

            .color-dropdown-content {
                min-width: 180px;
                left: -10px;
            }

            #editor {
                min-height: 200px;
                font-size: 1rem;
            }

            .main-grid {
                gap: 1rem;
            }

            .stats {
                font-size: 0.7rem;
                gap: 0.8rem;
                flex-wrap: wrap;
            }

            .gradient-picker {
                flex-wrap: wrap;
            }

            .word-color-modal {
                padding: 1.5rem;
                max-width: 95%;
            }

            .word-color-item {
                flex-wrap: wrap;
                gap: 6px;
            }

            .word-color-item select {
                min-width: 80px;
                flex: 1;
            }
        }

        /* ----- Scrollbar ----- */
        ::-webkit-scrollbar {
            width: 6px;
        }

        ::-webkit-scrollbar-track {
            background: #0f1219;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb {
            background: #2d3748;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #ffb347;
        }

        /* Obfuscate animation */
        @keyframes obfuscate {
            0% {
                opacity: 0.4;
                transform: translateX(0);
            }

            100% {
                opacity: 0.9;
                transform: translateX(2px);
            }
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

        <!-- HEADER -->
        <div class="header">
            <h1><span>🎨</span> Minecraft Color Text</h1>
            <div class="mode-switch">
                <button class="mode-btn active" data-mode="section" onclick="setMode('section')">§ Mode</button>
                <button class="mode-btn" data-mode="ampersand" onclick="setMode('ampersand')">&amp; Mode</button>
            </div>
        </div>

        <!-- MAIN GRID -->
        <div class="main-grid">

            <!-- LEFT PANEL -->
            <div class="left-panel">

                <!-- TOOLBAR -->
                <div class="toolbar" id="toolbar">

                    <!-- Color Dropdown -->
                    <div class="toolbar-group">
                        <div class="color-dropdown">
                            <button class="toolbar-btn" onclick="toggleColorDropdown()">
                                🎨 Color <span class="badge">16</span>
                            </button>
                            <div class="color-dropdown-content" id="colorDropdown"></div>
                        </div>
                    </div>

                    <!-- Formatting -->
                    <div class="toolbar-group">
                        <button class="format-btn" data-format="bold" onclick="applyFormat('bold')"><b>B</b></button>
                        <button class="format-btn" data-format="italic" onclick="applyFormat('italic')"><i>I</i></button>
                        <button class="format-btn" data-format="underline" onclick="applyFormat('underline')"><u>U</u></button>
                        <button class="format-btn" data-format="strikethrough" onclick="applyFormat('strikethrough')"><s>S</s></button>
                        <button class="format-btn" data-format="obfuscated" onclick="applyFormat('obfuscated')">??</button>
                        <button class="format-btn" data-format="reset" onclick="applyFormat('reset')">↺</button>
                    </div>

                    <!-- Extra Tools -->
                    <div class="toolbar-group">
                        <button class="toolbar-btn" onclick="applyRainbow()">🌈 Rainbow</button>
                        <button class="toolbar-btn" onclick="applyRandom()">🎲 Random</button>
                        <button class="toolbar-btn" onclick="openWordColorMenu()">🔤 هر کلمه</button>
                        <button class="toolbar-btn" onclick="toggleGradient()">🌀 Gradient</button>
                        <button class="toolbar-btn" onclick="clearFormatting()">✕ Clear</button>
                    </div>

                    <!-- Undo / Redo -->
                    <div class="toolbar-group">
                        <button class="toolbar-btn" onclick="undo()">↩</button>
                        <button class="toolbar-btn" onclick="redo()">↪</button>
                    </div>

                    <!-- Gradient Picker -->
                    <div class="gradient-picker" id="gradientPicker">
                        <span style="font-size:0.7rem;color:#8a9bb0;">از:</span>
                        <select id="gradColor1"></select>
                        <span style="font-size:0.7rem;color:#8a9bb0;">تا:</span>
                        <select id="gradColor2"></select>
                        <button class="toolbar-btn" onclick="applyGradient()" style="background:#ffb347;color:#1a1f2a;padding:2px 14px;border-radius:30px;">✓</button>
                    </div>

                </div>

                <!-- EDITOR -->
                <div class="editor-wrapper">
                    <textarea id="editor" placeholder="متن خود را اینجا تایپ کنید..." spellcheck="false"></textarea>
                </div>

                <!-- STATS -->
                <div class="stats">
                    <span>📝 <span class="num" id="charCount">0</span> کاراکتر</span>
                    <span>📄 <span class="num" id="wordCount">0</span> کلمه</span>
                    <span>📏 <span class="num" id="lineCount">0</span> خط</span>
                </div>

                <!-- EXTRA DOWNLOAD -->
                <div class="extra-tools">
                    <button onclick="downloadTxt()">📥 دانلود .txt</button>
                    <button onclick="downloadJson()">📥 دانلود .json</button>
                </div>

            </div>

            <!-- RIGHT PANEL -->
            <div class="right-panel">

                <!-- OUTPUT -->
                <div class="output-section">
                    <div class="output-header">
                        <label>📋 خروجی Minecraft</label>
                        <div class="output-actions">
                            <button onclick="copyOutput()">📋 کپی</button>
                            <button onclick="copyJson()">📋 کپی JSON</button>
                        </div>
                    </div>
                    <div id="output">خروجی در اینجا نمایش داده می‌شود...</div>
                </div>

                <!-- PREVIEW -->
                <div class="preview-section">
                    <div class="preview-header">
                        <label>👁️ پیش‌نمایش زنده</label>
                    </div>
                    <div id="preview">پیش‌نمایش رنگ‌ها و افکت‌ها...</div>
                </div>

            </div>

        </div>
    </div>

    <!-- ===== WORD COLOR POPUP ===== -->
    <div class="word-color-overlay" id="wordColorOverlay">
        <div class="word-color-modal">
            <button class="close-btn" onclick="closeWordColorMenu()">✕</button>
            <h2>🎨 انتخاب رنگ برای هر کلمه</h2>
            <div id="wordColorList"></div>
            <button class="apply-btn" onclick="applyWordColors()">✅ اعمال تغییرات</button>
            <button class="reset-all-btn" onclick="resetAllWordColors()">↺ بازنشانی همه به حالت پیش‌فرض</button>
        </div>
    </div>

    <script>
        // ============================================================
        //  DATA: Minecraft Colors & Formats
        // ============================================================

        const COLORS = [{
                code: '0',
                name: 'Black',
                hex: '#000000'
            },
            {
                code: '1',
                name: 'Dark Blue',
                hex: '#0000AA'
            },
            {
                code: '2',
                name: 'Dark Green',
                hex: '#00AA00'
            },
            {
                code: '3',
                name: 'Dark Aqua',
                hex: '#00AAAA'
            },
            {
                code: '4',
                name: 'Dark Red',
                hex: '#AA0000'
            },
            {
                code: '5',
                name: 'Dark Purple',
                hex: '#AA00AA'
            },
            {
                code: '6',
                name: 'Gold',
                hex: '#FFAA00'
            },
            {
                code: '7',
                name: 'Gray',
                hex: '#AAAAAA'
            },
            {
                code: '8',
                name: 'Dark Gray',
                hex: '#555555'
            },
            {
                code: '9',
                name: 'Blue',
                hex: '#5555FF'
            },
            {
                code: 'a',
                name: 'Green',
                hex: '#55FF55'
            },
            {
                code: 'b',
                name: 'Aqua',
                hex: '#55FFFF'
            },
            {
                code: 'c',
                name: 'Red',
                hex: '#FF5555'
            },
            {
                code: 'd',
                name: 'Light Purple',
                hex: '#FF55FF'
            },
            {
                code: 'e',
                name: 'Yellow',
                hex: '#FFFF55'
            },
            {
                code: 'f',
                name: 'White',
                hex: '#FFFFFF'
            }
        ];

        const FORMATS = {
            bold: {
                code: 'l',
                style: 'font-weight:bold;'
            },
            italic: {
                code: 'o',
                style: 'font-style:italic;'
            },
            underline: {
                code: 'n',
                style: 'text-decoration:underline;'
            },
            strikethrough: {
                code: 'm',
                style: 'text-decoration:line-through;'
            },
            obfuscated: {
                code: 'k',
                style: ''
            },
            reset: {
                code: 'r',
                style: ''
            }
        };

        // ============================================================
        //  STATE
        // ============================================================

        let mode = 'section';
        let history = [];
        let historyIndex = -1;
        let colorDropdownOpen = false;
        let gradientActive = false;
        let wordColorData = []; // [{word, colorCode}]

        const editor = document.getElementById('editor');
        const output = document.getElementById('output');
        const preview = document.getElementById('preview');

        // ============================================================
        //  INIT
        // ============================================================

        document.addEventListener('DOMContentLoaded', () => {
            buildColorDropdown();
            populateGradientSelects();
            updateStats();
            saveHistory();
            editor.addEventListener('input', () => {
                updateStats();
                updateOutputAndPreview();
                clearTimeout(window._saveTimer);
                window._saveTimer = setTimeout(saveHistory, 400);
            });
            editor.addEventListener('keydown', (e) => {
                if (e.ctrlKey && e.key === 'z') {
                    e.preventDefault();
                    undo();
                }
                if (e.ctrlKey && e.key === 'y') {
                    e.preventDefault();
                    redo();
                }
            });
            // Prevent manual code entry
            editor.addEventListener('paste', (e) => {
                setTimeout(() => {
                    const raw = editor.value;
                    const clean = raw.replace(/[§&][0-9a-fk-or]/gi, '');
                    if (clean !== raw) {
                        editor.value = clean;
                        updateStats();
                        updateOutputAndPreview();
                        saveHistory();
                        showToast('🧹 کدهای دستی حذف شدند (فقط از Toolbar استفاده کنید)');
                    }
                }, 10);
            });
            updateOutputAndPreview();
        });

        // ============================================================
        //  BUILD COLOR DROPDOWN
        // ============================================================

        function buildColorDropdown() {
            const container = document.getElementById('colorDropdown');
            container.innerHTML = '';
            COLORS.forEach(c => {
                const div = document.createElement('div');
                div.className = 'color-item';
                div.innerHTML = `
                    <span class="swatch" style="background:${c.hex};"></span>
                    <span class="name">${c.name}</span>
                    <span class="code">${c.code}</span>
                `;
                div.addEventListener('click', () => applyColor(c.code));
                container.appendChild(div);
            });
        }

        function populateGradientSelects() {
            const sel1 = document.getElementById('gradColor1');
            const sel2 = document.getElementById('gradColor2');
            sel1.innerHTML = '';
            sel2.innerHTML = '';
            COLORS.forEach(c => {
                const opt1 = document.createElement('option');
                opt1.value = c.code;
                opt1.textContent = c.name;
                opt1.style.backgroundColor = c.hex;
                opt1.style.color = '#fff';
                sel1.appendChild(opt1);
                const opt2 = document.createElement('option');
                opt2.value = c.code;
                opt2.textContent = c.name;
                opt2.style.backgroundColor = c.hex;
                opt2.style.color = '#fff';
                sel2.appendChild(opt2);
            });
            sel1.value = 'c';
            sel2.value = 'e';
        }

        function toggleColorDropdown() {
            const el = document.getElementById('colorDropdown');
            colorDropdownOpen = !colorDropdownOpen;
            el.classList.toggle('show', colorDropdownOpen);
            if (colorDropdownOpen) {
                document.addEventListener('click', closeColorDropdownOutside);
            } else {
                document.removeEventListener('click', closeColorDropdownOutside);
            }
        }

        function closeColorDropdownOutside(e) {
            if (!e.target.closest('.color-dropdown')) {
                document.getElementById('colorDropdown').classList.remove('show');
                colorDropdownOpen = false;
                document.removeEventListener('click', closeColorDropdownOutside);
            }
        }

        // ============================================================
        //  MODE
        // ============================================================

        function setMode(m) {
            mode = m;
            document.querySelectorAll('.mode-btn').forEach(b => {
                b.classList.toggle('active', b.dataset.mode === m);
            });
            updateOutputAndPreview();
            showToast(`حالت ${m === 'section' ? '§' : '&'} فعال شد`);
        }

        function getPrefix() {
            return mode === 'section' ? '§' : '&';
        }

        // ============================================================
        //  GET / SET ALL TEXT
        // ============================================================

        function getAllText() {
            return editor.value;
        }

        function replaceAllText(newText) {
            editor.value = newText;
            editor.focus();
            editor.selectionStart = editor.selectionEnd = newText.length;
            updateStats();
            updateOutputAndPreview();
            saveHistory();
        }

        // ============================================================
        //  CLEAN TEXT (remove all existing codes)
        // ============================================================

        function cleanText(text) {
            return text.replace(/[§&][0-9a-fk-or]/gi, '');
        }

        // ============================================================
        //  APPLY COLOR (روی کل متن)
        // ============================================================

        function applyColor(code) {
            const text = getAllText();
            if (!text) {
                showToast('⚠️ متنی برای رنگ‌آمیزی وجود ندارد');
                return;
            }
            const prefix = getPrefix();
            const clean = cleanText(text);
            const wrapped = prefix + code + clean;
            replaceAllText(wrapped);
            document.getElementById('colorDropdown').classList.remove('show');
            colorDropdownOpen = false;
            const colorName = COLORS.find(c => c.code === code)?.name || code;
            showToast(`✅ رنگ ${colorName} روی کل متن اعمال شد`);
        }

        // ============================================================
        //  APPLY FORMAT (روی کل متن)
        // ============================================================

        function applyFormat(type) {
            const text = getAllText();
            if (!text) {
                showToast('⚠️ متنی برای اعمال فرمت وجود ندارد');
                return;
            }
            const prefix = getPrefix();
            const code = FORMATS[type]?.code;
            if (!code) return;
            const clean = cleanText(text);
            const wrapped = prefix + code + clean;
            replaceAllText(wrapped);
            showToast(`✅ ${type} روی کل متن اعمال شد`);
        }

        // ============================================================
        //  RAINBOW
        // ============================================================

        function applyRainbow() {
            const text = getAllText();
            if (!text) {
                showToast('⚠️ متنی برای Rainbow وجود ندارد');
                return;
            }
            const prefix = getPrefix();
            const colors = ['c', '6', 'e', 'a', 'b', '9', 'd'];
            const clean = cleanText(text);
            let result = '';
            for (let i = 0; i < clean.length; i++) {
                const color = colors[i % colors.length];
                result += prefix + color + clean[i];
            }
            replaceAllText(result);
            showToast('🌈 Rainbow روی کل متن اعمال شد');
        }

        // ============================================================
        //  RANDOM
        // ============================================================

        function applyRandom() {
            const text = getAllText();
            if (!text) {
                showToast('⚠️ متنی برای Random وجود ندارد');
                return;
            }
            const prefix = getPrefix();
            const codes = COLORS.map(c => c.code);
            const clean = cleanText(text);
            let result = '';
            for (let i = 0; i < clean.length; i++) {
                const code = codes[Math.floor(Math.random() * codes.length)];
                result += prefix + code + clean[i];
            }
            replaceAllText(result);
            showToast('🎲 Random روی کل متن اعمال شد');
        }

        // ============================================================
        //  WORD COLOR MENU
        // ============================================================

        function openWordColorMenu() {
            const text = getAllText();
            if (!text.trim()) {
                showToast('⚠️ ابتدا متنی وارد کنید');
                return;
            }
            const clean = cleanText(text);
            const words = clean.split(/\s+/).filter(w => w.length > 0);

            if (words.length === 0) {
                showToast('⚠️ متنی برای پردازش وجود ندارد');
                return;
            }

            // ساخت داده برای هر کلمه
            wordColorData = words.map((word, index) => {
                // بررسی اینکه آیا کلمه قبلاً رنگی دارد؟
                // سعی می‌کنیم رنگ فعلی را پیدا کنیم
                const prefix = getPrefix();
                const regex = new RegExp(`${prefix}([0-9a-f])${word}(?=\\s|$)`);
                const match = text.match(regex);
                let currentColor = '';
                if (match) {
                    currentColor = match[1];
                }
                return {
                    word: word,
                    colorCode: currentColor || ''
                };
            });

            renderWordColorMenu();
            document.getElementById('wordColorOverlay').classList.add('show');
        }

        function renderWordColorMenu() {
            const container = document.getElementById('wordColorList');
            container.innerHTML = '';

            wordColorData.forEach((item, index) => {
                const div = document.createElement('div');
                div.className = 'word-color-item';

                const idxSpan = document.createElement('span');
                idxSpan.className = 'word-index';
                idxSpan.textContent = `#${index + 1}`;

                const wordSpan = document.createElement('span');
                wordSpan.className = 'word-text';
                wordSpan.textContent = item.word;

                const select = document.createElement('select');
                // گزینه پیش‌فرض: بدون رنگ (داده نشده)
                const defaultOpt = document.createElement('option');
                defaultOpt.value = '';
                defaultOpt.textContent = '🔹 بدون رنگ';
                select.appendChild(defaultOpt);

                COLORS.forEach(c => {
                    const opt = document.createElement('option');
                    opt.value = c.code;
                    opt.textContent = c.name;
                    opt.style.backgroundColor = c.hex;
                    opt.style.color = '#fff';
                    if (item.colorCode === c.code) {
                        opt.selected = true;
                    }
                    select.appendChild(opt);
                });

                select.addEventListener('change', (e) => {
                    wordColorData[index].colorCode = e.target.value;
                    updateWordPreview(index);
                });

                const swatch = document.createElement('span');
                swatch.className = 'preview-swatch';
                swatch.id = `swatch-${index}`;
                const color = COLORS.find(c => c.code === item.colorCode);
                swatch.style.background = color ? color.hex : '#2d3748';

                div.appendChild(idxSpan);
                div.appendChild(wordSpan);
                div.appendChild(select);
                div.appendChild(swatch);
                container.appendChild(div);
            });
        }

        function updateWordPreview(index) {
            const item = wordColorData[index];
            const swatch = document.getElementById(`swatch-${index}`);
            if (swatch) {
                const color = COLORS.find(c => c.code === item.colorCode);
                swatch.style.background = color ? color.hex : '#2d3748';
            }
        }

        function applyWordColors() {
            const text = getAllText();
            const clean = cleanText(text);
            const words = clean.split(/\s+/).filter(w => w.length > 0);

            if (words.length !== wordColorData.length) {
                showToast('⚠️ تعداد کلمات تغییر کرده، دوباره منو را باز کنید');
                closeWordColorMenu();
                return;
            }

            const prefix = getPrefix();
            let result = '';
            for (let i = 0; i < words.length; i++) {
                const data = wordColorData[i];
                if (data.colorCode) {
                    result += prefix + data.colorCode + words[i];
                } else {
                    result += words[i];
                }
                if (i < words.length - 1) result += ' ';
            }

            replaceAllText(result);
            closeWordColorMenu();
            showToast('✅ رنگ‌های هر کلمه اعمال شد');
        }

        function resetAllWordColors() {
            wordColorData.forEach(item => {
                item.colorCode = '';
            });
            renderWordColorMenu();
            showToast('↺ همه رنگ‌ها به حالت پیش‌فرض بازنشانی شدند');
        }

        function closeWordColorMenu() {
            document.getElementById('wordColorOverlay').classList.remove('show');
        }

        // کلیک خارج از مودال برای بستن
        document.getElementById('wordColorOverlay').addEventListener('click', (e) => {
            if (e.target === e.currentTarget) {
                closeWordColorMenu();
            }
        });

        // ============================================================
        //  GRADIENT
        // ============================================================

        function toggleGradient() {
            gradientActive = !gradientActive;
            document.getElementById('gradientPicker').classList.toggle('show', gradientActive);
            if (gradientActive) {
                showToast('🌀 دو رنگ را انتخاب کنید و روی ✓ کلیک کنید');
            }
        }

        function applyGradient() {
            const text = getAllText();
            if (!text) {
                showToast('⚠️ متنی برای Gradient وجود ندارد');
                return;
            }
            const code1 = document.getElementById('gradColor1').value;
            const code2 = document.getElementById('gradColor2').value;
            const prefix = getPrefix();

            const c1 = COLORS.find(c => c.code === code1);
            const c2 = COLORS.find(c => c.code === code2);
            if (!c1 || !c2) {
                showToast('⚠️ رنگ انتخاب شده معتبر نیست');
                return;
            }

            function hexToRgb(hex) {
                const r = parseInt(hex.slice(1, 3), 16);
                const g = parseInt(hex.slice(3, 5), 16);
                const b = parseInt(hex.slice(5, 7), 16);
                return {
                    r,
                    g,
                    b
                };
            }
            const rgb1 = hexToRgb(c1.hex);
            const rgb2 = hexToRgb(c2.hex);

            function rgbToMcCode(r, g, b) {
                let best = 'f';
                let bestDist = Infinity;
                COLORS.forEach(c => {
                    const cr = parseInt(c.hex.slice(1, 3), 16);
                    const cg = parseInt(c.hex.slice(3, 5), 16);
                    const cb = parseInt(c.hex.slice(5, 7), 16);
                    const dist = (r - cr) ** 2 + (g - cg) ** 2 + (b - cb) ** 2;
                    if (dist < bestDist) {
                        bestDist = dist;
                        best = c.code;
                    }
                });
                return best;
            }

            const clean = cleanText(text);
            let result = '';
            const len = clean.length;
            for (let i = 0; i < len; i++) {
                const t = len > 1 ? i / (len - 1) : 0;
                const r = Math.round(rgb1.r + t * (rgb2.r - rgb1.r));
                const g = Math.round(rgb1.g + t * (rgb2.g - rgb1.g));
                const b = Math.round(rgb1.b + t * (rgb2.b - rgb1.b));
                const mcCode = rgbToMcCode(r, g, b);
                result += prefix + mcCode + clean[i];
            }
            replaceAllText(result);
            gradientActive = false;
            document.getElementById('gradientPicker').classList.remove('show');
            showToast('🌀 Gradient اعمال شد');
        }

        // ============================================================
        //  CLEAR FORMATTING
        // ============================================================

        function clearFormatting() {
            const text = getAllText();
            if (!text) {
                showToast('⚠️ متنی برای پاک کردن وجود ندارد');
                return;
            }
            const clean = cleanText(text);
            replaceAllText(clean);
            showToast('✕ تمام فرمت‌ها پاک شدند');
        }

        // ============================================================
        //  UNDO / REDO
        // ============================================================

        function saveHistory() {
            const current = editor.value;
            if (history[historyIndex] !== current) {
                history = history.slice(0, historyIndex + 1);
                history.push(current);
                historyIndex++;
                if (history.length > 100) {
                    history.shift();
                    historyIndex--;
                }
            }
        }

        function undo() {
            if (historyIndex > 0) {
                historyIndex--;
                editor.value = history[historyIndex];
                updateStats();
                updateOutputAndPreview();
                showToast('↩ Undo');
            } else {
                showToast('⚠️ چیزی برای Undo وجود ندارد');
            }
        }

        function redo() {
            if (historyIndex < history.length - 1) {
                historyIndex++;
                editor.value = history[historyIndex];
                updateStats();
                updateOutputAndPreview();
                showToast('↪ Redo');
            } else {
                showToast('⚠️ چیزی برای Redo وجود ندارد');
            }
        }

        // ============================================================
        //  UPDATE OUTPUT & PREVIEW
        // ============================================================

        function updateOutputAndPreview() {
            const raw = editor.value;
            output.textContent = raw || 'خروجی در اینجا نمایش داده می‌شود...';

            if (!raw) {
                preview.innerHTML = 'پیش‌نمایش رنگ‌ها و افکت‌ها...';
                return;
            }

            let html = raw;
            let resultHtml = '';
            let i = 0;
            let openTags = [];
            const colorMap = {};
            COLORS.forEach(c => {
                colorMap[c.code] = c.hex;
            });

            while (i < html.length) {
                const ch = html[i];
                if ((ch === '§' || ch === '&') && i + 1 < html.length) {
                    const code = html[i + 1].toLowerCase();
                    if ('0123456789abcdefklmnor'.includes(code)) {
                        if (code === 'r') {
                            while (openTags.length) {
                                const tag = openTags.pop();
                                resultHtml += `</${tag}>`;
                            }
                        } else if (colorMap[code]) {
                            const color = colorMap[code];
                            resultHtml += `<span style="color:${color};">`;
                            openTags.push('span');
                        } else if (code === 'l') {
                            resultHtml += `<b>`;
                            openTags.push('b');
                        } else if (code === 'o') {
                            resultHtml += `<i>`;
                            openTags.push('i');
                        } else if (code === 'n') {
                            resultHtml += `<u>`;
                            openTags.push('u');
                        } else if (code === 'm') {
                            resultHtml += `<s>`;
                            openTags.push('s');
                        } else if (code === 'k') {
                            resultHtml +=
                                `<span style="font-family:monospace;letter-spacing:2px;opacity:0.8;display:inline-block;animation:obfuscate 0.1s infinite alternate;">`;
                            openTags.push('span');
                        }
                        i += 2;
                        continue;
                    }
                }
                resultHtml += ch;
                i++;
            }
            while (openTags.length) {
                const tag = openTags.pop();
                resultHtml += `</${tag}>`;
            }

            preview.innerHTML = resultHtml || 'پیش‌نمایش...';
        }

        // ============================================================
        //  STATS
        // ============================================================

        function updateStats() {
            const text = editor.value;
            document.getElementById('charCount').textContent = text.length;
            const words = text.trim() ? text.trim().split(/\s+/).length : 0;
            document.getElementById('wordCount').textContent = words;
            const lines = text ? text.split('\n').length : 0;
            document.getElementById('lineCount').textContent = lines;
        }

        // ============================================================
        //  COPY & DOWNLOAD
        // ============================================================

        function copyOutput() {
            const text = output.textContent;
            if (!text || text === 'خروجی در اینجا نمایش داده می‌شود...') {
                showToast('⚠️ چیزی برای کپی وجود ندارد');
                return;
            }
            navigator.clipboard.writeText(text).then(() => {
                showToast('✅ کپی شد!');
            }).catch(() => {
                fallbackCopy(text);
            });
        }

        function copyJson() {
            const text = output.textContent;
            if (!text || text === 'خروجی در اینجا نمایش داده می‌شود...') {
                showToast('⚠️ چیزی برای کپی وجود ندارد');
                return;
            }
            const json = JSON.stringify({
                text: text,
                mode: mode
            });
            navigator.clipboard.writeText(json).then(() => {
                showToast('✅ JSON کپی شد!');
            }).catch(() => {
                fallbackCopy(json);
            });
        }

        function fallbackCopy(text) {
            const ta = document.createElement('textarea');
            ta.value = text;
            document.body.appendChild(ta);
            ta.select();
            document.execCommand('copy');
            document.body.removeChild(ta);
            showToast('✅ کپی شد!');
        }

        function downloadTxt() {
            const text = output.textContent;
            if (!text || text === 'خروجی در اینجا نمایش داده می‌شود...') {
                showToast('⚠️ چیزی برای دانلود وجود ندارد');
                return;
            }
            const blob = new Blob([text], {
                type: 'text/plain;charset=utf-8'
            });
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = 'minecraft_text.txt';
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            URL.revokeObjectURL(url);
            showToast('✅ دانلود شد');
        }

        function downloadJson() {
            const text = output.textContent;
            if (!text || text === 'خروجی در اینجا نمایش داده می‌شود...') {
                showToast('⚠️ چیزی برای دانلود وجود ندارد');
                return;
            }
            const json = JSON.stringify({
                text: text,
                mode: mode
            }, null, 2);
            const blob = new Blob([json], {
                type: 'application/json;charset=utf-8'
            });
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = 'minecraft_text.json';
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            URL.revokeObjectURL(url);
            showToast('✅ دانلود شد');
        }

        // ============================================================
        //  TOAST
        // ============================================================

        function showToast(msg) {
            const old = document.querySelector('.toast');
            if (old) old.remove();
            const div = document.createElement('div');
            div.className = 'toast';
            div.textContent = msg;
            document.body.appendChild(div);
            setTimeout(() => div.remove(), 2200);
        }
    </script>

</body>

</html>