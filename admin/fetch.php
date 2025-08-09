<?php
require_once __DIR__ . '/../vendor/autoload.php';

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

// GitHub設定
$owner = 'AkamachiYuta';
$repo = 'route25_akamachi.jp';
$branch = 'main';
$path = 'dist/blog/test/index.html';

// GitHubクライアント
$client = new Client();
$client->authenticate(
  tokenOrLogin: $token,
  password: null,
  authMethod: AuthMethod::ACCESS_TOKEN
);

try {
  $file = $client->api(name: 'repo')->contents()->show(
    $owner,
    $repo,
    $path,
    $branch
  );
  $content = base64_decode($file['content']);

  header('Content-Type: text/html; charset=UTF-8');
  echo $content;
} catch (\Exception $e) {
  echo "❌ 取得失敗: " . htmlspecialchars($e->getMessage());
}
