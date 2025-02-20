<?php

declare(strict_types=1);

/**
* This class is generated automatically. It will be changed during the update.
* 
* Этот класс сгенерирован автоматически. Он будет изменён при обновлении.
* 
* @internal
*/
final class HL2Options598_178c2dcfa54714
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
          'route' => 'search/web/url',
          'view' => null,
      ],
      'actions' =>  [],
      'full-address' => 'search/web/url',
      'module' =>  [
          'method' => 'module',
          'name' => 'catalog',
          'class' => 'Modules\Catalog\Controllers\AddItemController',
          'class-method' => 'searchUrl',
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
