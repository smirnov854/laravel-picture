<?php

namespace App\Http\Controllers;

use App\Models\PictureCategory;
use Illuminate\Http\Request;

class PictureCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response([
            'list'=>PictureCategory::all()
        ]);
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
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PictureCategory  $pictureCategory
     * @return \Illuminate\Http\Response
     */
    public function show(PictureCategory $pictureCategory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PictureCategory  $pictureCategory
     * @return \Illuminate\Http\Response
     */
    public function edit(PictureCategory $pictureCategory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PictureCategory  $pictureCategory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PictureCategory $pictureCategory)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PictureCategory  $pictureCategory
     * @return \Illuminate\Http\Response
     */
    public function destroy(PictureCategory $pictureCategory)
    {
        //
    }
}
