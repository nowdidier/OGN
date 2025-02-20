<?php

declare(strict_types=1);

/**
* This class is generated automatically. It will be changed during the update.
* 
* Этот класс сгенерирован автоматически. Он будет изменён при обновлении.
* 
* @internal
*/
final class HL2Options577_178c2dcfa54714
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
          'route' => 'facets/ban/{type}',
          'view' => null,
      ],
      'actions' =>  [],
      'full-address' => 'mod/admin/facets/ban/{type}',
      'middlewares' =>  [
           [
              'method' => 'middleware',
              'class' => 'App\Middlewares\DefaultMiddleware',
              'class-method' => 'index',
              'from-group' => true,
              'related-data' =>  [
                  10,
                  '=',
              ],
          ],
      ],
      'module' =>  [
          'method' => 'module',
          'name' => 'admin',
          'class' => 'Modules\Admin\Controllers\FacetsController',
          'class-method' => 'ban',
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
