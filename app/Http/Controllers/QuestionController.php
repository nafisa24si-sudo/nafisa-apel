<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class QuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'nama'       => 'required|max:10',
            'email'      => ['required', 'email'],
            'pertanyaan' => 'required|max:300|min:8',
        ], [

            'nama.required' => 'Nama wajib diisi',
            'email.email'   => 'Format email tidak valid',

        ]);

        $data['nama']       = $request->nama;
        $data['email']      = $request->email;
        $data['pertanyaan'] = $request->pertanyaan;

        //return view('home-question-respon', $data);
        // return view('home-question-respon', $data);
        return redirect()->route('home')->with('info', 'Terimakasih <b>' . $data['nama'] .
            '</b> Data pertanyaan berhasil di inputkan, Silahkan cek email <b>'
            . $data['email'] . '</b>');
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
