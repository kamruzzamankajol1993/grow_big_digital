<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Portfolio;
use App\Models\PortfolioHeader;
use App\Models\GrowBigService;
use App\Http\Controllers\Admin\CommonController;
use Illuminate\Support\Facades\File;

class PortfolioController extends Controller
{
    /**
     * পোর্টফোলিও প্রজেক্ট লিস্ট
     */
    public function index()
    {
        // সার্ভিসের নামসহ পোর্টফোলিও ডাটা নিয়ে আসা
        $portfolios = Portfolio::with('service')->latest()->paginate(15);
        return view('admin.portfolio.index', compact('portfolios'));
    }

    /**
     * নতুন প্রজেক্ট তৈরির ফর্ম
     */
    public function create()
    {
        // ড্রপডাউনের জন্য একটিভ সার্ভিসগুলো নেওয়া হচ্ছে
        $services = GrowBigService::where('status', 1)->get();
        return view('admin.portfolio.create', compact('services'));
    }

    /**
     * ডাটাবেজে প্রজেক্ট সেভ করা
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'service_id' => 'required|exists:grow_big_services,id',
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            'video_type' => 'nullable|in:link,file',
        ]);

        try {
            $data = $request->only(['service_id', 'title', 'subtitle', 'description', 'video_type']);
            
            // সরাসরি আইফ্রেম (iframe) কোড ইনপুট হিসেবে নেওয়া
            $data['video_link'] = $request->video_link; 

            // থাম্বনেইল ইমেজ আপলোড
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '_portfolio.' . $image->getClientOriginalExtension();
                $image->move(public_path('uploads/portfolio'), $imageName);
                $data['image'] = 'uploads/portfolio/' . $imageName;
            }

            // যদি ভিডিও ফাইল আপলোড করতে চায়
            if ($request->video_type == 'file' && $request->hasFile('video')) {
                $video = $request->file('video');
                $videoName = time() . '_video.' . $video->getClientOriginalExtension();
                $video->move(public_path('uploads/portfolio/videos'), $videoName);
                $data['video'] = 'uploads/portfolio/videos/' . $videoName;
            }

            Portfolio::create($data);

            CommonController::addToLog('New Portfolio Added: ' . $request->title);
            return redirect()->route('portfolio.index')->with('success', 'Project added successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * এডিট ফর্ম
     */
    public function edit($id)
    {
        $portfolio = Portfolio::findOrFail($id);
        $services = GrowBigService::where('status', 1)->get();
        return view('admin.portfolio.edit', compact('portfolio', 'services'));
    }

    /**
     * ডাটা আপডেট করা
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|max:255',
            'service_id' => 'required|exists:grow_big_services,id',
        ]);

        try {
            $portfolio = Portfolio::findOrFail($id);
            $data = $request->only(['service_id', 'title', 'subtitle', 'description', 'video_type']);
            $data['video_link'] = $request->video_link;

            // ইমেজ আপডেট
            if ($request->hasFile('image')) {
                if (File::exists(public_path($portfolio->image))) {
                    File::delete(public_path($portfolio->image));
                }
                $image = $request->file('image');
                $imageName = time() . '_portfolio.' . $image->getClientOriginalExtension();
                $image->move(public_path('uploads/portfolio'), $imageName);
                $data['image'] = 'uploads/portfolio/' . $imageName;
            }

            // ভিডিও ফাইল আপডেট
            if ($request->video_type == 'file' && $request->hasFile('video')) {
                if ($portfolio->video && File::exists(public_path($portfolio->video))) {
                    File::delete(public_path($portfolio->video));
                }
                $video = $request->file('video');
                $videoName = time() . '_video.' . $video->getClientOriginalExtension();
                $video->move(public_path('uploads/portfolio/videos'), $videoName);
                $data['video'] = 'uploads/portfolio/videos/' . $videoName;
            }

            $portfolio->update($data);

            CommonController::addToLog('Portfolio Updated ID: ' . $id);
            return redirect()->route('portfolio.index')->with('success', 'Portfolio updated successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Update failed!');
        }
    }

    /**
     * প্রজেক্ট ডিলিট করা
     */
    public function destroy($id)
    {
        try {
            $portfolio = Portfolio::findOrFail($id);
            if (File::exists(public_path($portfolio->image))) {
                File::delete(public_path($portfolio->image));
            }
            if ($portfolio->video && File::exists(public_path($portfolio->video))) {
                File::delete(public_path($portfolio->video));
            }
            $portfolio->delete();

            CommonController::addToLog('Portfolio Deleted ID: ' . $id);
            return back()->with('success', 'Project deleted successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Delete failed!');
        }
    }

    /**
     * হেডার সেটিংস
     */
    public function headerSettings()
    {
        $header = PortfolioHeader::first();
        return view('admin.portfolio.header_settings', compact('header'));
    }

    public function headerUpdate(Request $request)
    {
        $request->validate(['title' => 'required|max:255']);
        $header = PortfolioHeader::first() ?? new PortfolioHeader();
        $header->fill($request->all())->save();

        CommonController::addToLog('Portfolio Header Updated');
        return back()->with('success', 'Header updated successfully!');
    }
}