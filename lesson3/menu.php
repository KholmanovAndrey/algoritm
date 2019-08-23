<?php

$connect = mysqli_connect(
    "localhost",
    "root",
    "",
    "alg");

$query = "SELECT * FROM category t1
          INNER JOIN category_links t2
          ON t1.id = t2.child_id";

$result = mysqli_query($connect, $query);

$cats = [];
while ($cat = mysqli_fetch_assoc($result)) {
    $cats[$cat["level"]][$cat["parent_id"]][$cat["id"]] = $cat;
}

echo buildMenu($cats);

function buildMenu(array $cats, int $level = 0, int $parent_id = 1)
{
    if (isset($cats[$level][$parent_id])){
        $menu = '<ul>';
        foreach ($cats[$level][$parent_id] as $cat) {
            $menu .= '<li>' . $level . ' ' . $cat['name'];
            $menu .= buildMenu($cats,$cat['level'] + 1, $cat['id']);
            $menu .= '</li>';
        }
        $menu .= '</ul>';
        return $menu;
    }
}