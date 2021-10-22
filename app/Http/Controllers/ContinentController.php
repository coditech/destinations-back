<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Continent;
use App\Http\Requests\AddContinentRequest;
use File;

class ContinentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function imageDelete($oldImage)
    {
        if(File::exists(public_path('storage/images/continents/'. $oldImage)))
        {
            File::delete(public_path('storage/images/continents/' . $oldImage));
            
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
            $continents = Continent::orderBy('name', 'ASC')->get();
            if($continents){
                return response()->json([
                    'data'=> $continents
                ],200);
            }
            return response()->json([
                'continents'=>"empty"
            ],404);
        }
        catch(\Exception $e){
            return response()->json([
                'continents'=>'internal error'
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
    public function store(AddContinentRequest $request)
    {
        //    
        $continent = new Continent();
        $continent->fill($request->all());//because we used fillable
        if($image=$request->file('image'))
        {
          $image=$request->image;
            $image->store('public/images/continents');
            $continent->image = $image->hashName();
        }
        if($continent->save()){ //returns a boolean
            return response()->json([
                'data'=> $continent
            ],200);
        }
        else
        {
            return response()->json([
                'continent'=>'continent could not be added' 
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
        $continent = Continent::find($id);
        if($continent)
        {
            return response()->json([
                'data'=> $continent
            ],200);
        }
        return response()->json([
            'continent'=>'continent could not be found' 
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
        $continent = Continent::find($id);
        //what is the best way to validate the update request
        if($continent){
            $continent->update($request->all());//because we used fillable
            if($image=$request->file('image'))
            {
                
                if($this->imageDelete($continent->image)){
                    
                    $image=$request->image;
                    $image->store('public/images/continents');                    
                    $continent->image = $image->hashName();
                }
                else
                {
                    $image=$request->image;
                   $image->store('public/images/continents');                    
                    $continent->image = $image->hashName();
                }               
            }
            if($continent->save()){ //returns a boolean
                return response()->json([
                    'data'=> $continent
                ],200);
            }
            else
            {
                return response()->json([
                    'continent'=>'continent could not be updated' 
                ],500);
            }
        }
        return response()->json([
            'continent'=>'continent could not be found' 
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
        $continent = Continent::find($id);
        if($continent->delete()){ //returns a boolean
            if($this->imageDelete($continent->image)){
                var_dump('got deleted');
            }
            else
            {
                var_dump('didnt delete');
            }
            return response()->json([
                'continent'=> "good for you"
            ],200);
        }
        else
        {
            return response()->json([
                'continent'=>'continent could not be deleted' 
            ],500);
        }
    }
}
