<?php
  namespace App\SirTrevor;

  use HTML;

  class SirTrevor {
      public static function stylesheets() {
          $config = config('sir-trevor');

          $stylesheets = '';
          if (isset($config['stylesheets']) && is_array($config['stylesheets'])) {
              foreach ($config['stylesheets'] as $stylesheet) {
                  if (file_exists(public_path($stylesheet))) {
                      $stylesheets .= HTML::style($stylesheet);
                  }
              }
          }

          return $stylesheets;
      }

      public static function scripts($types = [], $required = []) {
          $config = config('sir-trevor');

          $blockTypes = empty($types) ? json_encode($config['blockTypes']) : json_encode($types);
          $blockRequired = json_encode($required);

          $scripts = '';
          if (isset($config['scripts']) && is_array($config['scripts'])) {
              foreach ($config['scripts'] as $script) {
                  if (file_exists(public_path($script))) {
                      $scripts .= HTML::script($script);
                  }
              }
          }

          return $scripts . view('scripts.sir-trevor', compact('config', 'blockTypes', 'blockRequired'));
    }
  }
