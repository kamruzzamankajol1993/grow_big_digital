<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\GrowBigWhoWeAre;
use App\Models\WhoWeAreList;
use App\Http\Controllers\Admin\CommonController;
use Illuminate\Support\Facades\File;
use DB;

class WhoWeAreController extends Controller
{
    public function index()
    {
        $data = GrowBigWhoWeAre::with('listItems')->first();
        return view('admin.whoweare.index', compact('data'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'feature_titles' => 'nullable|array',
        ]);

        try {
            DB::beginTransaction();

            $whoWeAre = GrowBigWhoWeAre::first() ?? new GrowBigWhoWeAre();
            
            // সকল কলাম অন্তর্ভুক্ত করা হয়েছে
            $inputs = $request->only([
                'title', 'subtitle_one', 'subtitle_two', 'subtitle_three', 
                'short_description', 'button_name', 'edit_count_text'
            ]);

            if ($request->hasFile('image')) {
                if ($whoWeAre->image && File::exists(public_path($whoWeAre->image))) {
                    File::delete(public_path($whoWeAre->image));
                }
                $image = $request->file('image');
                $imageName = time() . '_about_800x533.' . $image->getClientOriginalExtension();
                $image->move(public_path('uploads/about'), $imageName);
                $inputs['image'] = 'uploads/about/' . $imageName;
            }

            $whoWeAre->fill($inputs)->save();

            // ফিচার লিস্ট আপডেট লজিক
            $whoWeAre->listItems()->delete();
            if ($request->has('feature_titles')) {
                foreach ($request->feature_titles as $key => $title) {
                    if (!empty($title)) {
                        $whoWeAre->listItems()->create([
                            'title'             => $title,
                            'icon'              => $request->feature_icons[$key] ?? null,
                            'short_description' => $request->feature_descriptions[$key] ?? null,
                        ]);
                    }
                }
            }

            DB::commit();
            CommonController::addToLog('Who We Are Section Fully Updated');
            return back()->with('success', 'Information and Features updated successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Update failed: ' . $e->getMessage());
        }
    }
}