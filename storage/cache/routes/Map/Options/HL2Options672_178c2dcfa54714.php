<?php

declare(strict_types=1);

/**
* This class is generated automatically. It will be changed during the update.
* 
* Этот класс сгенерирован автоматически. Он будет изменён при обновлении.
* 
* @internal
*/
final class HL2Options672_178c2dcfa54714
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
          'route' => 'search',
          'view' => null,
      ],
      'actions' =>  [],
      'full-address' => 'search',
      'module' =>  [
          'method' => 'module',
          'name' => 'search',
          'class' => 'Modules\Search\Controllers\SearchController',
          'class-method' => 'index',
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
