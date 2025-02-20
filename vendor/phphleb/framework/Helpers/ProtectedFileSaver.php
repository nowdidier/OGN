<?php


namespace Hleb\Helpers;

use Hleb\CoreProcessException;

final class ProtectedFileSaver
{

    public function save(string $path, string $data): void
    {
       \hl_create_directory($path);

       $fp = \fopen($path, 'wb+');
       if ($fp === false || !\flock($fp, LOCK_EX)) {


           \file_put_contents($path, $data);
           $fp and \fclose($fp);
           @\chmod($path, 0664);
           return;
       }
       \ftruncate($fp, 0);
       if (!\fwrite($fp, $data)) {
           throw new CoreProcessException('Failed to save the file, check the permissions on the directory.');
       }
       \fflush($fp);
       \flock($fp, LOCK_UN);

       \fclose($fp);
        @\chmod($path, 0664);
   }
}
