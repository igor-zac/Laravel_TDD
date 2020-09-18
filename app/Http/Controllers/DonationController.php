<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DonationController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        $this->authorizeResource(Donation::class, 'donation');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $donations = Donation::where('user_id', Auth::id())->get();

        return view('donations.donation-list', compact('donations'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Project $project
     * @return \Illuminate\Http\Response
     */
    public function create(Project $project)
    {
        return view('donations.create-donation', compact('project'));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Project $project
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Project $project)
    {
        $donation = Donation::create([
            'amount' => $request->input('amount'),
            'project_id' => $project->id,
            'user_id' => Auth::id(),
            'validated' => false
        ]);

        return response()->view('donations.create-donation_recap', compact('project', 'donation'), 201);

    }

    /**
     * Display the specified resource.
     *
     * @param Donation $donation
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(Donation $donation)
    {
        return view('donations.donation-detail', compact('donation'));

    }
}
