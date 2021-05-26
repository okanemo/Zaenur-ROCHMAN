<?php

namespace App\Traits;

use Illuminate\Http\Response;

trait ApiResponse
{
    public function responseSukses($data, $code = Response::HTTP_OK,$data_khusus = null)
    {
        // dd($data_khusus !== null);
        if($data_khusus !== null){
            $res['status'] = $code;
            $res['message'] = $data['message'];

            $response = array_merge($res,$data_khusus);
        }else{
            $response = [
                'status' => $code,
                'message' => $data['message'],
                'total_data' => isset($data['total']) ? $data['total'] : (is_countable($data['data']) ? count($data['data']) : (($data['data'] == "") ? 0:1)),
                'count_data' => is_countable($data['data']) ? count($data['data']):(($data['data'] == "") ? 0:1),
                'data' => isset($data['data']) ? $data['data']:"", 
            ];
        }


        if(isset($data['token'])){
            $response['token'] = $data['token'];
            $response['token_type'] = $data['token_type'];
            $response['expires_in'] = $data['expires_in'];
        }

        // if(isset($data['current_page'])){
        //     array_push($data,$response);
        // }

        return \response()->json($response, $code);
    }

    public function responseError($message,$error, $code)
    {
        return \response()->json([
            'status' => $code,
            'message' => $message,
            'total_data' => 0,
            'count_data' => 0,
            'data' => isset($data['data']) ? $data['data']:"", 
            'errors' => $error, 
        ], $code);
    }
}
