<?php

namespace App\Http\Controllers;

use App\Models\Picture;
use App\Models\PictureCategory;
use App\Models\Vote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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


    public function addVotes(Request $request){
        $categorys = $request->post('categorys');
        $picture_id = $request->post('picture_id');

        foreach($categorys as $row){
            $vote = Vote::where('user_id',auth()->user()->id)->
                          where('picture_id',$picture_id)->
                          where('category_id',$row['id'])->first();
            if(!empty($vote)){
                $vote->value = $row['value'];
                $vote->save();
            }else{
                $vote = Vote::create([
                    'value'=>$row['value'],
                    'user_id'=>auth()->user()->id,
                    'category_id'=>$row['id'],
                    'picture_id'=>$picture_id
                ]);
            }
        }
        return response(['message'=>'votes successfully  added'],201);
    }

    public function pictureVotes(Request $request,$id){
            $data = Picture::select(
                'picture_categories.name',
                'picture_categories.id',
                DB::raw('SUM(value) as total_value')
            )
                ->crossJoin('picture_categories')->leftJoin("votes",function($join) use ($id){
                        $join->on('votes.category_id', '=', 'picture_categories.id');
                        $join->on('votes.picture_id','=',DB::raw("'".$id."'"));
                })
                        ->where('pictures.id','=',$id)
                        ->groupBy('pictures.id')
                        ->groupBy('picture_categories.id')
                        ->get();

            return response($data);
    }
}
