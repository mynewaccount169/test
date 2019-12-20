<?php
require 'catalog.php';
class Get_bredcrumps extends Db
{
    public function __construct()
    {
        $this->db = new Db;
    }
    public   function breadcrumbs($array, $id)
    {
        if (!$id) return false;
        $count = count($array);
        $breadcrumbs_array = array();
        for ($i = 0; $i < $count; $i++) {
            if ($array[$id]) {
           $breadcrumbs_array[$array[$id]['id']] = $array[$id]['title'];
                $id = $array[$id]['parent'];
            } else break;
        }
        return array_reverse($breadcrumbs_array, true);
    }
}
$bredcrums = new Get_bredcrumps;
$id = $_POST['arg'];
$breadcrumbs_array = $bredcrums->breadcrumbs($categories, $id);
if($breadcrumbs_array){
    $breadcrumbs = "<a class='get_url' href='" .PATH. "'>Главная</a> / ";
    foreach($breadcrumbs_array as $id => $title){
        $breadcrumbs .= "<a class='get_url' href='" .PATH. "?category={$id}'>{$title}</a> / ";
    }
    if( !isset($get_one_product) ){
        $breadcrumbs = rtrim($breadcrumbs, " / ");
        $breadcrumbs = preg_replace("#(.+)?<a.+>(.+)</a>$#", "$1$2", $breadcrumbs);
    }else{
        $breadcrumbs .= $get_one_product['title'];
    }
}else{
    $breadcrumbs = "<a class='get_url' href='" .PATH. "'>Главная</a> / Каталог";
}
echo $breadcrumbs;
