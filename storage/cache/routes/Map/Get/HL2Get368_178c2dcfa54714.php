<?php

declare(strict_types=1);

/**
* This class is generated automatically. It will be changed during the update.
* 
* Этот класс сгенерирован автоматически. Он будет изменён при обновлении.
* 
* @internal
*/
final class HL2Get368_178c2dcfa54714
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
          'route' => '@{login}/posts',
          'view' => null,
      ],
      'actions' =>  [],
      'full-address' => '@{login}/posts',
      'controller' =>  [
          'method' => 'controller',
          'class' => 'App\Controllers\User\ProfileController',
          'class-method' => 'posts',
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
