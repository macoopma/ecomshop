<?php
class myTree
{
var $tree;
var $currentRootBranchOrLeaf;
var $mysql_desc;
var $counter;
var $tmpTree;
var $maxLevel;

function myTree() {
    $this->tree = array();
    $this->mysql_desc = null;
    $this->counter = 0;
    $this->tmpTree = array();
    $this->maxLevel = 0;
}

function dbConnect($mysqlhost,$database,$user_id,$user_pwd) {
    $this->mysql_desc=mysql_connect($mysqlhost,$user_id,$user_pwd) or die("Could not connect to Database Server");
    mysql_select_db($database,$this->mysql_desc) or die ("Could not select Database");
}

function initTree ($treeName,$lang) {
global $tmpTree;
    /* get root */
    $sq="SELECT '-1' as indexofparent,
                'N' as expanded,parent_id, categories_id, categories_noeud, categories_name_1, categories_name_2, categories_name_3, categories_visible, cat_or_subcat,
                '-1' as level,
                '1' as display 
                FROM categories 
                where tree='".$treeName."' 
                and categories_noeud='R' 
                and categories_visible='yes'";
    $result=mysql_query($sq,$this->mysql_desc);
    $i=0;
    while ($row=mysql_fetch_object($result))
    {
        $this->tmpTree[$i++] = $row;
    }
    mysql_free_result($result);

    /* get leaves and branches */
    //$categories_name = "";
    $sq="SELECT '-1' as indexofparent,
                'N' as expanded, parent_id, categories_id, categories_noeud, categories_name_1, categories_name_2, categories_name_3, categories_visible, cat_or_subcat,
                '-1' as level,
                '0' as display 
                FROM categories 
                where tree='".$treeName."' 
                and categories_noeud in ('B','L') 
                and categories_visible='yes'
                order by categories_noeud ASC, categories_order ASC, categories_name_".$lang." ASC
                ";
    // order by categories_name_$lang
    $result=mysql_query($sq,$this->mysql_desc);
    while ($row=mysql_fetch_object($result))
    {
        $this->tmpTree[$i++] = $row;
    }
    mysql_free_result($result);

    /* set the parent index of the tree to be the parent of branch or leaf */
    $j=0;
    while ($j < sizeof($tmpTree)) {
        $parent=$this->tmpTree[$j]->parent_id;
        $sizeofTmpTree2 = sizeof($this->tmpTree);
        for($k=0;$k < $sizeofTmpTree2; $k++) {
            if($this->tmpTree[$k]->categories_id == $parent) {
                $this->tmpTree[$j]->indexofparent = $k;
            }
        }
        $j++;
    }

    $this->counter=0;
    $this->maxLevel = 0;
    $this->currentRootBranchOrLeaf = 0;
    $this->insertIntoTree(0,0);
}

function insertIntoTree($RBoL,$level) {
global $lang;
    $newLevel=false;
    $this->tmpTree[$RBoL]->level = $level;
    $this->tree[$this->counter][0] = $this->tmpTree[$RBoL]->indexofparent;
    $this->tree[$this->counter][1] = $this->tmpTree[$RBoL]->expanded;
    $this->tree[$this->counter][2] = $this->tmpTree[$RBoL]->parent_id;
    $this->tree[$this->counter][3] = $this->tmpTree[$RBoL]->categories_noeud;
    $this->tree[$this->counter][4] = $this->tmpTree[$RBoL]->categories_id;
    $this->tree[$this->counter][5] = $this->tmpTree[$RBoL]->categories_name_1;
    $this->tree[$this->counter][6] = $this->tmpTree[$RBoL]->categories_visible;
    $this->tree[$this->counter][7] = $this->tmpTree[$RBoL]->level;
    $this->tree[$this->counter][8] = $this->tmpTree[$RBoL]->display;
    $this->tree[$this->counter][9] = $this->tmpTree[$RBoL]->categories_name_2;
    $this->tree[$this->counter][10] = $this->tmpTree[$RBoL]->categories_name_3;
    $this->tree[$this->counter][11] = $this->tmpTree[$RBoL]->cat_or_subcat;
    $this->counter++;
    $sizeofTmpTree = sizeof($this->tmpTree);
    for($k=1;$k < $sizeofTmpTree; $k++) {
        if($this->tmpTree[$k]->parent_id == $this->tmpTree[$RBoL]->categories_id) {
            $newLevel=true;
            $this->insertIntoTree($k,$level + 1);
        }
    }
    if($newLevel == true)
    {
        if($level > $this->maxLevel) $this->maxLevel = $level;
    }
}
};
?>
