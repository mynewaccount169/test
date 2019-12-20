<?php
require 'catalog.php';
class Productsss extends Db
{
    public function __construct()
    {
        $this->db = new Db;
    }
    public function get_products()
    {
        $cat = new Catalog;
        $id = $_POST['arg'];
        $categories = $cat->get_cat();
        $ids = $cat->cats_id($categories, $id);
        $ids = !$ids ? $id : rtrim($ids, ",");


        $arr = [
            'default'=> 'title',
            'sort-new'=> 'date',
            'sort-asc'=> 'price',
            'sort-Alfavit'=> 'title',
        ];
        $arr2= [
            'default'=> 'ASC',
            'sort-new'=> 'DESC',
            'sort-asc'=> 'ASC',
            'sort-Alfavit'=> 'ASC',
        ];
        $param = $_POST['sort_by'];
        if(!$param){
            $param = 'default';
        }

        if ($ids) {
            $query = $this->db->query("SELECT * FROM products WHERE parent IN($ids) ORDER BY ".$arr[$param]." ".$arr2[$param]." LIMIT 20");
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $id = $row['id'];
                $username = $row['title'];
                $name = $row['price'];
                $parent = $row['parent'];
                $date = $row['date'];
                $return_arr[] = array(
                    "id" => $id,
                    "title" => $username,
                    "price" => $name,
                    "parent" => $parent,
                    "date" =>  date('d-m-Y H:i', $date)
                );

            }
                if($this->count_goods($ids) >=1) {
                    return json_encode($return_arr);
                }else{
                    $res = array('answer' => 'no');
                    return json_encode($res);
                }
        } else {
           $query = $this->db->query("SELECT * FROM products ORDER BY ".$arr[$param]." ASC");
            $res = array('answer' => 'no');

            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $id = $row['id'];
                $username = $row['title'];
                $name = $row['price'];
                $parent = $row['parent'];
                $date = $row['date'];
                $return_arr[] = array(
                    "id" => $id,
                    "title" => $username,
                    "price" => $name,
                    "parent" => $parent,
                     "date" =>  date('d-m-Y H:i', $date)
                );

            }
            return json_encode($return_arr);
        }

    }
   public  function count_goods($ids)
    {
        $params = [
            'ids' =>$ids,
        ];
        if (!$ids) {
            $query = $this->db->column("SELECT COUNT(*) FROM products");
        } else {
            $query = $this->db->column("SELECT COUNT(*) FROM products WHERE parent IN($ids)",$params);
        }

        return $query;
    }
}
$prod = new Productsss;

    echo $prod->get_products();
