<?php

declare(strict_types=1);

/**
* This class is generated automatically. It will be changed during the update.
* 
* Этот класс сгенерирован автоматически. Он будет изменён при обновлении.
* 
* @internal
*/
final class HL2Get335_178c2dcfa54714
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
          'route' => 'login',
          'view' => null,
      ],
      'actions' =>  [],
      'full-address' => 'login',
      'middlewares' =>  [
           [
              'method' => 'middleware',
              'class' => 'App\Middlewares\DefaultMiddleware',
              'class-method' => 'index',
              'from-group' => true,
              'related-data' =>  [
                  0,
                  '=',
              ],
          ],
      ],
      'controller' =>  [
          'method' => 'controller',
          'class' => 'App\Controllers\Auth\LoginController',
          'class-method' => 'showLoginForm',
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
