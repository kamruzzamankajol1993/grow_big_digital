<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\GrowBigTestimonial;
use App\Models\TestimonialHeader;
use App\Http\Controllers\Admin\CommonController;
use Illuminate\Support\Facades\File;
use DB;
use Log;

class TestimonialController extends Controller
{
    /**
     * টেস্টমোনিয়াল লিস্ট প্রদর্শন
     */
    public function index()
    {
        $testimonials = GrowBigTestimonial::latest()->paginate(10);
        return view('admin.testimonial.index', compact('testimonials'));
    }

    /**
     * নতুন টেস্টমোনিয়াল তৈরির ফর্ম
     */
    public function create()
    {
        return view('admin.testimonial.create');
    }

    /**
     * নতুন টেস্টমোনিয়াল ডাটাবেজে সেভ করা
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'designation' => 'required|max:255',
            'short_description' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        try {
            $data = $request->except('image');

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('uploads/testimonials'), $imageName);
                $data['image'] = 'uploads/testimonials/' . $imageName;
            }

            GrowBigTestimonial::create($data);
            CommonController::addToLog('New Testimonial Added: ' . $request->name);

            return redirect()->route('testimonial.index')->with('success', 'Testimonial created successfully!');
        } catch (\Exception $e) {
            Log::error('Testimonial Store Error: ' . $e->getMessage());
            return back()->with('error', 'Something went wrong!');
        }
    }

    /**
     * টেস্টমোনিয়াল এডিট ফর্ম
     */
    public function edit($id)
    {
        $testimonial = GrowBigTestimonial::findOrFail($id);
        return view('admin.testimonial.edit', compact('testimonial'));
    }

    /**
     * টেস্টমোনিয়াল আপডেট করা
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|max:255',
            'designation' => 'required|max:255',
            'short_description' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        try {
            $testimonial = GrowBigTestimonial::findOrFail($id);
            $data = $request->except('image');

            if ($request->hasFile('image')) {
                // পুরানো ইমেজ ডিলিট করা
                if (File::exists(public_path($testimonial->image))) {
                    File::delete(public_path($testimonial->image));
                }

                $image = $request->file('image');
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('uploads/testimonials'), $imageName);
                $data['image'] = 'uploads/testimonials/' . $imageName;
            }

            $testimonial->update($data);
            CommonController::addToLog('Testimonial Updated ID: ' . $id);

            return redirect()->route('testimonial.index')->with('success', 'Testimonial updated successfully!');
        } catch (\Exception $e) {
            Log::error('Testimonial Update Error: ' . $e->getMessage());
            return back()->with('error', 'Update failed!');
        }
    }

    /**
     * টেস্টমোনিয়াল ডিলিট করা
     */
    public function destroy($id)
    {
        try {
            $testimonial = GrowBigTestimonial::findOrFail($id);
            if (File::exists(public_path($testimonial->image))) {
                File::delete(public_path($testimonial->image));
            }
            $testimonial->delete();

            CommonController::addToLog('Testimonial Deleted ID: ' . $id);
            return back()->with('success', 'Testimonial deleted successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Delete failed!');
        }
    }

    /**
     * টেস্টমোনিয়াল হেডার সেটিংস ভিউ (ট্যাব ২)
     */
    public function headerSettings()
    {
        $header = TestimonialHeader::first();
        return view('admin.testimonial.header_settings', compact('header'));
    }

    /**
     * টেস্টমোনিয়াল হেডার আপডেট
     */
    public function headerUpdate(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
        ]);

        try {
            $header = TestimonialHeader::first() ?? new TestimonialHeader();
            $header->title = $request->title;
            $header->subtitle_one = $request->subtitle_one;
            $header->subtitle_two = $request->subtitle_two;
            $header->save();

            CommonController::addToLog('Testimonial Header Updated');
            return back()->with('success', 'Header settings updated!');
        } catch (\Exception $e) {
            return back()->with('error', 'Header update failed!');
        }
    }
}