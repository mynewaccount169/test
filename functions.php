<?php
require 'Db.php';
/**
* Распечатка массива
**/

class Catalog extends Db
{
    public function __construct()
    {
        $this->db = new Db;
    }

    function print_arr($array)
    {
        echo "<pre>" . print_r($array, true) . "</pre>";
    }

  public  function get_cat()
    {

        $query = $this->db->query("SELECT * FROM categories");

        $arr_cat = array();
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $arr_cat[$row['id']] = $row;
        }
        return $arr_cat;
    }





    function get_catById($ids)
    {
        $params = [
            'ids' =>$ids,
        ];

        if ($ids) {
            $query = $this->db->query("SELECT * FROM categories WHERE parent=:ids AND parent!=0",$params);

            $arr_cat = array();
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $arr_cat[$row['id']] = $row;
            }
            return $arr_cat;
        } else {
            echo '';

        }
    }


  public  function map_tree($dataset)
    {
        $tree = array();

        foreach ($dataset as $id => &$node) {
            if (!$node['parent']) {
                $tree[$id] = &$node;
            } else {
                $dataset[$node['parent']]['childs'][$id] = &$node;
            }
        }

        return $tree;
    }

  public  function categories_to_string($data)
    {
        foreach ($data as $item) {
            $string .= $this->categories_to_template($item);
        }
        return $string;
    }

 public   function categories_to_template($category)
    {
        ob_start();
        include 'category_template.php';
        return ob_get_clean();
    }

  
    public  function cats_id($array, $id)
    {
        if (!$id) return false;

        foreach ($array as $item) {
            if ($item['parent'] == $id) {
                $data .= $item['id'] . ",";
                $data .= $this->cats_id($array, $item['id']);
            }
        }
        return $data;
    }
}
