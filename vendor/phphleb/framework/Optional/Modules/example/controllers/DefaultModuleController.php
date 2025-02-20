<?php

namespace modules_template\module_class_name_template\Controllers;

use Hleb\Base\Module;
use Hleb\Constructor\Data\View;

class DefaultModuleController extends Module
{
    public function index(): View
    {

        return view("example");
    }
}
