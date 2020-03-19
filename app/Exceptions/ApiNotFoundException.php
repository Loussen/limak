<?php

namespace App\Exceptions;

use Exception;

class ApiNotFoundException extends Exception
{
    /**
     * Report the exception.
     *
     * @return void
     */
    public function report()
    {

    }

    /**
     * Render the exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function render($request)
    {
        $message = 'Müraciət etdiyiniz servis tapılmadı.';
        return response()->view('errors.permissions', ['message' => $message], 500);
    }
}
