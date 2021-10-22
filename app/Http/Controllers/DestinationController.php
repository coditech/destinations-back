<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Destination;
use App\Http\Requests\AddDestinationRequest;
use File;

class DestinationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


    public function imageDelete($oldImage, $cat_id)
    {
        if(File::exists(public_path('storage/images/destinations/'. $cat_id .'/'. $oldImage)))
        {
            File::delete(public_path('storage/images/destinations/'. $cat_id . '/' . $oldImage));
            return true;
        }
        else
        {
            return false;
        }
    }
    public function index()
    {
        //
        try{
            $destinations = Destination::with('continent')->orderBy('title', 'ASC')->get();
            if($destinations){
                return response()->json([
                    'data'=> $destinations
                ],200);
            }
            return response()->json([
                'destinations'=>"empty"
            ],404);
        }
        catch(\Exception $e){
            return response()->json([
                'message'=>'internal error'
            ],500);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
       
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $destination = new Destination();
        $destination->fill($request->all());//because we used fillable
        if($image=$request->file('image'))
        {
          $image=$request->image;
            $image->store('public/images/destinations/'. $request->continent_id);
            $destination->image = $image->hashName();
        }
        if($destination->save()){ //returns a boolean
            return response()->json([
                'data'=> $destination
            ],200);
        }
        else
        {
            return response()->json([
                'destination'=>'destination could not be added' 
            ],500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $destination = Destination::find($id);
        if($destination)
        {
            return response()->json([
                'data'=> $destination
            ],200);
        }
        return response()->json([
            'destination'=>'destination could not be found' 
        ],500);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $destination = Destination::find($id);
        if($destination){
            $destination->update($request->all());//because we used fillable
            if($image=$request->file('image'))
            {
                
                if($this->imageDelete($destination->image,$destination->continent_id)){
                    
                    $image=$request->image;
                    $cat =  is_null($request->continent_id)? $destination->continent_id: $request->continent_id;
                    $image->store('public/images/destinations/'. $cat);                    
                    $destination->image = $image->hashName();
                }
                else
                {
                    $image=$request->image;
                    $cat =  is_null($request->continent_id)? $destination->continent_id: $request->continent_id;                   
                    $image->store('public/images/destinations/'. $cat);                    
                    $destination->image = $image->hashName();
                }               
            }
            if($destination->save()){ //returns a boolean
                return response()->json([
                    'data'=> $destination
                ],200);
            }
            else
            {
                return response()->json([
                    'destination'=>'destination could not be updated' 
                ],500);
            }
        }
        return response()->json([
            'item'=>'item could not be found' 
        ],500);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $destination = Destination::find($id);
        if($destination->delete()){ //returns a boolean
            if($this->imageDelete($destination->image,$destination->continent_id)){
               // var_dump('got deleted');
               return response()->json([
                'destination'=> "good for you"
            ],200);
            }
            else
            {
                return response()->json([
                    'destination'=>'destination could not be deleted' 
                ],500);
            }
            return response()->json([
                'destination'=> "good for you"
            ],200);
        }
        else
        {
            return response()->json([
                'destination'=>'destination could not be deleted' 
            ],500);
        }
    }
}
