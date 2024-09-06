<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Location;
use Illuminate\Support\Facades\Auth;
class LocationController extends Controller
{
    public function store (Request $request){
        $request->validate([ 
            'street' => 'required|string',
            'building' => 'required|string',
            'area' => 'required|string',
             
             
        ]); 
        Location::create([
            'street'=>$request->street  , 
            'building'=>$request->building  , 
            'area'=>$request->area  , 
            
            'user_id'=>Auth::id(),
        ]  ) ; 
         
        return response()->json('location added',201) ;   ; 
         
         
    }

    public function update_location(Request $request, $id)
{
   

  


    $request->validate([
        'street' => 'required|string',
        'building' => 'required|string',
        'area' => 'required|string',
    ]);

    $location = Location::find($id);

    if ($location) {

       
           
            $location->update([
                'street' => $request->street,
                'building' => $request->building,
                'area' => $request->area,
            ]);

            return response()->json('location updated successfully', 200);
        
    } else {
        return response()->json('location not found', 404);
    }
}

 

    public function delete_location($id){ 
        $location =Location ::find($id) ; 
        if ($location ){ 
            $location ->delete() ; 
            return response()->json('location deleted') ; 
              
             
             
        } 
        else { 
            return response()->json('location not found'); 
        }
         
         
    }
}
