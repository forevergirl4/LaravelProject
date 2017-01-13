@extends('layouts.master')

@section('title')
    Profile Page
@endsection

@section('content')
    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xs-offset-0 col-sm-offset-0 col-md-offset-3 col-lg-offset-3 toppad" > 
          <div class="panel panel-info">
            <div class="panel-heading">
              <h3 class="panel-title">{{ $user->first_name }} {{ $user->last_name }}</h3>
            </div>
            <div class="panel-body">
              <div class="row">
              @if (Storage::disk('local')->has($user->first_name . '-' . $user->id . '.jpg'))
                <div class="col-md-3 col-lg-3 " align="center">
                        <img src="{{ route('account.image', ['filename' => $user->first_name . '-' . $user->id . '.jpg']) }}" alt="" class="img-circle img-responsive">
                </div>
              @endif  
                <div class=" col-md-9 col-lg-9 "> 
                  <table class="table table-user-information">
                    <tbody>
                      <tr>
                        <td>Email</td>
                        <td><a href="{{ $user->email }}">{{ $user->email }}</a></td>
                      </tr>
                        <td>Phone Number</td>
                        <td>{{ $user->phone }}
                        </td>
                           
                      </tr>
                     
                    </tbody>
                  </table>
                </div>
              </div>
                <div class="panel-footer">
                        <span class="pull-right">
                            <a href="{{ route('account') }}" data-original-title="Edit this user" data-toggle="tooltip" type="button" class="btn btn-sm btn-warning"><i class="glyphicon glyphicon-edit"></i></a>
                        </span>
                </div>
            </div>
          </div>
        </div>
@endsection