<?php
session_start();
require_once __DIR__ . '/vendor/autoload.php'; // change path as needed
$fb = new \Facebook\Facebook([
  'app_id' => 'app-id',
  'app_secret' => 'app-secret',
  'default_graph_version' => 'v2.10',
]);

if(isset($_GET['code'])) {

    $helper = $fb->getRedirectLoginHelper();
try {
  $accessToken = $helper->getAccessToken();
  $response = $fb->get('/me?fields=id,name', $accessToken);
  print_r($response);

} catch(Facebook\Exceptions\FacebookResponseException $e) {
  // When Graph returns an error
  echo 'Graph returned an error: ' . $e->getMessage();
  exit;
} catch(Facebook\Exceptions\FacebookSDKException $e) {
  // When validation fails or other local issues
  echo 'Facebook SDK returned an error: ' . $e->getMessage();
  exit;
}

if (! isset($accessToken)) {
  if ($helper->getError()) {
    header('HTTP/1.0 401 Unauthorized');
    echo "Error: " . $helper->getError() . "\n";
    echo "Error Code: " . $helper->getErrorCode() . "\n";
    echo "Error Reason: " . $helper->getErrorReason() . "\n";
    echo "Error Description: " . $helper->getErrorDescription() . "\n";
  } else {
    header('HTTP/1.0 400 Bad Request');
    echo 'Bad request';
  }
  exit;
}

} else {
    $helper = $fb->getRedirectLoginHelper();

$permissions = ['email']; // Optional permissions
$loginUrl = $helper->getLoginUrl('http://localhost:8080/fb-login/file.php', $permissions);

echo '<a href="' . htmlspecialchars($loginUrl) . '">Log in with Facebook!</a>';
}