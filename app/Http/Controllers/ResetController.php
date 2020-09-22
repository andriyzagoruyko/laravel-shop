<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;


class ResetController extends Controller
{
    public function reset() {
        session()->flush();
        Artisan::call('migrate:fresh --seed');

        foreach(['categories', 'products'] as $folder){
            Storage::deleteDirectory($folder);
            Storage::makeDirectory($folder);
    
            $files = Storage::Disk('reset')->files($folder);
    
            foreach ($files as $file) {
                Storage::put($file, Storage::Disk('reset')->get($file));
            }
        }

        session()->flash('success', 'Проект был сброшен в начальное состояние');
        return redirect()->route('index');
    }
}
