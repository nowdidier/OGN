<?php

declare(strict_types=1);

/**
* This class is generated automatically. It will be changed during the update.
* 
* Этот класс сгенерирован автоматически. Он будет изменён при обновлении.
* 
* @internal
*/
final class HL2Get372_178c2dcfa54714
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
          'route' => '@{login}/comments',
          'view' => null,
      ],
      'actions' =>  [],
      'full-address' => '@{login}/comments',
      'controller' =>  [
          'method' => 'controller',
          'class' => 'App\Controllers\User\ProfileController',
          'class-method' => 'comments',
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
