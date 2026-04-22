<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\Arazi;
use App\Models\Customer;
use App\Models\Kisan;
use App\Models\Payment;
use App\Services\RegistryLifecycleService;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(RegistryLifecycleService $registryLifecycleService): View
    {
        $registryLifecycleService->expirePendingRegistries();

        return view('dashboard', [
            'totalKisans' => Kisan::count(),
            'totalArazis' => Arazi::count(),
            'availableArazis' => Arazi::where('status', 'available')->count(),
            'soldArazis' => Arazi::where('status', 'sold')->count(),
            'totalCustomers' => Customer::count(),
            'totalAgents' => Agent::count(),
            'recentPayments' => Payment::with(['customer', 'kisan', 'registry.arazi'])
                ->latest()
                ->take(5)
                ->get(),
        ]);
    }
}
