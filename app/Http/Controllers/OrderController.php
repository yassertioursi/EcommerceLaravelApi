<?php

namespace App\Http\Controllers;
use Exception;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItems;
use App\Models\Product;
use App\Models\Location;
use Illuminate\Support\Facades\Auth;


class OrderController extends Controller {
   public function index() { 
     
      $orders = Order:: with('user')->get();

     if($orders ){
        
        return response()->json($orders, 200);
        
       
         
         
     }
      else { 
        response()->json('no orders');
    
      }

     
   }

   public function show($id) {
      $order = Order::find($id);  
      if ($order) { 
         return response()->json($order, 200);  
      } else {
         return response()->json('order not found', 404); 
      }
   }

   public function store(Request $request) { 
    
    $location =Location::where('user_id', Auth::id())->first() ;

    
      try { 
          $validated = $request->validate([ 
              'order_items' => 'required', 
              'total_price'=>'required' , 
              'quantity'=>'required' , 
              'date_of_delivery'=>'required'
 
          ]);  
          $order = new Order(); 
          $order->user_id=Auth::id() ; 
          $order->location_id=$location->id  ; 
          $order->total_price=$request->total_price ; 
          $order->date_of_delivery=$request->date_of_delivery ; 
          $order->save();

          foreach ($request->order_items as $order_items) {
        
            $items=new OrderItems();
            $items->order_id=$order->id ; 
            $items->price=$order_items['price'] ;
            $items->product_id=$order_items['product_id'] ; 
            $items->quantity=$order_items['quantity'] ;
            $items->save() ;  
            $product=Product::where('id',$order_items['product_id'])->first();
            $product->amount=$order_items['quantity'] ; 
            $product->save(); 

          }

          return response()->json('order added', 201); 
      
      } catch (Exception $e) { 
          return response()->json(['error' => $e->getMessage()], 500); 
      }
  }

  


  public function get_order_items($id)  { 
$order_items=OrderItems::where('order_id',$id)->get();

if($order_items){
   
   return response()->json($order_items); 

}
else{ 
   return response()->json('no items found'); 
     
}

     
  }

  public function get_user_orders($id) {
   $orders = Order::where('user_id', $id)
                   ->with('items')
                   ->get();
                  

   if ($orders->isEmpty()) {
       return response()->json('No orders found for this user', 200);
   }

   foreach ($orders as $order) {
       foreach ($order->items as $order_item) {
           $product = Product::where('id', $order_item->product_id)->pluck('name')->first();
           $order_item->product_name = $product;
       }
   }

   return response()->json($orders, 200);
}

  public function  change_order_status($id , Request $request){ 
$order= Order::find($id) ; 

if($order){
    $order->update(['status'=>$request->status]);
    return response()->json('Status changed successfully'); 
     

}
else { 
    response()->json('order not found  '); 
     
}
     
     
  }

   public function delete_order($id) {
      $order = Order::find($id);  
      if ($order) { 
         $order->delete();
         return response()->json("order deleted");  
      } else {
         return response()->json('order not found', 404); 
      }
   }
}
