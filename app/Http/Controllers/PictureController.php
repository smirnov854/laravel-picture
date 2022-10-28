<?php

namespace App\Http\Controllers;

use App\Models\Picture;
use App\Models\Vote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PictureController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response(['list'=>Picture::all()]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(!$request->hasFile('image')) {
            return response([
                'message'=>'need to upload file'
            ],403);
        }
        if($request->hasFile('image')) {
            $file = $request->file('image');
            $upload_folder = 'public/pictures';
            $filename = $file->getClientOriginalName();
            $file_extension = $file->getClientOriginalExtension();
            $new_file_name = md5($filename.time()).".".$file_extension;
            Storage::putFileAs($upload_folder, $file, $new_file_name);
        }

        $fields = $request->validate([
            'name'=>'required',
        ]);
        return Picture::create([
            'name'=>$fields['name'],
            'path'=>'/storage/pictures/'.$new_file_name,
            'user_id'=>auth()->user()->id
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Picture  $picture
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $pictureData = Picture::where('id',$id)->first();
        $votes = Vote::where('picture_id',$id)->get();
        return response([
            'picture_data'=>$pictureData,
            'votes'=>$votes
        ]);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Picture  $picture
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Picture $picture)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Picture  $picture
     * @return \Illuminate\Http\Response
     */
    public function destroy(Picture $picture)
    {
        //
    }

    public function userPictures(){
        return response([
            'list'=>Picture::where('user_id',auth()->user()->id)->get()
        ]);
    }
}
