<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

abstract class Controller {
    public function __construct() {
        $locale = Session::get('locale', config('app.locale'));
        App::setLocale($locale);
    }
}
