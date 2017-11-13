<?php

namespace Tests\Unit;

use App\Client;
use Tests\TestCase;

class ClientTest extends TestCase
{
    /**
     * A basic test for reading clients.
     *
     * @test
     * @dataProvider    readDataProvider
     *
     * @param           int         $model_id       The id of the model we're creating
     * @param           int         $id             Whatever the thing is we're looking for
     * @param           int         $status_code    Whatever the status is we're expecting
     * @param           bool        $success        Wheter we expect to succeed
     * @return          void
     */
    public function showTest($model_id, int $id, int $status_code, bool $success)
    { 
        $model = factory(Client::class)->create(['id' => $model_id])->first();

        // do the call
    	$response = $this->json('GET', "api/clients/$id");
 
        // assert our status
		$response->assertStatus($status_code);

        // assert response
        $response->assertJson($success ? [
		    'id'            =>  1,
		    'first_name' 	=>	$model->first_name,
		    'last_name'		=>	$model->last_name,
		    'email'			=>	$model->email,
        ] : [
            'message'       => 'The requested model was not found'
        ]);
    }

    /**
     * A basic test for creating clients.
     * 
     * @test
     * @dataProvider    createDataProvider 
     * 
     * @param           App\Client  $data           What will get posted to the endpoint
     * @param           int         $status_code    The status code we're expecting back
     * @param           bool        $success        Wheter we're expecting to succeed 
     * @return          void
     */
    public function createTest(Client $data, int $status_code, bool $success)
    {
    	$response = $this->json('POST', 'api/clients', array_only($data->toArray(), ['first_name', 'last_name', 'email']));
 
		$response->assertStatus($status_code);
        
        $response->assertJson($success ? [
		    'id'            =>  1,
			'first_name' 	=>	$data->first_name,
			'last_name'		=>	$data->last_name,
			'email'			=>	$data->email,
        ] : [
            'message'       => 'The given data was invalid.'
        ]);
	}

    /**
     * A basic test for listing clients.
     * 
     * @test
     * @dataProvider    ListDataProvider
     *
     * @param           int $limit              the max results we want per page
     * @param           int $page               the page we want to see
     * @param           int $count              the resultcount we're expecting
     * @param           int $status             the response code we're expecting
     * @param           int $amount             the amount of results we want to create
     * @param           int $creating           the amount of records we're creating
     * @param           bool $success           if we expect to succeed
     *
     * @return          void
     */
    public function listTest(int $creating, int $page, int $limit, int $count, int $status, bool $success)
    {
        Client::withTrashed()->get()->each(function($e){
            $e->delete();
        });

        factory(Client::class, $creating)->create();

        $response = $this->json('GET', "api/clients?page=$page&limit=$limit");

        $response->assertStatus($status);

        if ($success) {
            $this->assertEquals($count, count($response->json()['data']));
        }    
    }

    /**
     * A basic test for updating clients.
     * 
     * @test
     * @dataProvider    UpdateDataProvider
     *
     * @param           string  $key            the max results we want per page
     * @param           string  $value          the page we want to see
     * @param           int $status             the response code we're expecting
     * @param           bool $success           if we expect to succeed
     *
     * @return          void
     */
    public function updateTest(string $key, string $value, int $status, bool $success)
    {
        $model = factory(Client::class, 1)->create()->first();

        $old    = $model->$key;

        $response = $this->json('PUT', "api/clients/$model->id", [$key => $value]);

        $model = $model->fresh();

        $response->assertStatus($status);

        $this->assertEquals($success ? $value : $old, $model->$key);
    }

    /**
     * Super basic test for model deletion
     *
     * @test
     *
     * @return void
     */
    public function deleteTest()
    {
        $model = factory(Client::class, 1)->create()->first();

        $id = $model->id;

        $this->json('DELETE', "api/clients/$id")->assertStatus(204);

        $this->json('GET', "api/clients/$id")->assertStatus(404);
    }
    
    /**
     * Dataprovider for create data testing
     * 
     * @return array<App\Client, int, bool>
     */
    public function createDataProvider()
    {
        // setup the application in the provider
        $this->refreshApplication();

        // store faker instance
        $faker = resolve('Faker\Generator');

        return array_merge(
            // asume we have some standard data to work with
            factory(Client::class, 3)->make([
                // just use the default here
            ])->map(function ($e) {
                return [
                    'data'      =>  $e,
                    'status'    =>  201,
                    'success'   =>  true,
                ];
            })->toArray(),
            // and some weird cases
            [
                [
                    'data'      => factory(Client::class, 1)->make(['first_name' => ''])->first(),
                    'status'    => 422,
                    'success'   => false,
                ],
                [
                    'data'      => factory(Client::class, 1)->make(['last_name' => $faker->randomDigit])->first(),
                    'status'    => 422,
                    'success'   => false,
                ],
                [
                    'data'      => factory(Client::class, 1)->make(['email' => $faker->freeEmailDomain])->first(),
                    'status'    => 422,
                    'success'   => false,
                ]
            ]
        );
    }   

    /**
     * Dataprovider for read data testing
     * 
     * @return array<int, int, int, bool>
     */
    public function readDataProvider()
    {
        // setup the application in the provider
        $this->refreshApplication();

        return [
            [
                'looking_for'   => 1,
                'created'       => 1,
                'status'        => 200,
                'success'       => true,
            ],
            [
                'looking_for'   => 1,
                'created'       => 2,
                'status'        => 404,
                'success'       => false,
            ]
        ]; 
    }

    public function listDataProvider()
    {
        return [
            [
                'creating'  => 100,
                'page'      => 1,
                'limit'     => 100,
                'count'     => 100,
                'response'  => 200,
                'success'   => true,
            ],
            [
                'creating'  => 100,
                'page'      => 1,
                'limit'     => 50,
                'count'     => 50,
                'response'  => 200,
                'success'   => true,
            ],
            [
                'creating'  => 100,
                'page'      => -1,
                'limit'     => 50,
                'count'     => 50,
                'response'  => 422,
                'success'   => false,
            ],
            [
                'creating'  => 60,
                'page'      => 2,
                'limit'     => 50,
                'count'     => 10,
                'response'  => 200,
                'success'   => true,
            ],
            [
                'creating'  => 100,
                'page'      => 3,
                'limit'     => 50,
                'count'     => 0,
                'response'  => 200,
                'success'   => true,
            ],
        ];
    }

    /**
     * Dataprovider for the update endpoint
     *
     * @return array<string, string, int, bool>
     */
    public function updateDataProvider()
    {
        return [
            [
                'key'       =>  'first_name',
                'value'     =>  'test',
                'status'    =>  200,
                'success'   =>  true,
            ],
            [
                'key'       =>  'email',
                'value'     =>  'test',
                'status'    =>  422,
                'success'   =>  false,
            ],  
        ];
    }
}
