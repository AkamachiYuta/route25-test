<?php
// 保存先ディレクトリ（書き込み権限が必要）
$save_dir = __DIR__ . '/../contents/';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $title = $_POST['title'] ?? '';
  $slug = $_POST['slug'] ?? '';
  $content = $_POST['content'] ?? '';

  if (!$title || !$slug || !$content) {
    die('すべての項目を入力してください。');
  }

  // Markdown形式で保存
  $filename = $save_dir . $slug . '.md';
  $markdown = "---\ntitle: {$title}\ndate: " . date('Y-m-d') . "\n---\n\n{$content}";

  if (file_put_contents($filename, $markdown)) {
    echo "保存成功！<a href='index.html'>戻る</a>";
  } else {
    echo "保存に失敗しました。パーミッションを確認してください。";
  }
} else {
  echo "無効なアクセスです。";
}
