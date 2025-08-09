<?php

if (!defined(constant_name: 'CURL_SSLVERSION_TLSv1_2'))
  define(constant_name: 'CURL_SSLVERSION_TLSv1_2', value: 6);

require_once __DIR__ . '/../vendor/autoload.php';

use Github\Client;
use Github\AuthMethod;
use Dotenv\Dotenv;

header(header: 'Content-Type: text/html; charset=UTF-8');

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
  $content = base64_decode(string: $file['content']);
  echo "<pre>";
  var_dump(value: $file['content']);
  var_dump(value: $content);
  echo "</pre>";
  echo $content;
} catch (\Exception $e) {
  echo "❌ 取得失敗: " . htmlspecialchars(string: $e->getMessage());
}
