<?php

namespace App\Http\Controllers;

use App\Models\Picture;
use App\Models\PictureCategory;
use App\Models\Vote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Monolog\Logger;

class PictureController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response(Picture::orderBy('id','DESC')->paginate(12));
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
            'name'=>'required',
            'image'=>'required|mimes:jpeg,png,bmp,jpg'
        ]);
        if(!$request->hasFile('image')) {
            return response([
                'message'=>'need to upload file'
            ],400);
        }
        if($request->hasFile('image')) {
            $file = $request->file('image');
            $upload_folder = 'public/pictures';
            $filename = $file->getClientOriginalName();
            $file_extension = $file->getClientOriginalExtension();
            $new_file_name = md5($filename.time()).".".$file_extension;
            Storage::putFileAs($upload_folder, $file, $new_file_name);
        }


        return response(Picture::create([
            'name'=>$fields['name'],
            'path'=>'/storage/pictures/'.$new_file_name,
            'user_id'=>auth()->user()->id
        ]),201);
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
        $votes = Vote::where('picture_id',$id)->where('user_id','=',auth()->user()->id)->get();
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
        /*$data = DB::select(
            "SELECT p.id,
                   p.user_id,
                   p.name,
                   p.path,
                   cat.name,
                   (
                       SELECT SUM(value)
                       FROM votes v2
                       WHERE v2.picture_id=p.id AND v2.category_id=cat.id
                    )
            FROM pictures p,picture_categories cat
            WHERE p.user_id=51
            ORDER BY id DESC"
        );*/

        $data = Picture::where('pictures.user_id',auth()->user()->id)
            ->orderBy('id','DESC')
            ->paginate(10);
        return response($data);
    }

}
