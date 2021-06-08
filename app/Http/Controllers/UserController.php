<?php

namespace App\Http\Controllers;

use App\ImageHelper;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use App\Models\File;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function updateAccount()
    {
        $user = \App\Models\User::where('id', auth()->user()->id)->first();
        $user->bio = \request('bio');
        $user->save();
        return redirect('/account');
    }

    public function updateAvatar(Request $request) {
        // store image in
        $file_name = auth()->user()->id . '.png';
        $path = public_path('files') . '/avatars/';

        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }

        ImageHelper::compress($request['data'], $path . $file_name, 100);

        $file_db = new File();
        $file_db->name = $file_name;
        $file_db->path = 'files/avatars';
        $file_db->owner = auth()->user()->id;
        $file_db->save();

        $user = \App\Models\User::where('id', auth()->user()->id)->first();
        $user->avatar = $file_db->id;
        $user->save();

        return response()->json(['success' => true, 'img-url' => $file_db->getURL(), 'img-id' => $file_db->id]);
    }
}
