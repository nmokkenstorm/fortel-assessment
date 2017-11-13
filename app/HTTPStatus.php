<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Just a utility thing which should be a seperate package.
 * 
 * @author Niels Mokkenstorm
 */
class HTTPStatus extends Model
{
    /**
     * Since this class is realy just a list of codes, list them
     */
    const OK                    = 200;
    const Created               = 201;
    const No_Content            = 204;

    const Not_Found             = 404;
    const Method_Not_Allowed    = 405;
    const Not_Acceptable        = 406;
    const Gone                  = 410;
}
