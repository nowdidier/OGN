<?php

declare(strict_types=1);

/**
* This class is generated automatically. It will be changed during the update.
* 
* Этот класс сгенерирован автоматически. Он будет изменён при обновлении.
* 
* @internal
*/
final class HL2Options16_178c2dcfa54714
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
          'route' => 'blogs/new',
          'view' => null,
      ],
      'actions' =>  [],
      'full-address' => 'blogs/new',
      'controller' =>  [
          'method' => 'controller',
          'class' => 'App\Controllers\Facet\FacetController',
          'class-method' => 'blogNew',
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
