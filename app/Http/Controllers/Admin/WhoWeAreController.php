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
    /**
     * Display the Who We Are settings page.
     */
    public function index()
    {
        // Fetch data with all list items (features)
        $data = GrowBigWhoWeAre::with('listItems')->first();
        return view('admin.whoweare.index', compact('data'));
    }

    /**
     * Update Who We Are information and multiple feature list items.
     */
    public function update(Request $request)
    {
        // Validation for main section and list arrays
        $request->validate([
            'title' => 'required|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'feature_titles' => 'nullable|array',
            'feature_icons' => 'nullable|array',
            'feature_descriptions' => 'nullable|array',
        ]);

        try {
            DB::beginTransaction();

            $whoWeAre = GrowBigWhoWeAre::first() ?? new GrowBigWhoWeAre();
            
            // Map main section fields
            $inputs = $request->only([
                'title', 'subtitle_one', 'subtitle_two', 'subtitle_three', 
                'short_description', 'button_name', 'edit_count_text'
            ]);

            // Handle main image upload (Recommended size: 800x533px)
            if ($request->hasFile('image')) {
                if ($whoWeAre->image && File::exists(public_path($whoWeAre->image))) {
                    File::delete(public_path($whoWeAre->image));
                }
                
                $image = $request->file('image');
                $imageName = time() . '_who_we_are_800x533.' . $image->getClientOriginalExtension();
                $image->move(public_path('uploads/about'), $imageName);
                $inputs['image'] = 'uploads/about/' . $imageName;
            }

            // Save or Update the main record
            $whoWeAre->fill($inputs)->save();

            // Syncing the Feature List (WhoWeAreList)
            // We delete existing items to perform a clean sync based on the new array input
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
            
            // Log the activity using your CommonController
            CommonController::addToLog('Who We Are section and feature lists updated.');

            return back()->with('success', 'Who We Are content and features updated successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }
}