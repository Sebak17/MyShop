<?php

namespace App\Http\Controllers\AdminSystem;

use App\Http\Controllers\Controller;
use App\Rules\ValidMaintenanceMessage;
use App\Rules\ValidProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class SettingsController extends Controller
{

    //
    //      MAINTENANCE
    //

    public function maintenanceChange(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'enabled' => 'required|boolean',
            'msg'     => new ValidMaintenanceMessage,
        ]);

        $results = array();

        if ($validator->errors()->first('enabled') != '') {
            $results['success'] = false;
            $results['msg']     = $validator->errors()->first();
            return response()->json($results);
        }

        $msg = null;

        if ($validator->errors()->first('msg') == '') {
            $msg = $request->msg;
        }

        $toEnable = $request->enabled;

        if ($toEnable) {
            $data            = array();
            $data['time']    = time();
            $data['message'] = $msg;
            $data['retry']   = null;
            $data['allowed'] = array();

            if (Storage::exists('allowed_ips.json')) {
                $data['allowed'] = json_decode(Storage::get('allowed_ips.json'), true);
            }

            File::put(storage_path('framework/down'), json_encode($data, JSON_PRETTY_PRINT));
        } else {
            File::delete(storage_path('framework/down'));
        }

        $results['success'] = true;
        return response()->json($results);
    }

    public function maintenanceAddIP(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'ip' => 'required|ip',
        ]);

        $results = array();

        if ($validator->fails()) {
            $results['success'] = false;
            $results['msg']     = $validator->errors()->first();
            return response()->json($results);
        }

        $ips = array();

        if (Storage::exists('allowed_ips.json')) {
            $ips = json_decode(Storage::get('allowed_ips.json'), true);
        }

        if (in_array($request->ip, $ips)) {
            $results['success'] = false;
            $results['msg']     = "IP jest już na liście!";
            return response()->json($results);
        }

        array_push($ips, $request->ip);

        Storage::put('allowed_ips.json', json_encode($ips, JSON_PRETTY_PRINT));

        if (File::exists(storage_path('framework/down'))) {
            $mainInfo            = json_decode(File::get(storage_path('framework/down')), true);
            $mainInfo['allowed'] = $ips;
            File::put(storage_path('framework/down'), json_encode($mainInfo, JSON_PRETTY_PRINT));
        }

        $results['success'] = true;
        $results['list']    = $ips;
        return response()->json($results);
    }

    public function maintenanceDelIP(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'ip' => 'required|ip',
        ]);

        $results = array();

        if ($validator->fails()) {
            $results['success'] = false;
            $results['msg']     = $validator->errors()->first();
            return response()->json($results);
        }

        $ips = json_decode(Storage::get('allowed_ips.json'), true);

        if ($request->ip == "127.0.0.1") {
            $results['success'] = false;
            $results['msg']     = "Nie możesz tego usunąć!";
            return response()->json($results);
        }

        if (!in_array($request->ip, $ips)) {
            $results['success'] = false;
            $results['msg']     = "IP nie ma liście!";
            return response()->json($results);
        }

        if (($key = array_search($request->ip, $ips)) !== false) {
            unset($ips[$key]);
        }

        $ips = array_values($ips);

        Storage::put('allowed_ips.json', json_encode($ips, JSON_PRETTY_PRINT));

        if (File::exists(storage_path('framework/down'))) {
            $mainInfo            = json_decode(File::get(storage_path('framework/down')), true);
            $mainInfo['allowed'] = $ips;
            File::put(storage_path('framework/down'), json_encode($mainInfo, JSON_PRETTY_PRINT));
        }

        $results['success'] = true;
        $results['list']    = $ips;
        return response()->json($results);
    }

    //
    //      BANNERS
    //

    public function bannersUploadImage(Request $request)
    {
        $results = array();

        if (!$request->hasFile('images')) {
            $results['success'] = false;
            return response()->json($results);
        }

        $validator = Validator::make($request->all(), [
            'images'   => 'required',
            'images.*' => 'mimes:png,jpeg',
        ]);

        $results = array();

        if ($validator->fails()) {
            $results['success'] = false;
            $results['msg']     = $validator->errors()->first();
            return response()->json($results);
        }

        $banners = array();

        if (Storage::exists('banners.json')) {
            $banners = json_decode(Storage::get('banners.json'), true);
        }

        $files = $request->file('images');

        foreach ($files as $file) {
            $ar   = explode("/", $file->store('public/banners'));
            $hash = end($ar);

            array_push($banners, $hash);
        }

        Storage::put('banners.json', json_encode($banners, JSON_PRETTY_PRINT));

        $results['success'] = true;

        return response()->json($results);
    }

    public function bannersRemoveImage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => new ValidProductImage,
        ]);

        $results = array();

        if ($validator->fails()) {
            $results['success'] = false;

            $results['msg'] = $validator->errors()->first();

            return response()->json($results);
        }

        if (!Storage::exists("public/banners/" . $request->name)) {
            $results['success'] = false;
            $results['msg']     = "Plik nie istnieje!";
            return response()->json($results);
        }

        $banners = array();

        if (Storage::exists('banners.json')) {
            $banners = json_decode(Storage::get('banners.json'), true);
        }

        Storage::delete("public/banners/" . $request->name);
        Storage::put('banners.json', json_encode(array_diff($banners, [$request->name]), JSON_PRETTY_PRINT));

        $results['success'] = true;
        return response()->json($results);
    }

}
