<?php

use App\Models\Branches;
use App\Models\MDRUsers;
use App\Models\Tokenization;

class GeneralParams {
    public static $result = [];
    public static $dynamic_result = [];
    public static $branches = [];
    public static $signoutMessage;
}

class GeneralHelper {

    public static function Slug($method, $params, $condition){
        switch(true) {
            case $method === 'get' : {
                if($condition == 'api/checktoken'){
                    $instance = new self;
                    $instance->_init_checktoken($params);
                }
                else if($condition == 'api/getuser-info'){
                    $instance = new self;
                    $instance->_init_getcurrent_userInfo($params);
                } else if($condition == 'api/get-all-branches') {
                    $instance = new self;
                    $instance->_init_getAll_branches();
                }
            }
            case $method === 'put' : {
              if($condition == 'api/signout') {
                $instance = new self;
                $instance->_init_signout($params);
              }
            }
        }
    }

    public function _init_checktoken($userID){

        GeneralParams::$result = Tokenization::select('*')
        ->where('userID', '=', $userID)
        ->where('isvalid', '=', '1')->get();
        return GeneralParams::$result;
    }

    public function _init_getcurrent_userInfo($userID){
        GeneralParams::$dynamic_result = MDRUsers::select('*')
        ->where('id', '=', $userID)->get();
        return GeneralParams::$dynamic_result;
    }
    public function _init_getAll_branches(){
        return GeneralParams::$branches = Branches::orderBy('branchID')->get();
    }
    public function _init_signout($userID) {
      Tokenization::where('userID', $userID)->update([
          'isvalid' => '0'
      ]);
      return GeneralParams::$signoutMessage = "SIGNOUT_SUCCESS";
    }
}
