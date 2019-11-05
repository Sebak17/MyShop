<?php

namespace App\Helpers;

class CategoriesHelper
{

    public static function getAllSubCategories($categories, $id)
    {
        $list = array();

        foreach ($categories as $cat) {

            if ($cat->overcategory == $id) {
                array_push($list, $cat);

                $do = true;
                CategoriesHelper::getChildren($categories, $cat->id, $list, $do);
            }

        }

        return $list;
    }

    private static function getChildren($categories, $overID, &$list, &$do)
    {

        if (!$do) {
            return;
        }

        $res = array();

        foreach ($categories as $cat) {

            if ($cat->overcategory == $overID) {
                array_push($res, $cat);

                CategoriesHelper::getChildren($categories, $cat->id, $list, $do);
            }

        }

        if (empty($res)) {
            $do = false;
        } else {
            $list = array_merge($list, $res);
        }

    }

}
