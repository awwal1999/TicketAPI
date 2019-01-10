<?php

namespace App\Http\Controllers;

use App\Type;
use Illuminate\Http\Request;
use App\Http\Resources\Type as TypeResource;

class TypesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $types = Type::all();
        return new TypeResource($types);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $attributes = [
            'name' => ['required', 'min:3', 'max:190'],
            'description' => ['required', 'min:3'],
        ];

        $attributes = $request->validate($attributes);

        $type = Type::create($attributes);
        return (new TypeResource($type))->additional(['meta' => [
            'Ok' => '201',
        ]]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Type  $type
     * @return \Illuminate\Http\Response
     */
    public function show(Type $type)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Type  $type
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Type $type)
    {
        $type->fill($request->only([
            'name', 'description'
        ]));

        if ($type->isClean()) {
            return response()->json(['error' => 'You need to specify a different value to update', 'code' => 422], 422);
        }
        $type->save();
        return new TypeResource($type);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Type  $type
     * @return \Illuminate\Http\Response
     */
    public function destroy(Type $type)
    {
        $type->delete();
        return new TypeResource($type);
    }
}
