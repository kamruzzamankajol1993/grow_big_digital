<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\GrowBigContact;
use App\Models\ContactHeader;
use App\Http\Controllers\Admin\CommonController;
use DB;
use Log;
use App\Models\GrowBigQuickAudit;
class ContactMessageController extends Controller
{

// কুইক অডিট লিস্ট
// Quick Audit Index
public function quickAuditIndex() {
    $audits = \App\Models\GrowBigQuickAudit::latest()->paginate(10);
    return view('admin.contact.quick_audit_index', compact('audits'));
}

// Quick Audit Single Delete
public function quickAuditDestroy($id) {
    \App\Models\GrowBigQuickAudit::findOrFail($id)->delete();
    return back()->with('success', 'Audit deleted successfully');
}

// Quick Audit Multi-Delete
public function quickAuditMultiDelete(Request $request) {
    \App\Models\GrowBigQuickAudit::whereIn('id', $request->ids)->delete();
    return response()->json(['status' => 'success', 'message' => 'Selected audits deleted']);
}

// কুইক অডিট ভিউ ও স্ট্যাটাস আপডেট
public function quickAuditView($id) {
    $audit = GrowBigQuickAudit::findOrFail($id);
    if ($audit->is_read == 0) {
        $audit->update(['is_read' => 1]);
    }
    return response()->json(['status' => 'success', 'data' => $audit]);
}
    /**
     * ইউজারের পাঠানো মেসেজ লিস্ট প্রদর্শন
     */
    public function index()
{
    // ১0 টি মেসেজ পর পর পেজ পরিবর্তন হবে (Pagination)
    $messages = GrowBigContact::latest()->paginate(10); 
    return view('admin.contact.index', compact('messages'));
}

    /**
     * মডেল ভিউ এর জন্য নির্দিষ্ট মেসেজ ডাটা রিটার্ন এবং স্ট্যাটাস আপডেট
     */
    public function view($id)
    {
        try {
            $message = GrowBigContact::findOrFail($id);
            
            // মেসেজ ভিউ করলে is_read কলাম আপডেট হবে (০ থেকে ১ হবে)
            if ($message->is_read == 0) {
                $message->update(['is_read' => 1]);
            }

            return response()->json([
                'status' => 'success',
                'data'   => $message
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Message not found'
            ], 404);
        }
    }

    /**
     * সিঙ্গেল মেসেজ ডিলিট
     */
    public function destroy($id)
    {
        try {
            $message = GrowBigContact::findOrFail($id);
            $message->delete();

            CommonController::addToLog('Contact message deleted. ID: ' . $id);
            return back()->with('success', 'Message deleted successfully!');
        } catch (\Exception $e) {
            Log::error('Contact Delete Error: ' . $e->getMessage());
            return back()->with('error', 'Failed to delete message.');
        }
    }

    /**
     * মাল্টিপল মেসেজ ডিলিট (Checkbox selection)
     */
    public function multiDelete(Request $request)
    {
        $ids = $request->ids;
        if (!empty($ids)) {
            try {
                GrowBigContact::whereIn('id', $ids)->delete();
                CommonController::addToLog('Multiple contact messages deleted.');
                
                return response()->json([
                    'status' => 'success',
                    'message' => 'Selected messages deleted successfully!'
                ]);
            } catch (\Exception $e) {
                Log::error('Multi-Delete Error: ' . $e->getMessage());
                return response()->json(['status' => 'error', 'message' => 'Something went wrong!'], 500);
            }
        }
        return response()->json(['status' => 'error', 'message' => 'No messages selected!'], 400);
    }

    /**
     * কন্টাক্ট হেডার সেটিংস পেজ (ট্যাব ২)
     */
    public function headerSettings()
    {
        $header = ContactHeader::first();
        return view('admin.contact.header_settings', compact('header'));
    }

    /**
     * কন্টাক্ট হেডার আপডেট বা স্টোর
     */
    public function headerUpdate(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
        ]);

        try {
            DB::beginTransaction();

            // ডাটাবেজে রো না থাকলে নতুন তৈরি করবে, থাকলে আপডেট করবে
            $header = ContactHeader::first() ?? new ContactHeader();
            
            $header->title = $request->title;
            $header->subtitle_one = $request->subtitle_one;
            $header->subtitle_two = $request->subtitle_two;
            $header->button_name = $request->button_name;
            $header->short_description = $request->short_description;
            
            $header->save();

            CommonController::addToLog('Contact Header Configuration Updated');

            DB::commit();
            return back()->with('success', 'Contact header settings updated!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Contact Header Update Error: ' . $e->getMessage());
            return back()->with('error', 'Something went wrong! Check error logs.');
        }
    }
}