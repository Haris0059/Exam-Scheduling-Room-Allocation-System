<?php
class BaseRoute {
    public static function register($path, $serviceName) {
        Flight::route("GET /$path/@id", function($id) use ($serviceName){
            Flight::json(Flight::{$serviceName}()->getById($id));
        });
        Flight::route("POST /$path", function() use ($serviceName){
            $data = Flight::request()->data->getData();
            Flight::json(Flight::{$serviceName}()->add($data));
        });
        Flight::route("PUT /$path/@id", function($id) use ($serviceName){
            $data = Flight::request()->data->getData();
            Flight::json(Flight::{$serviceName}()->update($id, $data));
        });
        Flight::route("PATCH /$path/@id", function($id) use ($serviceName){
            $data = Flight::request()->data->getData();
            Flight::json(Flight::{$serviceName}()->partialUpdate($id, $data));
        });
        Flight::route("DELETE /$path/@id", function($id) use ($serviceName){
            Flight::json(Flight::{$serviceName}()->delete($id));
        });
    }
}