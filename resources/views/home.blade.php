@extends('layouts.app')

@section('content')
<div class="container">  
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif

                You are logged in!

                <table class="table"> 
                  <tbody>
                    <tr>
                      <th scope="row">Username: {{ Auth::user()->username }}</th>
                      <td>Email: {{ Auth::user()->email }}</td>
                      <td>Referral link: {{ Auth::user()->referral_link }}</td> 
                    </tr>
                    <tr>
                        <td>Referrer: {{ Auth::user()->referrer->name ?? 'Not Specified' }}</td>
                        <td>Refferal count: {{ count(Auth::user()->referrals)  ?? '0' }}</td>
                        <td>Position: {{ Auth::user()->position }}</td>
                    </tr>
                  </tbody>
              </table> 

            </div>
            </div>
        </div>
    </div> 
    <hr>
    <div class="row justify-content-center">
        <div class="col-md-12">
             <table class="table table-bordered table-striped" id="contact_table">
                    <thead>
                        <tr>
                            <th>1</th>
                            <th>2</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr class="bg-gray font-17 text-center footer-total">
                            <td></td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
        </div>
    </div>
</div>
@endsection
