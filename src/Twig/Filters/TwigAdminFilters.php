<?php
namespace App\Twig\Filters;

use Twig\TwigFilter;
use Twig\Environment;
use Twig\Extension\AbstractExtension;


class TwigAdminFilters extends AbstractExtension
{
    public function __construct() {
        
    }
    public function getFilters()
    {
        return [
            new TwigFilter('json_decode', [$this, 'json_decode']),
        ];
    }
    // function json decodes
    public function json_decode($json){
        return json_decode($json,true);
    }
}
