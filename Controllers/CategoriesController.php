<?php

namespace App\Http\Controllers;

use App\Http\Resources\Category\CategoryAR;
use App\Http\Resources\Category\CategoryEN;
use App\Http\Resources\Category\SizeGuide;
use App\Size_guide;
use Illuminate\Http\Request;
use App\Category;
use App\Traits\ApiResponses;

class CategoriesController extends Controller
{
    use ApiResponses;


    // all AR categories with all subs

    public function index()
    {
        $categories = CategoryAR::collection(
            Category::where('parent_id','0')->where('status','1')
              ->with('parent_category')
              ->with('parent_category.sub_category')
              ->get()

        );
        return $this->querySuccess($categories);

    }


    public function size_Guide($categoryid)
    {
        $SizeGuide = SizeGuide::collection(
            Size_guide::where('status','1')
                ->where('category_id',$categoryid)
                ->get()
 );
        return $this->querySuccess($SizeGuide);

    }
    // all EN categories with all subs
    public function Category_EN()
    {
        $categories = CategoryEN::collection(
            Category::where('parent_id','0')->where('status','1')
              ->with('parent_category')
              ->with('parent_category.sub_category')
              ->get()

        );
        return $this->querySuccess($categories);

    }


    public function CategoryAR()
    {
         $categories = CategoryAR::collection(Category::where('parent_id','0')->get());
        return $this->querySuccess($categories);

    }


    public function CategoryEN()
    {
        $categories = CategoryEN::collection(Category::where('parent_id','0')->get());
        return $this->querySuccess($categories);

    }


    public function CategoryARbyparent($id)
    {
        $category = Category::all()->where('parent_id', '=', $id);
        if ($category->isEmpty()) {
            return $this->sendError("Not Found", 404);
        } else {
            $categories = CategoryAR::collection($category);
            return $this->querySuccess($categories);

        }

    }

    public function CategoryENbyparent($id)
    {

        $category = Category::all()->where('parent_id', '=', $id);
        if ($category->isEmpty()) {
            return $this->sendError("Not Found", 404);
        } else {
            $categories = CategoryEN::collection($category);
            return $this->querySuccess($categories);

        }
    }
    public function getcategoryAR($id)
    {
        $category = Category::where('id', '=', $id)->get();
        if ($category->isEmpty()) {
            return $this->sendError("Not Found", 404);
        } else {
            $categories = CategoryAR::collection($category);
            return $this->querySuccess($categories);

        }

    }

    public function getcategoryEN($id)
    {

        $category = Category::where('id', '=', $id)->get();
        if ($category->isEmpty()) {
            return $this->sendError("Not Found", 404);
        } else {
            $categories = CategoryEN::collection($category);
            return $this->querySuccess($categories);

        }
    }
    public function TopCategoryENbyParent($id)
    {

        $category = Category::all()->where('parent_id', '=', $id)
            ->where('top_category', '=', '1');
        if ($category->isEmpty()) {
            return $this->sendError("Not Found", 404);
        } else {
            $categories = CategoryEN::collection($category);
            return $this->querySuccess($categories);

        }
    }
    public function TopCategoryARbyParent($id)
    {

        $category = Category::all()->where('parent_id', '=', $id)
            ->where('top_category', '=', '1');
        if ($category->isEmpty()) {
            return $this->sendError("Not Found", 404);
        } else {
            $categories = CategoryAR::collection($category);
            return $this->querySuccess($categories);

        }
    }



}
