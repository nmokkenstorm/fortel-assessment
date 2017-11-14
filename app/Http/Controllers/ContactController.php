<?php

namespace App\Http\Controllers;

use App\HTTPStatus;
use App\Client;
use App\Contact;
use App\Http\Requests\DeleteContact;
use App\Http\Requests\UpdateContact;
use App\Http\Requests\CreateContact;
use App\Http\Requests\IndexContact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param   \App\Client                      The client we're linking to
     * @param   \App\Http\Requests\Client\Index  The request we're receiving
     * @return  \Illuminate\Http\Response
     */
    public function index(Client $client, IndexContact $request)
    {
        return $client->contacts();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param   \App\Client                         $client
     * @param   \App\Http\Requests\Contact\Create   $request
     * @return  \Illuminate\Http\Response
     */
    public function store(Client $client, CreateContact $request)
    {
        $contact = new Contact($request->only(app('App\Contact')->fillable));

        $client->contacts()->save($contact);

        return response()->json($contact->toArray(), 201);
    }

    /**
     * Display the specified resource.
     *
     * @param   \App\Client     $client
     * @param   \App\Contact    $contact
     * @return  \Illuminate\Http\Response
     */
    public function show(Client $client, Contact $contact)
    {
        return $contact;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function edit(Contact $contact)
    {
        return response(HTTPStatus::Method_Not_Allowed)->json([
            'message'   => __('http_status.method_not_allowed')
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param   \App\Http\Requests\Client\Update    $request
     * @param   \App\Client                         $client
     * @return  \Illuminate\Http\Response
     */
    public function update(UpdateContact $request, Client $client, Contact $contact)
    {
        $contact->update($request->only(app('App\Contact')->fillable));
            
        return response()->json(
            $contact->toArray()
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param   App\Http\Requests\DeleteClient
     * @param   \App\Client  $client
     * @return  \Illuminate\Http\Response
     */
    public function destroy(DeleteClient $request, Client $client)
    {
        $contact->delete();

        return response('', HTTPStatus::No_Content);
    }
}
