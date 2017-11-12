<?php

namespace Tests\Unit;

use App\Client;
#use Faker\Generator as Faker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClientTest extends TestCase
{
    /**
     * A basic test for reading clients.
     * 
     * @test
     * @return void
     */
    public function showTest()
    {
		$model = factory(Client::class)->create();

    	$response = $this->json('GET', 'api/clients/1');
 
		$response->assertStatus(200)
				 ->assertJson([
					'id'            =>  1,
					'first_name' 	=>	$model->first_name,
					'last_name'		=>	$model->last_name,
					'email'			=>	$model->email,
             ]);
	}

    /**
     * A basic test for creating clients.
     * 
     * @dataProvider    createDataProvider 
     * @test
     * @return void
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

    public function createDataProvider()
    {
        // setup the application in the provider
        $this->createApplication();

        // store faker instance
        $faker = resolve('Faker\Generator');

        return array_merge(
            // asume we have some standard data to work with
            factory(Client::class, 3)->make([
                // just use the default here
            ])->map(function ($e) {
                return [
                    'data'      =>  $e,
                    'status'    =>  200,
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

}
