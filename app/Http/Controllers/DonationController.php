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
     * @param int $projectId
     * @return \Illuminate\Http\Response
     */
    public function create($projectId)
    {
        $project = Project::find($projectId);

        return view('donations.create-donation', compact('project'));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param int $projectId
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $projectId)
    {
        $donation = new Donation();
        $project = Project::find($projectId);

        $donation->amount = $request->input('amount');
        $donation->project_id = $project->id;
        $donation->user_id = Auth::id();
        $donation->isValid = true;

        $donation->save();

        return response()->view('donations.create-donation_recap', compact('project', 'donation'), 201);

    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show($id)
    {
        $donation = Donation::find($id);

        return view('donations.donation-detail', compact('donation'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
