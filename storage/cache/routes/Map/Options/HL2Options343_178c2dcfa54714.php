<?php

declare(strict_types=1);

/**
* This class is generated automatically. It will be changed during the update.
* 
* Этот класс сгенерирован автоматически. Он будет изменён при обновлении.
* 
* @internal
*/
final class HL2Options343_178c2dcfa54714
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
          'route' => 'activatingform/editmessage',
          'view' => null,
      ],
      'actions' =>  [],
      'full-address' => 'activatingform/editmessage',
      'controller' =>  [
          'method' => 'controller',
          'class' => 'App\Controllers\MessagesController',
          'class-method' => 'addForma',
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
