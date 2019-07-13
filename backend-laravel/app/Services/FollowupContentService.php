<?php

namespace App\Services;
use App\Models\FollowupContent;
class FollowupContentService{
    static public $service;
    static public function getService(){
        if(!self::$service) self::$service = new self();
        return self::$service;
    }

    public function GetFollowupContent( $index ){
        $allContents = FollowupContent::where('id','!=','-1')->orderBy('index')->get();
        return $allContents[$index % sizeof($allContents)]->content;
    }
}