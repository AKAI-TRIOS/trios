<?php
/**
 * Created by PhpStorm.
 * User: Denis
 * Date: 19.12.2016
 * Time: 00:31
 */
namespace App\Http\Controllers;

use App\Trio;
use App\TrioChange;
use App\User;
use Illuminate\Http\Request;
use \App\Notifications\TrioUpdated;
use \Validator;

class TriosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $paginate = 15;
        $filter = $request->query('show');

        if($filter == 'active') {
            $trios = Trio::where('active', 1)->paginate($paginate);
        } else if($filter == 'inactive') {
            $trios = Trio::where('active', 0)->paginate($paginate);
        } else {
            $trios = Trio::paginate($paginate);
        }

        return view('pages.admin.trios.index', compact(['trios', 'filter']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('pages.admin.trios.create');
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request  $r
     * @return Response
     */
    public function store(Request $r)
    {
        $v = Validator::make($r->all(), [
            's1' => 'required',
            's2' => 'required',
            's3' => 'required',
            'e1' => 'required',
            'e2' => 'required',
            'e3' => 'required',
            'a' => 'required'
        ]);
        if($v->fails()) {
            return redirect('/trios/create');
        } else {
            $trios = new Trio;
            $trios->sentence1 = $r->input('s1');
            $trios->sentence2 = $r->input('s2');
            $trios->sentence3 = $r->input('s3');
            $trios->explanation1 = $r->input('e1');
            $trios->explanation2 = $r->input('e2');
            $trios->explanation3 = $r->input('e3');
            $trios->answer = $r->input('a');
            $trios->save();
            return redirect('/trios/create')->with('msg', 'Success');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $trio = Trio::findOrFail($id);
        return view('pages.admin.trios.show')->with('trio', $trio);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $trio = Trio::findOrFail($id);
        return view('pages.admin.trios.edit')->with('trio', $trio);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, Trio $trio)
    {
        foreach ($trio->getFillable() as $field) {
            $this->registerChangeIfNeeded($request, $trio, $field);
            $trio->$field = $request->input($field, $trio->$field);
        }

        $active = $request->has('active');
        if($active !== (bool)$trio->active) {
            $this->registerChange($request, $trio, 'active', $trio->active, $active);
        }

        $trio->active = $active;
        $trio->save();

        //Notify to Slack channel
        $user  = User::whereId(1)->first();
        $user->notify(new TrioUpdated($trio));


        return redirect("/admin/trios/{$trio->id}");
    }

    /**
     * Check if change occurred and register it.
     *
     * @param $request
     * @param $trio
     * @param $field
     */
    private function registerChangeIfNeeded(Request $request, Trio $trio, $field)
    {
        if($trio[$field] !== $request->input($field)) {
            $trioChange = new TrioChange;

            $trioChange->trio_id = $trio->id;
            // Set to 0 if user is not logged in
            $trioChange->user_id = $request->user() ? $request->user()->id : 0;
            $trioChange->field_name = $field;
            $trioChange->before = $trio[$field];
            $trioChange->after = $request->input($field);

            $trioChange->save();
        }
    }

    private function registerChange(Request $request, Trio $trio, $field, $before, $after) {
        $trioChange = new TrioChange;

        $trioChange->trio_id = $trio->id;
        $trioChange->user_id = $request->user() ? $request->user()->id : 0;
        $trioChange->field_name = $field;
        $trioChange->before = $before;
        $trioChange->after = $after;

        $trioChange->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy(Request $request, Trio $trio)
    {
        $trio->delete();
        $request->session()->flash('message', 'Trio zostało usunięte!');
        return redirect()->action('TriosController@index');
    }

    public function active(Request $request, Trio $trio)
    {
        $this->registerChange($request, $trio, 'active', $trio->active, !$trio->active);

        $trio->active = !$trio->active;
        $trio->save();

        return ['state' => $trio->active];
    }
}