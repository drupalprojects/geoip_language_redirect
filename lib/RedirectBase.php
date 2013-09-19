<?php

namespace Drupal\geoip_language_redirect;

abstract class RedirectBase {
  protected $api;

  public function __construct($api) {
    $this->api = $api;
  }

  /**
   * Check if redirect is still possible and redirect if
   * it's already known where.
   */
  public function checkAndRedirect() {
    return TRUE;
  }
  
  /**
   * Redirect to the same page in a different language.
   */
  public function redirect($langCode) {
    $this->api->setCookie($langCode);
    $path = isset($path) ? $path : $this->api->currentPath();
    if (!($links = $this->api->switchLinks($path))) {
      return;
    }
    if (!isset($links[$langCode]) || !isset($links[$langCode]['href'])) {
      $langCode = $this->api->defaultLanguage();
    }
    // Don't redirect to the very same language.
    if ($langCode == $this->api->currentLanguage()) {
      return;
    }
    if (isset($links[$langCode])) {
      $link = &$links[$langCode];
      $path = isset($link['href']) ? $link['href'] : $path;
      if ($this->api->checkAccess($path, $langCode)) {
        $this->api->redirect($path, $link['language']);
      }
    }
  }
}
