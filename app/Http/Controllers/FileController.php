<?php

namespace App\Http\Controllers;

use App\Models\File;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Str;

class FileController extends Controller
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

    public static function store($file, $folder, $name = null){
        if($file !== null) {
            // Upload path
            $destinationPath = 'files/' . $folder;
    
            // Create directory if not exists
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }
    
            // Get file extension
            $extension = $file->getClientOriginalExtension();
    
            // Valid extensions
            $validextensions = array('jpg', 'png', 'jpeg');
    
            // Check extension
            if(in_array(strtolower($extension), $validextensions)) {
                $file_name = Str::uuid() . '.' . $extension;;
                // Uploading file to given path
                if($name != null) $file_name = $name;

                $original_name = $file->getClientOriginalName();

                $file = \App\ImageHelper::compress($file, $destinationPath . '/' . $file_name, 90);

                // $file->move($destinationPath, $file_name); 
               
                
                $file_db = new File();
                $file_db->name = $file_name;
                $file_db->og_name = $original_name;
                $file_db->path = $destinationPath;
                $file_db->owner = auth()->user()->id;
                $file_db->save();
                
                return $file_db;
            }
        }

        return null;
    }

}