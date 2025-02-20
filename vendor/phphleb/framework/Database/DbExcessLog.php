<?php

declare(strict_types=1);

namespace Hleb\Database;

use Hleb\Constructor\Data\DynamicParams;
use Hleb\Constructor\Data\SystemSettings;
use Hleb\Main\Logger\LogLevel;
use Hleb\Static\Log;

final class DbExcessLog
{
    protected static ?string $requestId = null;

    protected static float $queryTime = 0;

    protected static bool $notificationSent = false;

    public static function set(float|int $time): void
    {
        if (SystemSettings::isCli() || SystemSettings::getCommonValue('log.db.excess') <= 0) {
            return;
        }
        $id = DynamicParams::getDynamicRequestId();
        if (!self::$requestId) {
            self::$requestId = $id;
        }
        if (self::$requestId !== $id) {
            self::$queryTime = 0;
            self::$notificationSent = false;
        }
        self::$queryTime += $time;
        $timeExcess = SystemSettings::getCommonValue('log.db.excess');
        if (!self::$notificationSent && self::$queryTime > $timeExcess) {
            Log::log(
                LogLevel::STATE, SystemDB::DB_PREFIX . ' #db_total_time_exceeded > ' . $timeExcess . ' sec. for request-id: ' . self::$requestId,
                [\Hleb\Main\Logger\Log::B7E_NAME => \Hleb\Main\Logger\Log::DB_B7E]
            );
            self::$notificationSent = true;
        }
    }
}
