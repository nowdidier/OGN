<?php

declare(strict_types=1);

/**
* This class is generated automatically. It will be changed during the update.
* 
* Этот класс сгенерирован автоматически. Он будет изменён при обновлении.
* 
* @internal
*/
final class HL2Post247_178c2dcfa54714
{
    /**
    * @internal
    */
    private static array $data =[
      'method' => 'add',
      'name' => 'post',
      'types' =>  [
          'POST',
          'OPTIONS',
      ],
      'data' =>  [
          'route' => 'team/edit/{type}/{id}',
          'view' => null,
      ],
      'actions' =>  [],
      'full-address' => 'team/edit/{type}/{id}',
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
           [
              'method' => 'middleware',
              'class' => 'App\Middlewares\LimitsMiddleware',
              'class-method' => 'index',
              'from-group' => true,
              'related-data' =>  [],
          ],
      ],
      'controller' =>  [
          'method' => 'controller',
          'class' => 'App\Controllers\Facet\TeamFacetController',
          'class-method' => 'edit',
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
