<?php
class Tree {
 
    private $db = null;
    private $category_arr = array();
 
    public function __construct() {
        $this->db = new PDO("mysql:dbname=vk;host=localhost", "root", "");
        $this->category_arr = $this->getCategory();
    }

    private function getCategory() {
        $query = $this->db->prepare("SELECT * FROM cat"); 
        $query->execute(); 
        $result = $query->fetchAll(PDO::FETCH_OBJ);
       
        $return = array();
        foreach ($result as $value) { 
            $return[$value->parents_id][] = $value;
        }
        return $return;
    }
 
    public function outputTree($parents_id, $qwe) {
        if (isset($this->category_arr[$parents_id])) { 
            foreach ($this->category_arr[$parents_id] as $value) { 
               
                echo "<div style='margin-left:" . ($qwe * 25) . "px;'>" . $value->categories_id . "</div>";
                $qwe++; 
                $this->outputTree($value->categories_id, $qwe);
                $qwe--; 
            }
        }
    }
 
}
 
$tree = new Tree();
$tree->outputTree(0, 0); 
 
?>