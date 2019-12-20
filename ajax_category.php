<?php
require 'catalog.php';
class Get_cat extends Db
{
    public function __construct()
    {
        $this->db = new Db;
    }


public function count_cats($id){
    $params = [
        'id' =>   $id,
    ];
        return $this->db->column("SELECT COUNT(*) FROM categories WHERE parent=:id", $params);
}
    public  function get_catById()
    {
        $id = $_POST['arg'];
        $params = [
          'id' =>    $id,
        ];
        if ($id AND $this->count_cats($id)>=1) {
            $query = $this->db->query("SELECT * FROM categories WHERE parent=:id AND parent!=0", $params);

            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {

                $id = $row['id'];
                $username = $row['title'];

                $parent = $row['parent'];
                $return_arr[] = array(
                    "id" => $id,
                    "title" => $username,
                   "parent" => $parent
                );
                //  $products[] = $row;
            }
            return json_encode($return_arr);
        } else {
            $res = array('answer' => 'no', 'info' => 'нет категорий');
            return json_encode($res);

        }
    }
}
$cats= new Get_cat;

 echo $gg = $cats->get_catById();



