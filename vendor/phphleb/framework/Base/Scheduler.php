<?php


declare(strict_types=1);

namespace Hleb\Base;

use Hleb\Constructor\Attributes\AvailableAsParent;

#[AvailableAsParent]
abstract class Scheduler extends Task
{
    private \DateTimeInterface|null $date = null;

    protected function everyMinute(array|string $commands = []): void
    {
        $this->execute($commands);
    }

    protected function everyHour(array|string $commands = []): void
    {

        if ($this->getDate()->format('i') === '00') {
            $this->execute($commands);
        }
    }

    protected function everyDay(array|string $commands = []): void
    {

        if ($this->getDate()->format('H:i') === '00:00') {
            $this->execute($commands);
        }
    }

    protected function every5Minutes(array|string $commands = []): void
    {
        $date = $this->getDate()->format('i');
        if ($date[1] === '0' || $date[1] === '5') {
            $this->execute($commands);
        }
    }

    protected function every10Minutes($commands = []): void
    {
        $date = $this->getDate()->format('i');
        if ($date[1] === '0') {
            $this->execute($commands);
        }
    }

    protected function every15Minutes(array|string $commands = []): void
    {
        $date = $this->getDate()->format('i');
        if (\in_array($date, ['00', '15', '30', '45'])) {
            $this->execute($commands);
        }
    }

    protected function every20Minutes(array|string $commands = []): void
    {
        $date = $this->getDate()->format('i');
        if (\in_array($date, ['00', '20', '40'])) {
            $this->execute($commands);
        }
    }

    protected function givenHour(array|int|string $h = [0]): bool
    {
        return $this->searchData($h, 'H');
    }

    protected function givenMonth(array|int|string $mn = [1]): bool
    {
        return $this->searchData($mn, 'm');
    }

    protected function givenMinutes(array|string $minutes = [0], array|string $commands = []): bool
    {
        return $this->searchData($minutes, 'i', $commands);
    }

    protected function isLeapYear(): bool
    {
        return ((int)$this->getDate()->format('L')) === 1;
    }

    protected function isAm(): bool
    {
        return $this->getDate()->format('a') === 'am';
    }

    protected function isPm(): bool
    {
        return $this->getDate()->format('a') === 'pm';
    }

    protected function givenMonday(): bool
    {
        return $this->givenWeeklyDay(1);
    }

    protected function givenTuesday(): bool
    {
        return $this->givenWeeklyDay(2);
    }

    protected function givenWednesday(): bool
    {
        return $this->givenWeeklyDay(3);
    }

    protected function givenThursday(): bool
    {
        return $this->givenWeeklyDay(4);
    }

    protected function givenFriday(): bool
    {
        return $this->givenWeeklyDay(5);
    }

    protected function givenSaturday(): bool
    {
        return $this->givenWeeklyDay(6);
    }

    protected function givenSunday(): bool
    {
        return $this->givenWeeklyDay(7);
    }

    protected function byPattern(string $format = 'Y-m-d H:i:s', string $date = '0000-00-00 00:00:00', array|string $commands = []): bool
    {
        if ($this->getDate()->format($format) === $date) {
            $this->execute($commands);
            return true;
        }
        return false;
    }

    protected function inNewYearDay(): bool
    {
        return $this->byPattern('m-d', '12-31');
    }

    protected function givenWeeklyDay(int $number): bool
    {
        return ((int)$this->getDate()->format('N')) === $number;
    }

    protected function givenMonthlyDay(array|int $md = [1]): bool
    {
        return $this->searchData($md, "j");
    }

    protected function givenYearDay(array|int $yd = [1]): bool
    {
        return $this->searchData($yd, "z");
    }


    protected function setDate(\DateTimeInterface $date): void
    {
        $this->date = $date;
    }


    private function searchData(mixed $values, string $format, string|array $commands = []): bool
    {
        if (\is_string($values) || \is_int($values)){
            $values = [$values];
        }
        $date = $this->getDate()->format($format);
        if (\in_array((int)$date, $values, true)) {
            return $this->execute($commands);
        }
        return false;
    }

    private function execute(string|array $commands): bool
    {
        if (\is_string($commands)) {
            return !$this->executeCommand($commands);
        }
        if (\is_array($commands)) {
            $success = true;
            foreach ($commands as $cmd) {
                if (!$this->executeCommand($cmd)) {
                    $success = false;
                }
            }
            return $success;
        }
        return false;
    }

    private function executeCommand(string $commands): int
    {
        \exec($commands, $output, $var);
        echo \implode(PHP_EOL, $output);
        return $var;
    }

    private function getDate(): \DateTimeInterface
    {
        if ($this->date === null) {
            $this->date = new \DateTime('NOW');
        }
        return $this->date;
    }
}
