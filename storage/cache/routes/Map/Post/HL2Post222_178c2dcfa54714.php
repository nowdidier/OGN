<?php

declare(strict_types=1);

/**
* This class is generated automatically. It will be changed during the update.
* 
* Этот класс сгенерирован автоматически. Он будет изменён при обновлении.
* 
* @internal
*/
final class HL2Post222_178c2dcfa54714
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
          'route' => 'device',
          'view' => null,
      ],
      'actions' =>  [],
      'full-address' => 'device',
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
      'controller' =>  [
          'method' => 'controller',
          'class' => 'App\Controllers\DeviceController',
          'class-method' => 'set',
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
