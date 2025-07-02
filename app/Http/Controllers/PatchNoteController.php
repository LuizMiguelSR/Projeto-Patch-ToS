<?php
namespace App\Http\Controllers;

use App\Models\PatchNote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PatchNoteController extends Controller
{
    public function index(Request $request)
    {
        $query = PatchNote::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('content', 'like', '%' . $search . '%');
        }

        $patchNotes = $query->orderByDesc('date')->get();

        return view('patch-notes.index', compact('patchNotes'));
    }

    public function calendar()
    {
        $dates = PatchNote::select('id', 'date')->get()->map(function($note){
            return [
                'id' => $note->id,
                'date' => \Carbon\Carbon::parse($note->date)->format('Y-m-d'),
                'url' => route('patch-notes.show', ['id' => $note->id]),
            ];
        });

        return view('patch-notes.calendar', ['dates' => $dates]);
    }

    public function edit($id)
    {
        $patchNote = PatchNote::findOrFail($id);
        return view('patch-notes.edit', compact('patchNote'));
    }

    public function show($id)
    {
        $patchNote = PatchNote::findOrFail($id);
        return view('patch-notes.show', compact('patchNote'));
    }

    public function update(Request $request, $id)
    {
        $patchNote = PatchNote::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'content' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $patchNote->content = $request->input('content');
        $patchNote->save();

        return redirect()->route('patch-notes.index')->with('success', 'Patch note atualizado com sucesso!');
    }
}

