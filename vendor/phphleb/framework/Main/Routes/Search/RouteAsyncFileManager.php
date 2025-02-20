<?php


namespace Hleb\Main\Routes\Search;

class RouteAsyncFileManager extends RouteFileManager
{

    public function getBlock(): false|array
    {
        if (self::$infoCache === null) {
            return parent::getBlock();
        }

        $this->init();

        if (self::$stubData) {
            $this->isBlocked = true;
            return \is_array(self::$stubData) ? self::$stubData : false;
        }
        return parent::searchBlock();
    }
}
