<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Course;
use App\Models\Package;
use App\Models\Subscription;

class DashboardController extends Controller
{
    public function index()
    {
        $totalUsers = User::where('is_deleted', 0)->count();
        $totalCourses = Course::count();
        $totalPackages = Package::count();
        $activeSubscriptions = Subscription::where('is_active', true)->where('status', 'active')->count();
        $recentSubscriptions = Subscription::with(['user', 'package'])
            ->orderBy('created_at', 'desc')
            ->take(6)
            ->get();

        return view('dashboard.dashboard', compact(
            'totalUsers',
            'totalCourses',
            'totalPackages',
            'activeSubscriptions',
            'recentSubscriptions'
        ));
    }
}
