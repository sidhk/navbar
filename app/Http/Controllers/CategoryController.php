<?php

namespace App\Http\Controllers;

use App\Category;
use Illuminate\Http\Request;
use DB;

class CategoryController extends Controller
{


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
      //  $this->middleware('auth');

        $this->middleware('auth')->only('is_admin');;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
       $categoryLevel = Category::getCategoryLevel();
       $page = $request->get('page', 1);
       $perPage = 10;
       $offset = ($page * $perPage) - $perPage;
       $limit =" LIMIT $offset,".$perPage;
       $finalQuery =  $categoryLevel.$limit;
        $categoriesData =  DB::select($finalQuery);

        $foundRowsQuery = " SELECT FOUND_ROWS() as total_count ";
        $categoriesCount =  DB::select($foundRowsQuery);

        $totalCount = $categoriesCount[0]->total_count;

        $categories = $this->arrayCustomPaginator($page,$perPage, $categoriesData, $request,$totalCount);
        return view('category.index',compact('categories'))
            ->with('index',$offset );
    }

    public function arrayCustomPaginator($page,$perPageLimit, $array, $request, $totalCount)
    {
        $perPage = $perPageLimit;
        $offset = ($page * $perPage) - $perPage;
        $sliceData = $array;
        return new \Illuminate\Pagination\LengthAwarePaginator($sliceData, $totalCount, $perPage, $page,
        ['path' => $request->url(), 'query' => $request->query()]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categoryLevel = Category::getCategoryLevel();
        $categories =  DB::select($categoryLevel);

        return view('category.create',compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:category,name|max:255',
            'category_id'=>'nullable|exists:category,id',
            'sort_by' => 'required|numeric',
            'status' => 'required|in:enable,disable',
        ]);

        Category::create($request->all());
   
        return redirect()->route('categories.index')
                        ->with('success','Category created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\category  $categories
     * @return \Illuminate\Http\Response
     */
    public function edit(category $category)
    {
        $categoryLevel = Category::getCategoryLevel();
        $categories =  DB::select($categoryLevel);
        return view('category.edit',compact('categories','category'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, category $category)
    {
        $request->validate([
            'name' => 'required|unique:category,name,'.$category->id.'|max:255',
            'category_id'=>'nullable|exists:category,id',
            'sort_by' => 'required|numeric',
            'status' => 'required|in:enable,disable',
        ]);
  
        $category->update($request->all());
  
        return redirect()->route('categories.index')
                        ->with('success','Category updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(category $category)
    {
        $category->delete();  
        return redirect()->route('categories.index')
                        ->with('success','Category deleted successfully');
    }
}
