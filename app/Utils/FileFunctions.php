<?php
  namespace App\Utils;
  
  class FileFunctions {
    public static function openFile($filepath, $mode) {
      if (!@fopen($filepath, $mode) && $mode !== 'r') {
        mkdir(dirname($filepath), 0777, true);
      }
      
      $fileHandle = fopen($filepath, $mode);
      if (!$fileHandle) {
        throw new \Exception("Could not open the file: " . $filepath . "\n");
      }

      return $fileHandle;
    }
    
    public static function readFromFile($filepath) {
      $fileHandle = self::openFile($filepath, 'r');
      
      $content = '';
      while (feof($fileHandle) !== TRUE) {
        $content .= fread($fileHandle, 4096);
      }
      fclose($fileHandle);
      
      return $content;
    }
    
    public static function writeToFile($content, $filepath) {
      $fileHandle = self::openFile($filepath, 'w');
      fwrite($fileHandle, $content);
      fclose($fileHandle);
    }
    
    public static function addEmptyLineToFile($filepath) {
      $fileHandle = self::openFile($filepath, 'a');
      fwrite($fileHandle, PHP_EOL);
      fclose($fileHandle);
    }
    
    public static function closeFile($fileHandle) {
      fclose($fileHandle);
    }
    
    public static function getFilesFromFolderRecursive($folder) {
      $files = array(); 
      
      $rii = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($folder));
      
      foreach ($rii as $file) {
        if(!$file->isDir()) {
          $files[] = $file->getPathname();
        }
      }
      
      return $files;
    }
    
    public static function saveCsvList($list, $filepath) {
      $fileHandle = self::openFile($filepath, 'w');
      foreach($list as $item) {
        fputcsv($fileHandle, $item);
      }
      fclose($fileHandle);
    }
  }