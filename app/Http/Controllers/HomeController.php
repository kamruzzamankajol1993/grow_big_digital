<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GrowBigService;
use App\Models\Portfolio;
use App\Models\GrowBigWhoWeAre;
use App\Models\GrowBigHero;
use App\Models\GrowBigContact;
use App\Models\GrowBigQuickAudit;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        // ১. জেনারেল কাউন্টার্স
        $data['totalServices'] = GrowBigService::count();
        $data['totalPortfolio'] = Portfolio::count();
        $data['totalMessages'] = GrowBigContact::count();
        $data['totalAudits'] = GrowBigQuickAudit::count();

        // ২. নোটিফিকেশন কাউন্ট (আনরিড)
        $data['unreadMessages'] = GrowBigContact::where('is_read', 0)->count();
        $data['unreadAudits'] = GrowBigQuickAudit::where('is_read', 0)->count();

        // ৩. পোর্টফোলিও স্ট্যাটস (সার্ভিস অনুযায়ী)
        $portfolioStats = Portfolio::select('service_id', DB::raw('count(*) as total'))
            ->with('service')
            ->groupBy('service_id')
            ->get();
        $data['pLabels'] = $portfolioStats->map(fn($item) => $item->service->name ?? 'N/A');
        $data['pValues'] = $portfolioStats->pluck('total');

        // ৪. লেটেস্ট ডাটা প্রিভিউ (টেবিল থেকে সরাসরি)
        $data['recentPortfolio'] = Portfolio::with('service')->latest()->take(5)->get();
        $data['recentAudits'] = GrowBigQuickAudit::latest()->take(5)->get();
        
        // ৫. হিরো এবং হু উই আর স্ট্যাটাস চেক
        $data['heroTitle'] = GrowBigHero::value('main_title') ?? 'Not Set';
        $data['whoWeAreTitle'] = GrowBigWhoWeAre::value('title') ?? 'Not Set';

        return view('admin.dashboard.index', $data);
    }
}