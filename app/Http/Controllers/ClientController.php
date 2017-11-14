<?php

namespace App\Http\Controllers;

use App\HTTPStatus;
use App\Client;
use App\Http\Requests\Client\Create;
use App\Http\Requests\Client\Index;
use App\Http\Requests\Client\Update;
use App\Http\Requests\Client\Delete;

use Illuminate\Http\Request;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param   \App\Http\Requests\Client\Index
     * @return  \Illuminate\Http\Response
     */
    public function index(Index $request)
    {
        return Client::paginate($request->input('limit', Client::MAX_PER_PAGE));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Client\Create $request
     * @return \Illuminate\Http\Response
     */
    public function store(Create $request)
    {
        return response()->json(
                Client::create($request->only(app('App\Client')->fillable))
                ->toArray(),
            201
        );
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
        return response(HTTPStatus::METHOD_NOT_ALLOWED)->json([
            'message'   => __('http_status.method_not_allowed')
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Client\Update $request
     * @param  \App\Client                      $client
     * @return \Illuminate\Http\Response
     */
    public function update(Update $request, Client $client)
    {
        $client->update($request->only(app('App\Client')->fillable));

        return response()->json(
                $client->toArray(),
            200
        );
    }

   /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function destroy(Client $client)
    {
        $client->delete();
        
        // we could probably do transactions or observers here
        $client->contacts()->each(function ($contact) {
            $contact->delete();
        });

        return response('', 204);
    
    }
}
