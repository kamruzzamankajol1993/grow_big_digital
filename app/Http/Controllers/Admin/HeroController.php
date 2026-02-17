<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\GrowBigHero;
use App\Http\Controllers\Admin\CommonController;
use Illuminate\Support\Facades\File;

class HeroController extends Controller
{
    public function index()
    {
        $hero = GrowBigHero::first();
        return view('admin.hero.index', compact('hero'));
    }

    public function update(Request $request)
    {
        $hero = GrowBigHero::first() ?? new GrowBigHero();
        $data = $request->except(['_token']);

        // ১. টিম মেম্বার ইমেজ (500x500)
        $teamImages = ['member_one_image', 'member_two_image', 'member_three_image'];
        foreach ($teamImages as $img) {
            if ($request->hasFile($img)) {
                if ($hero->$img && File::exists(public_path($hero->$img))) File::delete(public_path($hero->$img));
                $file = $request->file($img);
                $name = time() . "_$img." . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/hero'), $name);
                $data[$img] = 'uploads/hero/' . $name;
            }
        }

        // ২. সকল আইকন (50x50)
        $icons = [
            'member_one_icon', 'member_two_icon', 'member_three_icon',
            'success_icon', 'client_icon', 'positive_icon'
        ];
        foreach ($icons as $icon) {
            if ($request->hasFile($icon)) {
                if ($hero->$icon && File::exists(public_path($hero->$icon))) File::delete(public_path($hero->$icon));
                $file = $request->file($icon);
                $name = time() . "_$icon." . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/hero/icons'), $name);
                $data[$icon] = 'uploads/hero/icons/' . $name;
            }
        }

        $hero->fill($data)->save();
        CommonController::addToLog('Hero Section Full Update');

        return back()->with('success', 'Hero Section all columns updated!');
    }
}