<?php

namespace App\Http\Controllers;

use App\ImageHelper;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use App\Models\File;

class ClosetController extends Controller {

    public function getLoves(Request $request) {
        $user_id = $request['user_id'];

        if(! \App\Models\User::where('id', $user_id)->exists()) {
            return response()->json(['success' => false, 'msg' => 'User not found.']);
        }

        $user = \App\Models\User::where('id', $user_id)->first();
        return response()->json(['success' => true, 'msg' => $user->getLovedProducts()]);
    }

    public function getCloset(Request $request) {
        $closet_id = $request['closet_id'];

        if(! \App\Models\Closet::where('id', $closet_id)->exists()) {
            return response()->json(['success' => false, 'msg' => 'Closet not found.']);
        }

        $closet = \App\Models\Closet::where('id', $closet_id)->first();
        return response()->json(['success' => true, 'msg' => $closet->getProducts()]);
    }

}
