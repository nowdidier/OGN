<?php

declare(strict_types=1);

/**
* This class is generated automatically. It will be changed during the update.
* 
* Этот класс сгенерирован автоматически. Он будет изменён при обновлении.
* 
* @internal
*/
final class HL2Get667_178c2dcfa54714
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
          'route' => 'search/opensearch',
          'view' => null,
      ],
      'actions' =>  [],
      'full-address' => 'search/opensearch',
      'module' =>  [
          'method' => 'module',
          'name' => 'search',
          'class' => 'Modules\Search\Controllers\SearchController',
          'class-method' => 'openSearch',
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
