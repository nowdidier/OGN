<?php

declare(strict_types=1);

/**
* This class is generated automatically. It will be changed during the update.
* 
* Этот класс сгенерирован автоматически. Он будет изменён при обновлении.
* 
* @internal
*/
final class HL2Options427_178c2dcfa54714
{
    /**
    * @internal
    */
    private static array $data =[
      'method' => 'add',
      'name' => 'get',
      'types' =>  [
          'GET',
          'OPTIONS',
      ],
      'data' =>  [
          'route' => 'rss-feed/topic/{slug}',
          'view' => null,
      ],
      'actions' =>  [],
      'full-address' => 'rss-feed/topic/{slug}',
      'controller' =>  [
          'method' => 'controller',
          'class' => 'App\Controllers\RssController',
          'class-method' => 'rssFeed',
      ],
  ];


    /**
    * @internal
    */
    public static function getData(): array
    {
        return self::$data;
    }
}
