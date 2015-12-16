<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Google Map Key
|--------------------------------------------------------------------------
|
| The key used to show the google map: you must create one.
| See: https://developers.google.com/maps/documentation/javascript/get-api-key
|
*/
$config['pms_google_map_key'] = 'AIzaSyCTfXhiQtaWszSzWiO5-cyoL26HdCHI8rQ';

/*
|--------------------------------------------------------------------------
| The rate of update of Agent positions on the Map
|--------------------------------------------------------------------------
|
| In seconds
|
*/
$config['pms_map_refresh_interval'] = 10;

/*
|--------------------------------------------------------------------------
| If the interval of time between the last agent sheet and now is greater than
| this value, we show the agent as disabled on the map
|--------------------------------------------------------------------------
|
| In minutes
|
*/
$config['pms_agent_idle_time'] = 60;