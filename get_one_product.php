<?php

require 'catalog.php';
class get_one_product extends Db
{
    public function __construct()
    {
        $this->db = new Db;
    }
    public function get_products()
    {

        $params = [
            'fields' =>$_POST['val'],
        ];

            $query = $this->db->query("SELECT * FROM products WHERE id = :fields",$params);
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $return_arr["title"] = $row["title"];
                $return_arr["parent"] = $row["parent"];
                $return_arr["id"] = $row["id"];
                $return_arr["price"] = $row["price"];
                $return_arr["date"] = date('d-m-Y H:i:s', $row["date"]);


                /* $id = $row['id'];
                 $username = $row['title'];
                 $name = $row['price'];
                 $parent = $row['parent'];
                 $return_arr[] = array(
                     "id" => $id,
                     "title" => $username,
                     "price" => $name,
                     "parent" => $parent
                 );
 */
            }

return json_encode($return_arr);
    }

}
$prod = new get_one_product;

echo $prod->get_products();
