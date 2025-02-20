<?php


namespace Hleb\Main\Routes\Search;

class RouteAnyFileManager extends RouteFileManager
{

    public function getBlock(): false|array
    {
        $infoCacheDuplicate = self::$infoCache;
        $stubDataDuplicate = self::$stubData;
        $result = parent::getBlock();
        self::$infoCache = $infoCacheDuplicate;
        self::$stubData = $stubDataDuplicate;

        return $result;
    }
}
