<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\GrowBigTeam;
use App\Models\TeamHeader;
use App\Models\TeamSocialLink;
use App\Http\Controllers\Admin\CommonController;
use Illuminate\Support\Facades\File;
use DB;
use Log;

class TeamController extends Controller
{
    /**
     * টিম মেম্বারদের তালিকা প্রদর্শন
     */
    public function index()
    {
        $members = GrowBigTeam::with('socialLinks')->latest()->paginate(10);
        return view('admin.team.index', compact('members'));
    }

    /**
     * নতুন মেম্বার তৈরির ফর্ম
     */
    public function create()
    {
        return view('admin.team.create');
    }

    /**
     * ডাটাবেজে মেম্বার সেভ করা
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'designation' => 'required|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            'skills' => 'required|array',
        ]);

        try {
            DB::beginTransaction();

            $data = $request->only(['name', 'designation', 'skills']);

            // ইমেজ হ্যান্ডেলিং
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('uploads/team'), $imageName);
                $data['image'] = 'uploads/team/' . $imageName;
            }

            // টিম মেম্বার তৈরি
            $team = GrowBigTeam::create($data);

            // সোশ্যাল লিঙ্ক সেভ করা (যদি থাকে)
            if ($request->social_titles) {
                foreach ($request->social_titles as $key => $title) {
                    if (!empty($title) && !empty($request->social_links[$key])) {
                        TeamSocialLink::create([
                            'team_id' => $team->id,
                            'title'   => $title,
                            'link'    => $request->social_links[$key]
                        ]);
                    }
                }
            }

            DB::commit();
            CommonController::addToLog('New Team Member Added: ' . $request->name);

            return redirect()->route('team.index')->with('success', 'Member added successfully!');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Team Store Error: ' . $e->getMessage());
            return back()->with('error', 'Something went wrong!');
        }
    }

    /**
     * এডিট ফর্ম
     */
    public function edit($id)
    {
        $member = GrowBigTeam::with('socialLinks')->findOrFail($id);
        return view('admin.team.edit', compact('member'));
    }

    /**
     * ডাটা আপডেট করা
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|max:255',
            'designation' => 'required|max:255',
            'skills' => 'required|array',
        ]);

        try {
            DB::beginTransaction();
            $member = GrowBigTeam::findOrFail($id);
            $data = $request->only(['name', 'designation', 'skills']);

            if ($request->hasFile('image')) {
                if (File::exists(public_path($member->image))) {
                    File::delete(public_path($member->image));
                }
                $image = $request->file('image');
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('uploads/team'), $imageName);
                $data['image'] = 'uploads/team/' . $imageName;
            }

            $member->update($data);

            // পুরানো সোশ্যাল লিঙ্ক ডিলিট করে নতুনগুলো অ্যাড করা
            TeamSocialLink::where('team_id', $id)->delete();
            if ($request->social_titles) {
                foreach ($request->social_titles as $key => $title) {
                    if (!empty($title) && !empty($request->social_links[$key])) {
                        TeamSocialLink::create([
                            'team_id' => $member->id,
                            'title'   => $title,
                            'link'    => $request->social_links[$key]
                        ]);
                    }
                }
            }

            DB::commit();
            CommonController::addToLog('Team Member Updated ID: ' . $id);

            return redirect()->route('team.index')->with('success', 'Member updated successfully!');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Update failed!');
        }
    }

    /**
     * মেম্বার ডিলিট করা
     */
    public function destroy($id)
    {
        try {
            $member = GrowBigTeam::findOrFail($id);
            if (File::exists(public_path($member->image))) {
                File::delete(public_path($member->image));
            }
            $member->delete(); // On Delete Cascade থাকলে সোশ্যাল লিঙ্ক অটো ডিলিট হবে

            CommonController::addToLog('Team Member Deleted ID: ' . $id);
            return back()->with('success', 'Member deleted successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Delete failed!');
        }
    }

    /**
     * হেডার সেটিংস ভিউ
     */
    public function headerSettings()
    {
        $header = TeamHeader::first();
        return view('admin.team.header_settings', compact('header'));
    }
public function show($id) {
    $member = GrowBigTeam::with('socialLinks')->findOrFail($id);
    return view('admin.team.show', compact('member'));
}
    /**
     * হেডার আপডেট
     */
    public function headerUpdate(Request $request)
    {
        $request->validate(['title' => 'required|max:255']);

        try {
            $header = TeamHeader::first() ?? new TeamHeader();
            $header->title = $request->title;
            $header->subtitle_one = $request->subtitle_one;
            $header->subtitle_two = $request->subtitle_two;
            $header->save();

            CommonController::addToLog('Team Header Updated');
            return back()->with('success', 'Team header updated!');
        } catch (\Exception $e) {
            return back()->with('error', 'Header update failed!');
        }
    }
}