<?php

if (!defined(constant_name: 'CURL_SSLVERSION_TLSv1_2'))
  define(constant_name: 'CURL_SSLVERSION_TLSv1_2', value: 6);

require_once __DIR__ . "/../vendor/autoload.php";

use Github\Client;
use Github\AuthMethod;
use Dotenv\Dotenv;

//  .env 読み込み
$dotenv = Dotenv::createImmutable(paths: __DIR__ . "/../");
$dotenv->load();

// トークン取得
$token = $_ENV["GITHUB_TOKEN"] ?? null;
if (!$token) {
  die("トークンが見つかりません。");
}

// GitHubその他設定
$owner = "AkamachiYuta";
$repo = "route25_akamachi.jp";
$branch = "main";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $title = $_POST["title"] ?? "";
  $slug = $_POST["slug"] ?? "";
  $content = $_POST["content"] ?? "";

  if (!$title || !$slug || !$content) {
    die("すべての項目を入力してください。");
  }

  // Markdownの組み立て
  $filename = "contents/{$slug}.md"; // ← GitHub上のパス
  $markdown = "---\ntitle: {$title}\ndate: " . date(format: "Y-m-d") . "\n---\n\n{$content}";
  $encoded = base64_encode(string: $markdown);

  // GitHubクライアント
  $client = new Client();
  $client->authenticate(
    tokenOrLogin: $token,
    password: null,
    authMethod: AuthMethod::ACCESS_TOKEN
  );

  try {
    $client->api(name: "repo")->contents()->create(
      $owner,
      $repo,
      $filename,
      $encoded,
      "記事投稿: {$title}",
      $branch,
      [
        "name" => "Route25 Submit",
        "email" => "route25@example.com",
      ]
    );
    echo "✅ GitHubへの保存成功！<a href='index.html'>戻る</a>";
  } catch (\Github\Exception\RuntimeException $e) {
    echo "❌ 保存失敗: " . htmlspecialchars(string: $e->getMessage());
  }
} else {
  echo "無効なアクセスです。";
}
