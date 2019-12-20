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

    /**
     * Получение массива категорий
     **/


  /*  public function get_products()
    {

        $arr = [
            'sort-new'=> 'id',
            'sort-asc'=> 'price',
            'sort-Alfavit'=> 'title',
        ];
        $param = $_POST['strGET'];
        $params = [
            'fields_sort' =>$arr[$param],
        ];

        $q = $this->db->query("SELECT * FROM products WHERE parent = 693 ORDER BY ".$arr[$param]." ASC");
        while ($row = $q->fetch(PDO::FETCH_ASSOC)) {

            $id = $row['id'];
            $username = $row['title'];
            $name = $row['price'];
            $return_arr[] = array(
                "id" => $id,
                "title" => $username,
                "price" => $name);
        }
        return json_encode($return_arr);

    }

    */

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


    /**
     * Построение дерева
     **/
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

    /**
     * Дерево в строку HTML
     **/
  public  function categories_to_string($data)
    {
        foreach ($data as $item) {
            $string .= $this->categories_to_template($item);
        }
        return $string;
    }

    /**
     * Шаблон вывода категорий
     **/
 public   function categories_to_template($category)
    {
        ob_start();
        include 'category_template.php';
        return ob_get_clean();
    }

    /**
     * Хлебные крошки
     **/
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

    /**
     * Получение ID дочерних категорий
     **/
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

    /**
     * Получение товаров
     **/
    public  function get_products($ids, $start_pos, $perpage)
    {
        $params = [
            'ids' =>$ids,
        ];
       if ($ids) {
           $query  = $this->db->query("SELECT * FROM products WHERE parent IN($ids) ORDER BY title LIMIT $start_pos, $perpage",$params);
        } else {
            $query = $this->db->query("SELECT * FROM products ORDER BY title LIMIT $start_pos, $perpage");
        }


        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $id = $row['id'];
            $username = $row['title'];
            $name = $row['price'];
            $return_arr[] = array(
                "id" => $id,
                "title" => $username,
                "price" => $name);


            //  $products[] = $row;
        }
        return json_encode($return_arr);
    }
    /**
     * Получение отдельного товара
     **/
    public  function get_one_product($product_id)
    {
        $params = [
            'product_id' =>$product_id,
        ];

          $query = $this->db->query("SELECT * FROM products WHERE id = :product_id",$params);
        return   $query->fetch(\PDO::FETCH_ASSOC);

    }

    /**
     * Кол-во товаров
     **/
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

    /**
     * Постраничная навигация
     **/
    public  function pagination($page, $count_pages)
    {
        // << < 3 4 5 6 7 > >>
        // $back - ссылка НАЗАД
        // $forward - ссылка ВПЕРЕД
        // $startpage - ссылка В НАЧАЛО
        // $endpage - ссылка В КОНЕЦ
        // $page2left - вторая страница слева
        // $page1left - первая страница слева
        // $page2right - вторая страница справа
        // $page1right - первая страница справа

        $uri = "?";
        // если есть параметры в запросе
        if ($_SERVER['QUERY_STRING']) {
            foreach ($_GET as $key => $value) {
                if ($key != 'page') $uri .= "{$key}=$value&amp;";
            }
        }

        if ($page > 1) {
            $back = "<a class='nav-link' href='{$uri}page=" . ($page - 1) . "'>&lt;</a>";
        }
        if ($page < $count_pages) {
            $forward = "<a class='nav-link' href='{$uri}page=" . ($page + 1) . "'>&gt;</a>";
        }
        if ($page > 3) {
            $startpage = "<a class='nav-link' href='{$uri}page=1'>&laquo;</a>";
        }
        if ($page < ($count_pages - 2)) {
            $endpage = "<a class='nav-link' href='{$uri}page={$count_pages}'>&raquo;</a>";
        }
        if ($page - 2 > 0) {
            $page2left = "<a class='nav-link' href='{$uri}page=" . ($page - 2) . "'>" . ($page - 2) . "</a>";
        }
        if ($page - 1 > 0) {
            $page1left = "<a class='nav-link' href='{$uri}page=" . ($page - 1) . "'>" . ($page - 1) . "</a>";
        }
        if ($page + 1 <= $count_pages) {
            $page1right = "<a class='nav-link' href='{$uri}page=" . ($page + 1) . "'>" . ($page + 1) . "</a>";
        }
        if ($page + 2 <= $count_pages) {
            $page2right = "<a class='nav-link' href='{$uri}page=" . ($page + 2) . "'>" . ($page + 2) . "</a>";
        }

        return $startpage . $back . $page2left . $page1left . '<a class="nav-active">' . $page . '</a>' . $page1right . $page2right . $forward . $endpage;
    }

}