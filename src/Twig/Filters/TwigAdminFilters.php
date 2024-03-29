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
            new TwigFilter('json_encode_slashes', [$this, 'json_encode_slashes']),
        ];
    }
    // function json decodes
    public function json_decode($json){
        return json_decode($json,true);
    }

    // function json_encode_slashes
    public function json_encode_slashes($array){
        return json_encode($array, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }
    
}
