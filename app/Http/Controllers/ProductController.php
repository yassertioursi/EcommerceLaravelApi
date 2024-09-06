<?php

namespace App\Http\Controllers;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;


class ProductController extends Controller
{
    public function index() { 
        $products = Product::all(); 
        $products->each(function($product) {
            $product->image = url('assets/uploads/product/' . $product->image);
        });
        return response()->json($products, 200); 
     }
     public function show($id) {
        $product = Product::find($id);  
        if ($product) { 
            $product->image = url('assets/uploads/category/' . $product->image);
           return response()->json($product, 200);  
        } else {
           return response()->json('product not found', 404); 
        }
     }


     
     public function store(Request $request) { 
     
        try { 
            $validated = $request->validate([ 
                'name' => 'required',
                'price' => 'required|numeric',
                'category_id' => 'required|numeric',
                'brand_id' => 'required|numeric',
                'discount' => 'required|numeric',
                'amount'=>'required|numeric' , 
                'image'=>'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
                
            ]);  
            $product = new Product(); 
            $product->name = $request->name;
            $product->price = $request->price;
            $product->brand_id = $request->brand_id;
            $product->category_id = $request->category_id;
            $product->discount = $request->discount;
            $product->amount = $request->amount;
            
            if($request->hasFile('image')) {  
           
                
                $file=$request->file('image') ; 
                $ext=$file->getClientOriginalExtension() ; 
                $filename=time().'.'.$ext; 
                
                $file->move(public_path('assets/uploads/product'), $filename);
            
               
                $product->image=$filename ; 
                
           
    
              }
           
            $product->save();
  
            return response()->json('product added', 201); 
        
        } catch (Exception $e) { 
            return response()->json(['error' => $e->getMessage()], 500); 
        }
    }

    public function update_product($id, Request $request) 
    { 
        try 
        { 
            
            $validated = $request->validate([ 
                'name' => 'required',
                'price' => 'required|numeric',
                'category_id' => 'required|numeric',
                'brand_id' => 'required|numeric',
                'discount' => 'required|numeric',
                'amount'=>'required|numeric' , 
                'image'=>'required'
                
            ]);  
            $product =Product::find($id);
             if(!$product){  
                return response()->json('product not found', 404);
             }
                else{  
                     
                
                $product->name = $request->name;
                $product->price = $request->price;
                $product->brand_id = $request->brand_id;
                $product->category_id = $request->category_id;
                $product->discount = $request->discount;
                $product->amount = $request->amount;
                if($request->hasFile('image')) {  
                  $path= 'assets/upload/product/'. $product->image  ;
                  if(File::exists($path)){
                      File::delete($path)  ;  
                       
                  }
                  $file=$request->file('image') ; 
                  $ext=$file->getClientOriginalExtension() ; 
                  $filename=time().'.'.$ext; 
                
                $file->move(public_path('assets/uploads/product'), $filename);      
                  $product->image=$filename ;        
                }
                $product->name=$request->name ; 
                $product->update() ; 
      
              
                return response()->json('product updated', 200);
            
            }
        } 
        catch (Exception $e) 
        { 
            return response()->json(['error' => $e->getMessage()], 500);     
        } 
    }
    public function delete_product($id) {
        $product = Product::find($id);  
        if ($product) { 
           $product->delete();
           return response()->json("product deleted");  
        } else {
           return response()->json('product not found', 404); 
        }
     }
    
     


}
