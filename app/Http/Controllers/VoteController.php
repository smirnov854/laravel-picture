<?php

namespace App\Http\Controllers;

use App\Models\Picture;
use App\Models\PictureCategory;
use App\Models\Vote;
use Illuminate\Http\Request;

class VoteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $fields = $request->validate([
            'value'=>'required|integer',
            'category_id'=>'required|integer',
            'picture_id'=>'required|integer',
        ]);

        $pict = Picture::where('id',$fields['picture_id'])->first();
        if(empty($pict)){
            return response(['message'=>'picture not found'],404);
        }

        $cat = PictureCategory::where('id',$fields['category_id'])->first();
        if(empty($cat)){
            return response(['message'=>'category not found'],404);
        }
        $vote = Vote::where('user_id',auth()->user()->id)->
                      where('picture_id',$fields['picture_id'])->
                      where('category_id',$fields['category_id'])->
            first();

        if(!empty($vote)){
            $vote->value = $fields['value'];
            $vote->save();
        }else{
            $vote = Vote::create([
                'value'=>$fields['value'],
                'user_id'=>auth()->user()->id,
                'category_id'=>$fields['category_id'],
                'picture_id'=>$fields['picture_id']
            ]);
        }

        return response(['data'=>$vote]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Vote  $vote
     * @return \Illuminate\Http\Response
     */
    public function show(Vote $vote)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Vote  $vote
     * @return \Illuminate\Http\Response
     */
    public function edit(Vote $vote)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Vote  $vote
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Vote $vote)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Vote  $vote
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return response([Vote::destroy($id)]);
    }
}
