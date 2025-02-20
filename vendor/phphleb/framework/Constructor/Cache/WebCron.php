<?php


namespace Hleb\Constructor\Cache;

use Hleb\Static\Path;
use Hleb\Static\Settings;

final class WebCron
{
  private const DIR = '@storage/cache/source/0_webcron';

  public static function offer(string $key, \Closure $func, int $period = 1): bool
  {
      $I = DIRECTORY_SEPARATOR;
      $dir = Path::get(self::DIR);
      if (!\file_exists($dir)) {
          @\mkdir($dir, 0775, true);
      }
      $file = Path::get(self::DIR . $I . $key . '_' . $period . '.txt');
      $time = Settings::getParam('system', 'start.unixtime');
      if (\file_exists($file)) {
          $previous = @\file_get_contents($file);
          if (!$previous || $previous == $time || (float)$previous >= $time - $period) {
              return false;
          }
      }
      @\file_put_contents($file, $time) and $func();
      @\chmod($file, 0664);

      return true;
  }
}
