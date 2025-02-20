<?php

declare(strict_types=1);

/**
* This class is generated automatically. It will be changed during the update.
* 
* Этот класс сгенерирован автоматически. Он будет изменён при обновлении.
* 
* @internal
*/
final class HL2Get376_178c2dcfa54714
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
          'route' => 'topic/{slug}/recommend',
          'view' => null,
      ],
      'actions' =>  [],
      'full-address' => 'topic/{slug}/recommend',
      'controller' =>  [
          'method' => 'controller',
          'class' => 'App\Controllers\Facet\TopicFacetController',
          'class-method' => 'recommend',
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
