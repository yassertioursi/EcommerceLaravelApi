<?php

namespace App\Http\Controllers;
use Exception;
use Illuminate\Http\Request;
use App\Models\Categories;


class CategoriesController extends Controller {
    public function index() {
        $categories = Categories::all(); 
        $categories->each(function($category) {
            $category->image = url('assets/uploads/category/' . $category->image);
        });
        return response()->json($categories, 200); 
    }
    

   public function show($id) {
      $category = Categories::find($id);  
      if ($category) { 
        $category->image = url('assets/uploads/category/' . $category->image);
         return response()->json($category, 200);  
      } else {
         return response()->json('category not found', 404); 
      }
   }

   public function store(Request $request) { 
    try { 
        $validated = $request->validate([ 
            'name' => 'required|unique:categories,name', 
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);  

        $category = new Categories(); 
        $category->name = $request->name;

        if($request->hasFile('image')) {  
            $file = $request->file('image'); 
            $ext = $file->getClientOriginalExtension(); 
            $filename = time().'.'.$ext; 

            $file->move(public_path('assets/uploads/category'), $filename);
            $category->image = $filename; 
        }

        $category->save();

        return response()->json('category added', 201); 
    } catch (Exception $e) { 
        return response()->json(['error' => $e->getMessage()], 500); 
    }
}


  
  public function update_category($id, Request $request) 
  { 
      try 
      { 
          
          $validated = $request->validate([ 
              'name' => 'required|unique:categories,name' , 
              'image'=> 'required'
          ]); 

          $category = Categories::find($id);


          if ( !$category){ 
            return response()->json('category not found', 404);

          }
          else{  
              if($request->hasFile('image')) {  
            $path= 'assets/upload/category/'. $category->image  ;
            if(File::exists($path)){
                File::delete($path)  ;  
                 
            }
            $file=$request->file('image') ; 
            $ext=$file->getClientOriginalExtension() ; 
            $filename=time().'.'.$ext; 
            
            $file->move(public_path('assets/uploads/category'), $filename);
        
           
            $category->image=$filename ; 
            
       

          }
          $category->name=$request->name ; 
          $category->update() ; 

        
          return response()->json('category updated', 200);
      } 
    }
      catch (Exception $e) 
      { 
          return response()->json(['error' => $e->getMessage()], 500);     
      } 
             

        
       
  }


   public function delete_category($id) {
      $category = Categories::find($id);  
      if ($category) { 
         $category->delete();
         return response()->json("category deleted");  
      } else {
         return response()->json('category not found', 404); 
      }
   }
}
