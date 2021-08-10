<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
     /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'category';

    protected $fillable = [
        'name', 'category_id','sort_by','status'
    ];

    public static function getCategoryLevel()
    {
        $finalQuery ="WITH RECURSIVE category_path (id,category_id, name, path, sort_by) AS
                        (
                        SELECT id,category_id, name, name as path,sort_by
                            FROM category
                            WHERE category_id IS NULL
                        UNION ALL
                        SELECT c.id,c.category_id, c.name, CONCAT(cp.path, ' > ', c.name),c.sort_by
                            FROM category_path AS cp JOIN category AS c
                            ON cp.id = c.category_id
                        )
                        SELECT SQL_CALC_FOUND_ROWS id,category_id, name, path, sort_by FROM category_path
                     ORDER BY path";
        return $finalQuery;
    }

    public function childs() {
        return $this->hasMany('App\Category','id','category_id') ;
    }
}


