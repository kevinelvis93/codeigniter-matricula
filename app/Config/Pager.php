<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Pager extends BaseConfig
{
    public $templates = [
        'default_full'   => 'App\Views\Pagers\bootstrap_pagination',
        'default_simple' => 'CodeIgniter\Pager\Views\default_simple',
        'default_head'   => 'CodeIgniter\Pager\Views\default_head',
    ];

    public $perPage = 10; // Número de registros por página
}

