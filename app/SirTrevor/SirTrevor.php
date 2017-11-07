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
    
      public static function scripts() {
          $config = config('sir-trevor');
          $blockTypes = "'" . implode("', '", $config['blockTypes']) . "'";
          
          $scripts = '';
          if (isset($config['scripts']) && is_array($config['scripts'])) {
              foreach ($config['scripts'] as $script) {
                  //if (file_exists(public_path($script))) {
                      $scripts .= HTML::script($script);
                  //}
              }
          }
          
          return $scripts . view('sirtrevor.scripts', compact('config', 'blockTypes'));
      }
  }
