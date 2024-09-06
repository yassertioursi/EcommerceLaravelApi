<?php

namespace App\Http\Controllers;
use Exception;
use Illuminate\Http\Request;
use App\Models\Brands;


class BrandsController extends Controller {
   public function index() { 
      $brands = Brands::all(); 
      return response()->json($brands, 200); 
   }

   public function show($id) {
      $brand = Brands::find($id);  
      if ($brand) { 
         return response()->json($brand, 200);  
      } else {
         return response()->json('Brand not found', 404); 
      }
   }

   public function store(Request $request) { 
      try { 
          $validated = $request->validate([ 
              'name' => 'required|unique:brands,name'
          ]);  
          $brand = new Brands(); 
          $brand->name = $request->name;  
          $brand->save();

          return response()->json('Brand added', 201); 
      
      } catch (Exception $e) { 
          return response()->json(['error' => $e->getMessage()], 500); 
      }
  }

  
  public function update_brand($id, Request $request) 
  { 
      try 
      { 
          
          $validated = $request->validate([ 
              'name' => 'required'
          ]); 

          $brand = Brands::find($id);

          if (!$brand) {
              return response()->json(['error' => 'Brand not found'], 404);
          }

         
          $brand->name = $request->name;
          $brand->save();

          return response()->json('Brand updated', 200);
      } 
      catch (ValidationException $e) 
      { 
          return response()->json($e->errors(), 422);  
      } 
      catch (Exception $e) 
      { 
          return response()->json(['error' => $e->getMessage()], 500);     
      } 
  }


   public function delete_brand($id) {
      $brand = Brands::finddybx($id);  
      if ($brand) { 
         $brand->delete();
         return response()->json("Brand deleted");  
      } else {
         return response()->json('Brand not found', 404); 
      }
   }
}
