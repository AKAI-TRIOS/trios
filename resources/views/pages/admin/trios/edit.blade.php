@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @if(session('msg'))
                <h2>{{session('msg')}}</h2>
            @endif
            <div class="panel panel-default">
                <div class="panel-heading">
                    Add Trio
                </div>
                <div class="panel-body">
                    <form action="{{ action('TriosController@update', $trio->id) }}" class="form-horizontal" method="post" role="form">
                        {{ csrf_field() }}
                        <input name="_method" type="hidden" value="PATCH">
                        <fieldset>
                            <div class="form-group">
                                <label class="col-md-4 control-label" for="sentence1">Sentence 1</label>
                                <div class="col-md-4">
                                    <input class="form-control input-md" id="sentence1" name="sentence1" placeholder="" value="{{ $trio->sentence1 }}" required="" type="text">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4 control-label" for="sentence2">Sentence 2</label>
                                <div class="col-md-4">
                                    <input class="form-control input-md" id="sentence2" name="sentence2" placeholder="" value="{{ $trio->sentence2 }}" required="" type="text">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4 control-label" for="sentence3">Sentence 3</label>
                                <div class="col-md-4">
                                    <input class="form-control input-md" id="sentence3" name="sentence3" placeholder="" value="{{ $trio->sentence3 }}" required="" type="text">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4 control-label" for="explanation1">Explanation 1</label>
                                <div class="col-md-4">
                                    <input class="form-control input-md" id="explanation1" name="explanation1" placeholder="" value="{{ $trio->explanation1 }}" required="" type="text">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4 control-label" for="explanation2">Explanation 2</label>
                                <div class="col-md-4">
                                    <input class="form-control input-md" id="explanation2" name="explanation2" placeholder="" value="{{ $trio->explanation2 }}" required="" type="text">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4 control-label" for="explanation3">Explanation 3</label>
                                <div class="col-md-4">
                                    <input class="form-control input-md" id="explanation3" name="explanation3" placeholder="" value="{{ $trio->explanation3 }}" required="" type="text">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4 control-label" for="answer">Answer</label>
                                <div class="col-md-4">
                                    <input class="form-control input-md" id="answer" name="answer" placeholder="" value="{{ $trio->answer }}" required="" type="text">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <button class="btn btn-default" type="submit">Update</button>
                                </div>
                            </div>
                        </fieldset>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection