<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;
use DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function categoryTree($permissionRecord, $parent_id = NULL)
    {
        $html = '<ul class="main-navigation">';
        foreach($permissionRecord as $row) {
            if ($row->category_id == $parent_id) {
                $aMenu = (empty($row->name)) ? '':'<a href="#">'.$row->name.'</a>';
                $html .= '<li>'.$aMenu;
                $html .= $this->categorySubTree($permissionRecord, $row->id, $html);
                $html .= '</li>';
            }
        }
        $html .= '</ul>';
        return $html;
    }

    public function categorySubTree($permissionRecord, $parent_id = NULL)
    {
        $html = '<ul>';
        foreach($permissionRecord as $row) {
            if ($row->category_id == $parent_id) {
                $aMenu = (empty($row->name)) ? '':'<a href="#">'.$row->name.'</a>';
                $html .= '<li>'.$aMenu;
                $html .= $this->categorySubTree($permissionRecord, $row->id, $html);
                $html .= '</li>';
            }
        }
        $html .= '</ul>';
        return $html;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {    
        $categoryLevelQuery = Category::getCategoryLevel();
        $categoriesResult =  DB::select($categoryLevelQuery);
        $categoriesTree = $this->categoryTree($categoriesResult,NULL);
        return view('home',compact('categoriesTree'));
    }
  
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function adminHome()
    {
        return view('adminHome');
    }
}



