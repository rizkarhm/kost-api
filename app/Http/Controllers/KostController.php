<?php

namespace App\Http\Controllers;

use App\Models\Kost;
use Illuminate\Http\Request;
use App\Http\Requests\KostRequest;

class KostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Kost $kost)
    {
        // dd($kost->all());
        $kost = Kost::paginate(10);
        return view('kosts.index', [
            'kost' => $kost
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Kost $kost)
    {
        return view('kosts.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $data['picturePath'] = $request->file('picturePath')->store('assets/kost', 'public');
        Kost::create($data);
        return redirect()->route('kosts.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Kost $kost)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Kost $kost)
    {
        return view('kosts.edit',[
            'item' => $kost
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Kost $kost)
    {
        $data = $request->all();
        if($request->file('picturePath'))
        {
            $data['picturePath'] = $request->file('picturePath')->store('assets/kost', 'public');
        }

        $kost->update($data);

        return redirect()->route('kosts.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Kost $kost)
    {
        $kost->delete();
        return redirect()->route('kosts.index');
    }
}
