<?php

namespace Chiave\ErepublikScrobblerBundle\Libraries;

class CurlUtils {

  protected $_curl;

  public function __construct() {
    $options = array(
      CURLOPT_HEADER => false,
      CURLOPT_COOKIE => true,
      CURLOPT_COOKIEFILE => 'app/cache/cookies.txt',
      CURLOPT_COOKIEJAR => 'app/cache/cookies.txt',
      CURLOPT_ENCODING => 'utf8',
      CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 6.2; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/32.0.1667.0 Safari/537.36',
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_AUTOREFERER => true,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_CONNECTTIMEOUT => 60,
      CURLOPT_TIMEOUT => 60,
      CURLOPT_VERBOSE => false,
    );
    $this->_curl = curl_init();
    curl_setopt_array($this->_curl, $options);
  }

  public function __destruct() {
    curl_close($this->_curl);
  }

  protected function _post($url, $referrer, $data) {
    $options = array(
      CURLOPT_URL => $url,
      CURLOPT_REFERER => $referrer,
      CURLOPT_POST => true,
      CURLOPT_POSTFIELDS => $data,
    );
    curl_setopt_array($this->_curl, $options);
    $return = curl_exec($this->_curl);
    if (!$return) {
      echo curl_error($this->_curl);
    }
    return $return;
  }

  protected function _get($url, $referrer = '') {
    $options = array(
      CURLOPT_URL => $url,
      CURLOPT_REFERER => $referrer,
      CURLOPT_POST => false
    );
    curl_setopt_array($this->_curl, $options);
    $return = curl_exec($this->_curl);
    if (!$return) {
      echo curl_error($this->_curl);
    }
    return $return;
  }
}
