<?php

namespace App\Http\Controllers;

use App\HTTPStatus;
use App\Client;
use App\Http\Requests\CreateClient;
use App\Http\Requests\IndexClient;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(IndexClient $request)
    {
        return Client::paginate($request->input('limit', Client::MAX_PER_PAGE));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  App\Http\Requests\CreateClient $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateClient $request)
    {
        return response()->json(Client::create($request->only(app('App\Client')->fillable))->toArray(), 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function show(Client $client)
    {
        return $client;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function edit(Client $client)
    {
        return response(HTTPStatus::Method_Not_Allowed)->json([
            'message'   => __('http_status.method_not_allowed')
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Client $client)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function destroy(Client $client)
    {
        //
    }
}
