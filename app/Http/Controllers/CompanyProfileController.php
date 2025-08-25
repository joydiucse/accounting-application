<?php

namespace App\Http\Controllers;

use App\Models\CompanyProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CompanyProfileController extends Controller
{
    /**
     * Display the company profile.
     */
    public function index()
    {
        $companyProfile = CompanyProfile::first();
        return view('company-profile.index', compact('companyProfile'));
    }

    /**
     * Show the form for creating a new company profile.
     */
    public function create()
    {
        return view('company-profile.create');
    }

    /**
     * Store a newly created company profile.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->only(['name', 'address', 'phone', 'email', 'website']);

        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('logos', 'public');
        }

        CompanyProfile::create($data);

        return redirect()->route('company-profile.index')
            ->with('success', 'Company profile created successfully.');
    }

    /**
     * Show the form for editing the company profile.
     */
    public function edit()
    {
        $companyProfile = CompanyProfile::firstOrFail();
        return view('company-profile.edit', compact('companyProfile'));
    }

    /**
     * Update the company profile.
     */
    public function update(Request $request)
    {
        $companyProfile = CompanyProfile::firstOrFail();

        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->only(['name', 'address', 'phone', 'email', 'website']);

        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            if ($companyProfile->logo) {
                Storage::disk('public')->delete($companyProfile->logo);
            }
            $data['logo'] = $request->file('logo')->store('logos', 'public');
        }

        $companyProfile->update($data);

        return redirect()->route('company-profile.index')
            ->with('success', 'Company profile updated successfully.');
    }
}