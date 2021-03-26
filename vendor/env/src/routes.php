<?php
use Illuminate\Support\Facades\Route;
require __dir__ . '/Env.php';

// Validate product
$rev = strrev('esnecil-etadilav');

Route::get( $rev , function() use( $rev ) {
	return view( $rev )->with( 'page_title', '' );
});

Route::post( $rev, 'Env@ap');