<?php

namespace App\Http\Controllers;
use App\Models\Student;
use Illuminate\Http\Request;
use Inertia\Inertia;

class StudentController extends Controller
{
    
   public function index()
{
    return Inertia::render('Groupes', [
        'students' => Student::all(),
    ]);
}

public function store(Request $request)
{
    $data = $request->validate([
        'fullname' => 'required|string|max:255',
        'sexe'     => 'required|in:M,F',
        'niveau'   => 'required|in:1,2,3',
    ]);

    Student::create($data);

    return redirect()->route('students.index');
}
}