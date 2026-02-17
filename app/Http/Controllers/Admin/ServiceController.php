<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\GrowBigService;
use App\Models\ServiceHeader;
use App\Http\Controllers\Admin\CommonController;
use Illuminate\Support\Facades\File;
use DB;

class ServiceController extends Controller
{
    /**
     * সকল সার্ভিসের তালিকা (প্যারেন্ট এবং চাইল্ডসহ)
     */
    public function index()
    {
        $services = GrowBigService::with('parent')->latest()->paginate(15);
        return view('admin.service.index', compact('services'));
    }

    /**
     * নতুন সার্ভিস তৈরির ফর্ম
     */
    public function create()
    {
        // শুধুমাত্র মেইন সার্ভিসগুলোকে নেওয়া হচ্ছে যাতে তাদের আন্ডারে সাব-সার্ভিস অ্যাড করা যায়
        $parentServices = GrowBigService::whereNull('parent_id')->get();
        return view('admin.service.create', compact('parentServices'));
    }

    /**
     * ডাটাবেজে সার্ভিস সেভ করা
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'icon' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:1024',
            'status' => 'required|in:1,0',
        ]);

        try {
            $data = $request->only(['parent_id', 'name', 'short_description', 'status']);

            // আইকন আপলোড (যদি থাকে)
            if ($request->hasFile('icon')) {
                $image = $request->file('icon');
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('uploads/services'), $imageName);
                $data['icon'] = 'uploads/services/' . $imageName;
            }

            GrowBigService::create($data);

            CommonController::addToLog('New Service Created: ' . $request->name);
            return redirect()->route('service.index')->with('success', 'Service added successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    /**
     * এডিট ফর্ম
     */
    public function edit($id)
    {
        $service = GrowBigService::findOrFail($id);
        $parentServices = GrowBigService::whereNull('parent_id')
                            ->where('id', '!=', $id) // নিজের আন্ডারে নিজে যেন চাইল্ড না হয়
                            ->get();
        return view('admin.service.edit', compact('service', 'parentServices'));
    }

    /**
     * ডাটা আপডেট করা
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|max:255',
            'status' => 'required|in:1,0',
        ]);

        try {
            $service = GrowBigService::findOrFail($id);
            $data = $request->only(['parent_id', 'name', 'short_description', 'status']);

            if ($request->hasFile('icon')) {
                if (File::exists(public_path($service->icon))) {
                    File::delete(public_path($service->icon));
                }
                $image = $request->file('icon');
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('uploads/services'), $imageName);
                $data['icon'] = 'uploads/services/' . $imageName;
            }

            $service->update($data);

            CommonController::addToLog('Service Updated ID: ' . $id);
            return redirect()->route('service.index')->with('success', 'Service updated successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Update failed!');
        }
    }

    /**
     * সার্ভিস ডিলিট করা
     */
    public function destroy($id)
    {
        try {
            $service = GrowBigService::findOrFail($id);
            
            // আইকন ডিলিট করা
            if (File::exists(public_path($service->icon))) {
                File::delete(public_path($service->icon));
            }

            // সাব-সার্ভিস থাকলে সেগুলোকেও ডিলিট করা (Optional: Depend on your DB Logic)
            $service->children()->delete(); 
            $service->delete();

            CommonController::addToLog('Service Deleted ID: ' . $id);
            return back()->with('success', 'Service deleted successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Delete failed!');
        }
    }

    /**
     * হেডার সেটিংস ভিউ
     */
    public function headerSettings()
    {
        $header = ServiceHeader::first();
        return view('admin.service.header_settings', compact('header'));
    }

    /**
     * হেডার আপডেট লজিক
     */
    public function headerUpdate(Request $request)
    {
        $request->validate(['title' => 'required|max:255']);

        try {
            $header = ServiceHeader::first() ?? new ServiceHeader();
            $header->title = $request->title;
            $header->subtitle_one = $request->subtitle_one;
            $header->subtitle_two = $request->subtitle_two;
            $header->save();

            CommonController::addToLog('Service Header Updated');
            return back()->with('success', 'Service header updated successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Header update failed!');
        }
    }
}