<?php

namespace App\Http\Controllers;

use App\Models\Forklift;
use Illuminate\Http\Request;

class ForkliftController extends Controller
{
    public function index()
    {
        $forklifts = Forklift::all();
        return "Done";
        return View::make('frontend.forklifts.index')
            ->with('forklifts', $forklifts);

    }

    public function create()
    {
        return view('forklifts.create');
    }

    public function store(Request $request)
    {
        Forklift::create($request->all());
        return redirect()->route('frontend.forklifts.index')
            ->with('success', 'Forklift created successfully.');

    }

    public function show($id)
    {
        $forklift = Forklift::find($id);
        return view('forklifts.show', compact('forklift'));
    }

    public function edit($id)
    {
        $forklift = Forklift::find($id);
        return view('forklifts.edit', compact('forklift'));
    }

    public function update(Request $request, $id)
    {
        $forklift = Forklift::find($id);
        $forklift->update($request->all());
        return redirect()->route('forklifts.index')
            ->with('success', 'Forklift updated successfully.');

    }

    public function destroy($id)
    {
        $forklift = Forklift::find($id);
        $forklift->delete();
        return redirect()->route('forklifts.index')
            ->with('success', 'Forklift deleted successfully');

    }
}
