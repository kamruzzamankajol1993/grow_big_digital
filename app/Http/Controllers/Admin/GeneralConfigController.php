<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SiteConfig;
use Intervention\Image\Laravel\Facades\Image;
use DB;
use Log; // লারাভেল এরর লগের জন্য
use App\Http\Controllers\Admin\CommonController; // কাস্টম লগের জন্য

class GeneralConfigController extends Controller
{
    public function index()
    {
        $config = SiteConfig::first();
        return view('admin.setting.general_config', compact('config'));
    }

    public function update(Request $request)
    {
        try {
            DB::beginTransaction();

            $config = SiteConfig::first() ?? new SiteConfig();
            
            // ইমেজ ছাড়া বাকি সব ইনপুট
            $input = $request->except(['logo', 'mobile_version_logo', 'icon', 'og_image']);

            // আপনার দেওয়া নির্দিষ্ট ইমেজ আপলোড লজিক
            $uploadImage = function($file, $prefix, $width, $height) {
                $imageName = $prefix . '_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $directory = 'public/uploads/config/';
                if (!file_exists($directory)) {
                    mkdir($directory, 0777, true);
                }
                
                $imageUrl = $directory . $imageName;
                $img = Image::read($file)->resize($width, $height);
                $img->save($imageUrl);
                return $imageUrl;
            };

            // ইমেজ প্রসেসিং
            if ($request->hasFile('logo')) {
                $input['logo'] = $uploadImage($request->file('logo'), 'logo', 300, 150);
            }
            if ($request->hasFile('mobile_version_logo')) {
                $input['mobile_version_logo'] = $uploadImage($request->file('mobile_version_logo'), 'mobile_logo', 300, 150);
            }
            if ($request->hasFile('icon')) {
                $input['icon'] = $uploadImage($request->file('icon'), 'icon', 60, 60);
            }
            if ($request->hasFile('og_image')) {
                $input['og_image'] = $uploadImage($request->file('og_image'), 'og', 300, 150);
            }

            // ডাটা আপডেট
            $config->fill($input)->save();

            // ১. কাস্টম লগ (CommonController এর মাধ্যমে ডাটাবেজে)
            CommonController::addToLog('General Configuration Updated by ' . auth()->user()->name);

            DB::commit();
            return back()->with('success', 'Configuration updated successfully!');

        } catch (\Exception $e) {
            DB::rollBack();


            return $e->getMessage(); // ডেভেলপমেন্টের জন্য সরাসরি এরর দেখানো, প্রোডাকশনে এই লাইনটি রিমুভ করুন।
            // ২. লারাভেল এরর লগ (storage/logs/laravel.log ফাইলে সেভ হবে)
            Log::error('General Config Update Error: ' . $e->getMessage(), [
                'user_id' => auth()->user()->id,
                'input' => $request->all()
            ]);

            return back()->with('error', 'Something went wrong! Check laravel log for details.');
        }
    }
}