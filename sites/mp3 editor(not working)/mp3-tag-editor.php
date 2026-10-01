<?php
// =====================================================
// فایل: sites/mp3-tag-editor/mp3-tag-editor.php
// ویرایشگر اطلاعات آهنگ - نسخه نهایی با getID3 اصلاح شده
// =====================================================

ini_set('memory_limit', '256M');
ini_set('max_execution_time', 300);
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);

session_start();

// =====================================================
// بارگذاری کتابخانه getID3
// =====================================================

require_once 'getid3/getid3.php';
require_once 'getid3/write.php';

// =====================================================
// کلاس مدیریت تگ‌های MP3 با getID3 - نسخه اصلاح شده
// =====================================================

class MP3TagEditor {
    
    private $filePath;
    private $tags;
    private $getID3;
    private $tagWriter;
    private $errors = array();
    private $warnings = array();
    
    public function __construct($filePath) {
        $this->filePath = $filePath;
        $this->tags = $this->getDefaultTags();
        $this->getID3 = new getID3();
        $this->tagWriter = new getid3_writetags();
        $this->tagWriter->filename = $filePath;
        $this->tagWriter->tagformats = array('id3v2.3'); // استفاده از نسخه 2.3 برای سازگاری بیشتر
        $this->tagWriter->overwrite_tags = true;
        $this->tagWriter->remove_other_tags = false;
        $this->tagWriter->tag_encoding = 'UTF-8';
    }
    
    private function getDefaultTags() {
        return [
            'title' => '', 'artist' => '', 'album' => '', 'year' => '',
            'genre' => '', 'comment' => '', 'track' => '', 'disc' => '',
            'composer' => '', 'lyrics' => '', 'cover_data' => null, 
            'cover_mime' => null, 'duration' => '', 'bitrate' => '', 
            'sample_rate' => ''
        ];
    }
    
    // =============================================
    // 1. خواندن تگ‌ها از فایل MP3 با getID3
    // =============================================
    public function readTags() {
        if (!file_exists($this->filePath)) {
            return $this->tags;
        }
        
        $fileInfo = $this->getID3->analyze($this->filePath);
        getid3_lib::CopyTagsToComments($fileInfo);
        
        $this->tags = $this->getDefaultTags();
        
        // اطلاعات فیزیکی فایل
        if (isset($fileInfo['audio']['bitrate'])) {
            $this->tags['bitrate'] = round($fileInfo['audio']['bitrate'] / 1000);
        }
        
        if (isset($fileInfo['audio']['sample_rate'])) {
            $this->tags['sample_rate'] = $fileInfo['audio']['sample_rate'];
        }
        
        if (isset($fileInfo['playtime_string'])) {
            $this->tags['duration'] = $fileInfo['playtime_string'];
        } elseif (isset($fileInfo['playtime_seconds'])) {
            $minutes = floor($fileInfo['playtime_seconds'] / 60);
            $seconds = floor($fileInfo['playtime_seconds'] % 60);
            $this->tags['duration'] = sprintf("%02d:%02d", $minutes, $seconds);
        }
        
        // خواندن تگ‌ها از بخش comments
        if (isset($fileInfo['comments'])) {
            $comments = $fileInfo['comments'];
            
            if (isset($comments['title'][0])) {
                $this->tags['title'] = $comments['title'][0];
            }
            if (isset($comments['artist'][0])) {
                $this->tags['artist'] = $comments['artist'][0];
            }
            if (isset($comments['album'][0])) {
                $this->tags['album'] = $comments['album'][0];
            }
            if (isset($comments['year'][0])) {
                $this->tags['year'] = $comments['year'][0];
            }
            if (isset($comments['genre'][0])) {
                $this->tags['genre'] = $comments['genre'][0];
            }
            if (isset($comments['comment'][0])) {
                $this->tags['comment'] = $comments['comment'][0];
            }
            if (isset($comments['track_number'][0])) {
                $this->tags['track'] = $comments['track_number'][0];
            }
            if (isset($comments['part_number'][0])) {
                $this->tags['disc'] = $comments['part_number'][0];
            }
            if (isset($comments['composer'][0])) {
                $this->tags['composer'] = $comments['composer'][0];
            }
            if (isset($comments['lyrics'][0])) {
                $this->tags['lyrics'] = $comments['lyrics'][0];
            }
        }
        
        // خواندن کاور - توجه: در خواندن از image_mime استفاده می‌شود (چون getID3 آن را به این نام ذخیره می‌کند)
        if (isset($fileInfo['id3v2']['APIC'])) {
            $pictures = $fileInfo['id3v2']['APIC'];
            if (is_array($pictures) && count($pictures) > 0) {
                $picture = $pictures[0];
                if (isset($picture['data']) && isset($picture['image_mime'])) {
                    $this->tags['cover_data'] = $picture['data'];
                    $this->tags['cover_mime'] = $picture['image_mime'];
                }
            }
        } elseif (isset($fileInfo['comments']['picture'][0])) {
            $picture = $fileInfo['comments']['picture'][0];
            if (isset($picture['data']) && isset($picture['image_mime'])) {
                $this->tags['cover_data'] = $picture['data'];
                $this->tags['cover_mime'] = $picture['image_mime'];
            }
        }
        
        return $this->tags;
    }
    
    // =============================================
    // 2. نوشتن تگ‌ها در فایل MP3 با getID3 - اصلاح شده مطابق مستندات
    // =============================================
    public function writeTags($newTags, $coverData = null) {
        if (!file_exists($this->filePath)) {
            $this->errors[] = 'فایل وجود ندارد: ' . $this->filePath;
            return false;
        }
        
        // ریست کردن خطاها و هشدارها
        $this->errors = array();
        $this->warnings = array();
        
        // آماده‌سازی تگ‌ها برای نوشتن
        $tagData = array();
        
        // فریم‌های متنی - طبق مستندات getID3
        if (!empty($newTags['title'])) {
            $tagData['title'][] = $newTags['title'];
        }
        if (!empty($newTags['artist'])) {
            $tagData['artist'][] = $newTags['artist'];
        }
        if (!empty($newTags['album'])) {
            $tagData['album'][] = $newTags['album'];
        }
        if (!empty($newTags['year'])) {
            $tagData['year'][] = $newTags['year'];
        }
        if (!empty($newTags['genre'])) {
            $tagData['genre'][] = $newTags['genre'];
        }
        if (!empty($newTags['comment'])) {
            $tagData['comment'][] = $newTags['comment'];
        }
        if (!empty($newTags['track'])) {
            $tagData['track_number'][] = $newTags['track'];
        }
        if (!empty($newTags['disc'])) {
            $tagData['part_number'][] = $newTags['disc'];
        }
        if (!empty($newTags['composer'])) {
            $tagData['composer'][] = $newTags['composer'];
        }
        if (!empty($newTags['lyrics'])) {
            $tagData['lyrics'][] = $newTags['lyrics'];
        }
        
        // =============================================
        // کاور (APIC) - مطابق مستندات رسمی getID3
        // کلید صحیح "mime" است، نه "image_mime"
        // =============================================
        if ($coverData && isset($coverData['data']) && !empty($coverData['data'])) {
            $mime = $coverData['mime'] ?? 'image/jpeg';
            
            // ساختار صحیح attached_picture طبق دموی رسمی getID3
            $tagData['attached_picture'][] = array(
                'data'          => $coverData['data'],
                'picturetypeid' => 3, // 3 = Cover (front)
                'description'   => 'Cover',
                'mime'          => $mime   // توجه: کلید "mime" درست است، نه "image_mime"
            );
        }
        
        // تنظیمات writer - طبق مستندات رسمی
        $this->tagWriter->filename = $this->filePath;
        $this->tagWriter->tagformats = array('id3v2.3');
        $this->tagWriter->overwrite_tags = true;
        $this->tagWriter->remove_other_tags = false;
        $this->tagWriter->tag_encoding = 'UTF-8';
        $this->tagWriter->tag_data = $tagData;
        
        // نوشتن تگ‌ها
        $result = $this->tagWriter->WriteTags();
        
        // ذخیره خطاها و هشدارها
        if (!empty($this->tagWriter->errors)) {
            $this->errors = array_merge($this->errors, $this->tagWriter->errors);
        }
        if (!empty($this->tagWriter->warnings)) {
            $this->warnings = array_merge($this->warnings, $this->tagWriter->warnings);
        }
        
        if ($result) {
            // دوباره فایل را بخوان تا اطلاعات به‌روز شوند
            $this->tags = $this->readTags();
            return true;
        }
        
        return false;
    }
    
    // =============================================
    // 3. دریافت خطاها و هشدارها
    // =============================================
    public function getErrors() {
        return $this->errors;
    }
    
    public function getWarnings() {
        return $this->warnings;
    }
    
    // =============================================
    // 4. توابع عمومی برای دسترسی
    // =============================================
    public function getTags() {
        return $this->tags;
    }
    
    public function getFilePath() {
        return $this->filePath;
    }
}

// =====================================================
// توابع کمکی
// =====================================================

function cleanUploadsFolder() {
    $uploadDir = 'uploads/';
    if (is_dir($uploadDir)) {
        $files = glob($uploadDir . '*');
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }
    }
}

function cleanBackupsFolder() {
    $backupDir = 'backups/';
    if (is_dir($backupDir)) {
        $files = glob($backupDir . '*');
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }
    }
}

// =====================================================
// پردازش درخواست‌ها
// =====================================================

$uploadDir = 'uploads/';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

$backupDir = 'backups/';
if (!is_dir($backupDir)) {
    mkdir($backupDir, 0777, true);
}

// =============================================
// 1. آپلود فایل - پاک کردن فایل‌های قبلی
// =============================================
if (isset($_FILES['audio_file']) && $_FILES['audio_file']['error'] === UPLOAD_ERR_OK) {
    // پاک کردن تمام فایل‌های قبلی
    cleanUploadsFolder();
    
    $fileName = basename($_FILES['audio_file']['name']);
    $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    
    if ($fileExt !== 'mp3') {
        $_SESSION['error'] = '❌ فقط فایل‌های MP3 پشتیبانی می‌شوند!';
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    }
    
    $filePath = $uploadDir . time() . '_' . $fileName;
    
    if (move_uploaded_file($_FILES['audio_file']['tmp_name'], $filePath)) {
        $editor = new MP3TagEditor($filePath);
        $tags = $editor->readTags();
        
        $_SESSION['audio_file'] = $filePath;
        $_SESSION['audio_tags'] = $tags;
        $_SESSION['message'] = '✅ فایل با موفقیت آپلود شد!';
        header('Location: ' . $_SERVER['PHP_SELF'] . '?edit=1');
        exit;
    } else {
        $_SESSION['error'] = '❌ خطا در آپلود فایل!';
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    }
}

// =============================================
// 2. آپلود کاور (مستقل)
// =============================================
if (isset($_POST['upload_cover']) && isset($_SESSION['audio_file'])) {
    $filePath = $_SESSION['audio_file'];
    
    if (file_exists($filePath) && isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] === UPLOAD_ERR_OK) {
        // خواندن داده‌های تصویر
        $imageData = file_get_contents($_FILES['cover_image']['tmp_name']);
        $imageMime = mime_content_type($_FILES['cover_image']['tmp_name']);
        
        if ($imageData === false) {
            $_SESSION['error'] = '❌ خطا در خواندن تصویر!';
            header('Location: ' . $_SERVER['PHP_SELF'] . '?edit=1');
            exit;
        }
        
        $coverData = [
            'data' => $imageData,
            'mime' => $imageMime
        ];
        
        $editor = new MP3TagEditor($filePath);
        $currentTags = $editor->readTags();
        
        if ($editor->writeTags($currentTags, $coverData)) {
            $newTags = $editor->readTags();
            $_SESSION['audio_tags'] = $newTags;
            $_SESSION['message'] = '✅ کاور با موفقیت به‌روزرسانی شد!';
        } else {
            $errors = $editor->getErrors();
            $warnings = $editor->getWarnings();
            $errorMsg = '❌ خطا در ذخیره کاور!';
            if (!empty($errors)) {
                $errorMsg .= ' خطاها: ' . implode(' | ', $errors);
            }
            if (!empty($warnings)) {
                $errorMsg .= ' هشدارها: ' . implode(' | ', $warnings);
            }
            $_SESSION['error'] = $errorMsg;
        }
    } else {
        $_SESSION['error'] = '❌ لطفاً یک عکس انتخاب کنید!';
    }
    header('Location: ' . $_SERVER['PHP_SELF'] . '?edit=1');
    exit;
}

// =============================================
// 3. ذخیره تغییرات (با دکمه ذخیره)
// =============================================
if (isset($_POST['save_tags']) && isset($_SESSION['audio_file'])) {
    $filePath = $_SESSION['audio_file'];
    
    if (file_exists($filePath)) {
        $tags = [
            'title' => trim($_POST['title'] ?? ''),
            'artist' => trim($_POST['artist'] ?? ''),
            'album' => trim($_POST['album'] ?? ''),
            'year' => trim($_POST['year'] ?? ''),
            'genre' => trim($_POST['genre'] ?? ''),
            'comment' => trim($_POST['comment'] ?? ''),
            'track' => trim($_POST['track'] ?? ''),
            'disc' => trim($_POST['disc'] ?? ''),
            'composer' => trim($_POST['composer'] ?? ''),
            'lyrics' => trim($_POST['lyrics'] ?? '')
        ];
        
        $coverData = null;
        if (isset($_SESSION['audio_tags']['cover_data']) && $_SESSION['audio_tags']['cover_data']) {
            $coverData = [
                'data' => $_SESSION['audio_tags']['cover_data'],
                'mime' => $_SESSION['audio_tags']['cover_mime'] ?? 'image/jpeg'
            ];
        }
        
        $editor = new MP3TagEditor($filePath);
        if ($editor->writeTags($tags, $coverData)) {
            $newTags = $editor->readTags();
            $_SESSION['audio_tags'] = $newTags;
            $_SESSION['message'] = '✅ اطلاعات آهنگ با موفقیت ذخیره شد!';
        } else {
            $errors = $editor->getErrors();
            $warnings = $editor->getWarnings();
            $errorMsg = '❌ خطا در ذخیره اطلاعات!';
            if (!empty($errors)) {
                $errorMsg .= ' خطاها: ' . implode(' | ', $errors);
            }
            if (!empty($warnings)) {
                $errorMsg .= ' هشدارها: ' . implode(' | ', $warnings);
            }
            $_SESSION['error'] = $errorMsg;
        }
    } else {
        $_SESSION['error'] = '❌ فایل وجود ندارد!';
    }
    header('Location: ' . $_SERVER['PHP_SELF'] . '?edit=1');
    exit;
}

// =============================================
// 4. دانلود فایل - با پاک کردن کامل
// =============================================
if (isset($_GET['download']) && isset($_SESSION['audio_file'])) {
    $filePath = $_SESSION['audio_file'];
    
    if (file_exists($filePath)) {
        $fileName = basename($filePath);
        $fileName = preg_replace('/^\d+_/', '', $fileName);
        
        header('Content-Type: audio/mpeg');
        header('Content-Disposition: attachment; filename="' . $fileName . '"');
        header('Content-Length: ' . filesize($filePath));
        header('Cache-Control: private, max-age=0, must-revalidate');
        header('Pragma: public');
        
        // خواندن و ارسال فایل
        readfile($filePath);
        
        // حذف فایل دانلود شده
        if (file_exists($filePath)) {
            unlink($filePath);
        }
        
        // پاک کردن تمام فایل‌های باقیمانده در uploads
        cleanUploadsFolder();
        
        // پاک کردن Session
        session_unset();
        session_destroy();
        
        exit;
    }
}

// =============================================
// 5. حذف فایل
// =============================================
if (isset($_GET['delete']) && isset($_SESSION['audio_file'])) {
    if (file_exists($_SESSION['audio_file'])) {
        unlink($_SESSION['audio_file']);
    }
    cleanUploadsFolder();
    session_destroy();
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

// =============================================
// 6. خواندن مجدد تگ‌ها از فایل
// =============================================
if (isset($_GET['refresh']) && isset($_SESSION['audio_file'])) {
    if (file_exists($_SESSION['audio_file'])) {
        $editor = new MP3TagEditor($_SESSION['audio_file']);
        $tags = $editor->readTags();
        $_SESSION['audio_tags'] = $tags;
        $_SESSION['message'] = '🔄 اطلاعات به‌روزرسانی شد!';
    }
    header('Location: ' . $_SERVER['PHP_SELF'] . '?edit=1');
    exit;
}

// =============================================
// 7. پشتیبان‌گیری از فایل (Backup)
// =============================================
if (isset($_GET['backup']) && isset($_SESSION['audio_file'])) {
    $filePath = $_SESSION['audio_file'];
    
    if (file_exists($filePath)) {
        $backupDir = 'backups/';
        if (!is_dir($backupDir)) {
            mkdir($backupDir, 0777, true);
        }
        
        $fileName = basename($filePath);
        $fileName = preg_replace('/^\d+_/', '', $fileName);
        $backupName = date('Y-m-d_H-i-s') . '_' . $fileName;
        $backupPath = $backupDir . $backupName;
        
        if (copy($filePath, $backupPath)) {
            $_SESSION['message'] = '✅ پشتیبان با موفقیت ذخیره شد: ' . $backupName;
        } else {
            $_SESSION['error'] = '❌ خطا در ایجاد پشتیبان!';
        }
    } else {
        $_SESSION['error'] = '❌ فایل وجود ندارد!';
    }
    header('Location: ' . $_SERVER['PHP_SELF'] . '?edit=1');
    exit;
}

// =============================================
// 8. انتقال متغیرها به صفحه
// =============================================
$editMode = isset($_GET['edit']) && isset($_SESSION['audio_file']) && file_exists($_SESSION['audio_file']);
$tags = null;
$fileName = '';
$message = isset($_SESSION['message']) ? $_SESSION['message'] : '';
$error = isset($_SESSION['error']) ? $_SESSION['error'] : '';

if ($editMode) {
    $filePath = $_SESSION['audio_file'];
    $editor = new MP3TagEditor($filePath);
    $tags = $editor->readTags();
    $_SESSION['audio_tags'] = $tags;
    $fileName = basename($filePath);
}

if (isset($_SESSION['message'])) unset($_SESSION['message']);
if (isset($_SESSION['error'])) unset($_SESSION['error']);

?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes" />
    <link rel="icon" href="fav.png" type="image/png" />
    <title>ویرایشگر اطلاعات آهنگ | MP3 Tag Editor</title>
    <style>
        @font-face { font-family: titr-bold; src: url(B\ Titr\ Bold_0.ttf); }
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: titr-bold, 'Vazirmatn', system-ui, sans-serif; }
        body {
            background: linear-gradient(145deg, #12161c 0%, #1a1f2a 100%);
            min-height: 100vh;
            padding: 2rem 1.5rem;
        }
        .container {
            max-width: 900px;
            margin: 0 auto;
            background: rgba(30, 35, 48, 0.75);
            backdrop-filter: blur(12px);
            border-radius: 48px;
            padding: 2rem;
            box-shadow: 0 25px 45px rgba(0,0,0,0.4), 0 0 0 1px rgba(255,255,200,0.1);
        }
        h1 {
            font-size: 2rem;
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
            padding-bottom: 12px;
        }
        .upload-area {
            background: rgba(15, 18, 25, 0.6);
            border: 2px dashed #ffb347;
            border-radius: 28px;
            padding: 2rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
            margin-bottom: 1.5rem;
        }
        .upload-area:hover {
            border-color: #ffd966;
            background: rgba(255, 217, 102, 0.05);
        }
        .upload-icon { font-size: 3rem; margin-bottom: 0.5rem; }
        .upload-text { color: #ddf4ff; font-size: 1.1rem; }
        .upload-hint { color: #8a9bb0; font-size: 0.85rem; margin-top: 6px; }
        #fileInput { display: none; }
        
        .edit-section {
            display: <?php echo $editMode ? 'block' : 'none'; ?>;
            animation: fadeIn 0.4s ease;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .file-info {
            background: rgba(15, 18, 25, 0.5);
            border-radius: 20px;
            padding: 1rem;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            flex-wrap: wrap;
            border: 1px solid #2d3748;
        }
        .file-info .icon { font-size: 2rem; }
        .file-info .name { color: #ffec99; font-size: 1.1rem; word-break: break-all; }
        .file-info .size { color: #8a9bb0; font-size: 0.85rem; }
        .file-info .format-badge {
            background: #ffb347;
            color: #1e293b;
            padding: 2px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: bold;
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 0.5rem;
            margin-bottom: 1rem;
        }
        .info-item {
            background: rgba(15, 18, 25, 0.3);
            border-radius: 12px;
            padding: 0.5rem;
            text-align: center;
        }
        .info-item .label {
            color: #8a9bb0;
            font-size: 0.7rem;
        }
        .info-item .value {
            color: #ffec99;
            font-size: 0.85rem;
        }
        @media (max-width: 550px) {
            .info-grid { grid-template-columns: 1fr 1fr; }
        }
        
        .cover-preview {
            text-align: center;
            margin: 1rem 0;
        }
        .cover-preview img {
            max-width: 200px;
            max-height: 200px;
            border-radius: 16px;
            border: 2px solid #2d3748;
            box-shadow: 0 8px 24px rgba(0,0,0,0.3);
        }
        .cover-preview .no-cover {
            background: rgba(15, 18, 25, 0.5);
            border-radius: 16px;
            padding: 2rem;
            color: #6a7a8a;
            border: 1px dashed #2d3748;
            display: inline-block;
        }
        
        .form-group {
            margin-bottom: 1.2rem;
        }
        .form-group label {
            display: block;
            color: #ffec99;
            font-weight: bold;
            margin-bottom: 6px;
            font-size: 0.9rem;
        }
        .form-group input, .form-group textarea, .form-group select {
            width: 100%;
            padding: 10px 16px;
            border: 2px solid #2d3748;
            border-radius: 20px;
            background: #0f1219;
            color: #f0f3fa;
            font-size: 1rem;
            transition: all 0.2s;
            outline: none;
            font-family: inherit;
        }
        .form-group input:focus, .form-group textarea:focus {
            border-color: #ffd966;
            box-shadow: 0 0 0 3px rgba(255,217,102,0.2);
        }
        .form-group textarea { resize: vertical; min-height: 60px; }
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }
        @media (max-width: 550px) {
            .form-row { grid-template-columns: 1fr; }
        }
        
        .btn-group {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-top: 1.5rem;
        }
        .btn {
            border: none;
            padding: 12px 28px;
            border-radius: 40px;
            font-weight: bold;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.2s;
            font-family: inherit;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        .btn:hover { transform: translateY(-2px); filter: brightness(1.05); }
        .btn-success { background: #2b9348; color: white; }
        .btn-danger { background: #dc2f02; color: white; }
        .btn-secondary { background: #4a5568; color: white; }
        .btn-refresh { background: #ffb347; color: #1e293b; }
        .btn-download { background: #2196f3; color: white; }
        .btn-backup { background: #6c757d; color: white; }
        
        .message {
            padding: 12px 20px;
            border-radius: 20px;
            margin: 1rem 0;
            text-align: center;
            font-weight: bold;
        }
        .message.success { background: #2c3e2e80; color: #c7f9cc; border: 1px solid #2b9348; }
        .message.error { background: #3e2c2e80; color: #f9c7c7; border: 1px solid #dc2f02; }
        
        .cover-upload-area {
            border: 2px dashed #4a5568;
            border-radius: 20px;
            padding: 1.5rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
            background: rgba(15, 18, 25, 0.3);
        }
        .cover-upload-area:hover {
            border-color: #ffb347;
            background: rgba(255, 179, 71, 0.05);
        }
        .cover-upload-area .hint {
            color: #8a9bb0;
            font-size: 0.85rem;
        }
        #coverInput { display: none; }
        
        .credit {
            text-align: center;
            margin-top: 28px;
            font-size: 0.7rem;
            color: #6c7a91;
        }
        
        .home-btn {
            position: fixed;
            top: 20px;
            left: 20px;
            z-index: 9999;
            direction: ltr;
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
        }
        .home-btn:hover {
            background: #0d1c26;
            transform: scale(0.97);
        }
        
        .toast-msg {
            position: fixed;
            bottom: 30px;
            left: 50%;
            transform: translateX(-50%);
            background: #1e293b;
            color: #fff;
            padding: 12px 28px;
            border-radius: 50px;
            font-size: 0.9rem;
            z-index: 99999;
            animation: toastFade 2.5s ease forwards;
            font-family: system-ui;
            backdrop-filter: blur(4px);
            border: 1px solid rgba(255,255,200,0.2);
            box-shadow: 0 8px 24px rgba(0,0,0,0.35);
        }
        @keyframes toastFade {
            0% { opacity: 0; transform: translateX(-50%) translateY(20px); }
            15% { opacity: 1; transform: translateX(-50%) translateY(0); }
            85% { opacity: 1; transform: translateX(-50%) translateY(0); }
            100% { opacity: 0; transform: translateX(-50%) translateY(20px); }
        }
        
        .lyrics-box {
            background: rgba(15, 18, 25, 0.3);
            border-radius: 16px;
            padding: 1rem;
            border: 1px solid #2d3748;
        }
        .refresh-link {
            color: #ffb347;
            text-decoration: none;
            font-size: 0.85rem;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }
        .refresh-link:hover {
            color: #ffd966;
        }
        .cover-form-inline {
            display: inline;
        }
        .error-details {
            background: rgba(220, 47, 2, 0.1);
            border: 1px solid #dc2f02;
            border-radius: 12px;
            padding: 1rem;
            margin: 0.5rem 0;
            color: #f9c7c7;
            font-size: 0.85rem;
            text-align: right;
            direction: ltr;
        }
        .error-details strong {
            color: #ff6b6b;
        }
    </style>
</head>
<body>

<a href="../../index.php" class="home-btn">🏠 بازگشت به صفحه اصلی</a>

<div class="container">
    <h1>🎵 ویرایشگر اطلاعات آهنگ</h1>
    <div class="sub">ویرایش تگ‌های MP3 | کاور، عنوان، خواننده، آلبوم و...</div>

    <?php if ($message): ?>
        <div class="message success"><?php echo $message; ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="message error"><?php echo $error; ?></div>
    <?php endif; ?>

    <!-- بخش آپلود -->
    <div class="upload-area" onclick="document.getElementById('fileInput').click()">
        <div class="upload-icon">📁</div>
        <div class="upload-text">برای آپلود فایل MP3 کلیک کنید</div>
        <div class="upload-hint">فقط فایل‌های MP3 | حداکثر ۵۰ مگابایت</div>
    </div>
    <form method="post" enctype="multipart/form-data" id="uploadForm">
        <input type="file" name="audio_file" id="fileInput" accept=".mp3,audio/mpeg" onchange="document.getElementById('uploadForm').submit();" />
    </form>

    <!-- بخش ویرایش -->
    <div class="edit-section">
        <div class="file-info">
            <span class="icon">🎵</span>
            <span class="name"><?php echo htmlspecialchars($fileName); ?></span>
            <span class="format-badge">MP3</span>
            <span class="size">(<?php echo $editMode && isset($_SESSION['audio_file']) ? number_format(filesize($_SESSION['audio_file']) / 1024, 1) : '0'; ?> KB)</span>
            <?php if ($editMode && $tags && isset($tags['duration']) && $tags['duration']): ?>
                <span style="color:#8a9bb0;font-size:0.85rem;">⏱ <?php echo htmlspecialchars($tags['duration']); ?></span>
            <?php endif; ?>
            <a href="?refresh=1" class="refresh-link" title="به‌روزرسانی اطلاعات">🔄</a>
        </div>

        <?php if ($editMode && $tags): ?>
        
        <!-- اطلاعات فایل -->
        <div class="info-grid">
            <?php if (isset($tags['bitrate']) && $tags['bitrate']): ?>
            <div class="info-item">
                <div class="label">بیت‌ریت</div>
                <div class="value"><?php echo htmlspecialchars($tags['bitrate']); ?> kbps</div>
            </div>
            <?php endif; ?>
            <?php if (isset($tags['sample_rate']) && $tags['sample_rate']): ?>
            <div class="info-item">
                <div class="label">نمونه‌برداری</div>
                <div class="value"><?php echo htmlspecialchars($tags['sample_rate']); ?> Hz</div>
            </div>
            <?php endif; ?>
            <?php if (isset($tags['duration']) && $tags['duration']): ?>
            <div class="info-item">
                <div class="label">مدت زمان</div>
                <div class="value"><?php echo htmlspecialchars($tags['duration']); ?></div>
            </div>
            <?php endif; ?>
        </div>

        <!-- فرم کاور (مستقل) -->
        <div class="form-group">
            <label>🖼️ کاور آهنگ</label>
            <div class="cover-preview">
                <?php if (isset($tags['cover_data']) && $tags['cover_data']): ?>
                    <img src="data:<?php echo htmlspecialchars($tags['cover_mime'] ?? 'image/jpeg'); ?>;base64,<?php echo base64_encode($tags['cover_data']); ?>" alt="کاور" />
                <?php else: ?>
                    <div class="no-cover">🎵 بدون کاور</div>
                <?php endif; ?>
            </div>
            <form method="post" enctype="multipart/form-data" class="cover-form-inline">
                <input type="hidden" name="upload_cover" value="1" />
                <div class="cover-upload-area" onclick="document.getElementById('coverInput').click()">
                    <div>📤 برای تغییر کاور کلیک کنید</div>
                    <div class="hint">فرمت‌های مجاز: JPG, PNG, GIF</div>
                </div>
                <input type="file" name="cover_image" id="coverInput" accept="image/*" onchange="this.form.submit();" />
            </form>
        </div>

        <!-- فرم اطلاعات اصلی -->
        <form method="post" enctype="multipart/form-data" id="editForm">
            
            <div class="form-row">
                <div class="form-group">
                    <label>🎵 عنوان آهنگ</label>
                    <input type="text" name="title" value="<?php echo htmlspecialchars($tags['title'] ?? ''); ?>" placeholder="عنوان آهنگ..." />
                </div>
                <div class="form-group">
                    <label>🎤 خواننده / هنرمند</label>
                    <input type="text" name="artist" value="<?php echo htmlspecialchars($tags['artist'] ?? ''); ?>" placeholder="نام خواننده..." />
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>💿 آلبوم</label>
                    <input type="text" name="album" value="<?php echo htmlspecialchars($tags['album'] ?? ''); ?>" placeholder="نام آلبوم..." />
                </div>
                <div class="form-group">
                    <label>📅 سال</label>
                    <input type="text" name="year" value="<?php echo htmlspecialchars($tags['year'] ?? ''); ?>" placeholder="مثال: 2024" />
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>🔢 شماره آهنگ</label>
                    <input type="text" name="track" value="<?php echo htmlspecialchars($tags['track'] ?? ''); ?>" placeholder="مثال: 1/12" />
                </div>
                <div class="form-group">
                    <label>💿 شماره دیسک</label>
                    <input type="text" name="disc" value="<?php echo htmlspecialchars($tags['disc'] ?? ''); ?>" placeholder="مثال: 1/2" />
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>🎵 سبک (ژانر)</label>
                    <input type="text" name="genre" value="<?php echo htmlspecialchars($tags['genre'] ?? ''); ?>" placeholder="مثال: Pop, Rock, Classical" />
                </div>
                <div class="form-group">
                    <label>🎼 آهنگساز</label>
                    <input type="text" name="composer" value="<?php echo htmlspecialchars($tags['composer'] ?? ''); ?>" placeholder="نام آهنگساز..." />
                </div>
            </div>

            <div class="form-group">
                <label>💬 توضیحات / نظر</label>
                <input type="text" name="comment" value="<?php echo htmlspecialchars($tags['comment'] ?? ''); ?>" placeholder="توضیحات اضافی..." />
            </div>

            <div class="form-group">
                <label>📝 متن آهنگ (Lyrics)</label>
                <div class="lyrics-box">
                    <textarea name="lyrics" rows="4" placeholder="متن آهنگ را وارد کنید..."><?php echo htmlspecialchars($tags['lyrics'] ?? ''); ?></textarea>
                </div>
            </div>

            <div class="btn-group">
                <button type="submit" name="save_tags" class="btn btn-success">💾 ذخیره تغییرات</button>
                <button type="button" class="btn btn-secondary" onclick="window.location.href='<?php echo $_SERVER['PHP_SELF']; ?>'">🔄 آپلود جدید</button>
                <button type="button" class="btn btn-danger" onclick="if(confirm('آیا مطمئن هستید؟ فایل حذف می‌شود.')){ window.location.href='<?php echo $_SERVER['PHP_SELF']; ?>?delete=1'; }">🗑️ حذف فایل</button>
                <button type="button" class="btn btn-refresh" onclick="window.location.href='<?php echo $_SERVER['PHP_SELF']; ?>?refresh=1'">🔄 به‌روزرسانی</button>
                <a href="?download=1" class="btn btn-download" style="text-decoration:none;">📥 دانلود فایل ویرایش‌شده</a>
                <a href="?backup=1" class="btn btn-backup" style="text-decoration:none;">💾 پشتیبان‌گیری از فایل</a>
            </div>
        </form>
        <?php endif; ?>
    </div>

    <div class="credit">🎧 ویرایشگر حرفه‌ای اطلاعات آهنگ | فقط MP3</div>
</div>

<?php if ($message): ?>
<script>
    setTimeout(function() {
        const toast = document.createElement('div');
        toast.className = 'toast-msg';
        toast.textContent = '<?php echo addslashes($message); ?>';
        document.body.appendChild(toast);
        setTimeout(() => toast.remove(), 2500);
    }, 300);
</script>
<?php endif; ?>

</body>
</html>