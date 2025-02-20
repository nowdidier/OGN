<?php

declare(strict_types=1);

/**
* This class is generated automatically. It will be changed during the update.
* 
* Этот класс сгенерирован автоматически. Он будет изменён при обновлении.
* 
* @internal
*/
final class HL2Options654_178c2dcfa54714
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
          'route' => 'web/bookmarks',
          'view' => null,
      ],
      'actions' =>  [],
      'full-address' => 'web/bookmarks',
      'middlewares' =>  [
           [
              'method' => 'middleware',
              'class' => 'App\Middlewares\DefaultMiddleware',
              'class-method' => 'index',
              'from-group' => true,
              'related-data' =>  [
                  1,
                  '>=',
              ],
          ],
      ],
      'module' =>  [
          'method' => 'module',
          'name' => 'catalog',
          'class' => 'Modules\Catalog\Controllers\UserAreaController',
          'class-method' => 'bookmarks',
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
