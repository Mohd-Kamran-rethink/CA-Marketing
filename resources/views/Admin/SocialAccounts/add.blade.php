@extends('Admin.index')
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{ isset($source) ? 'Edit Account' : 'Add Account' }}</h1>
                    <h6 class="text-danger">* Items marked with an asterisk are required fields and must be completed</h6>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="card">

            <div class="card-body">
                <form action="{{ isset($account) ? url('accounts/edit') : url('accounts/add') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <input type="hidden" name="accountID" value="{{ isset($account) ? $account->id : '' }}">
                        <div class="col-xs-12 col-md-4">
                            <div class="form-group">
                                <label>Account Title<span style="color:red">*</span></label>
                                <input type="text" name="title" class="form-control" data-validation="required"
                                    value="{{ isset($account) ? $account->title : old('name') }}">
                                @error('title')
                                    <span class="text-danger">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-4">
                            <div class="form-group">
                                <label>Assign To Agent<span style="color:red">*</span></label>
                                <select type="text" name="agent" class="form-control searchOptions"
                                    data-validation="required">
                                    <option value="0">--Choose--</option>
                                    @foreach ($agents as $item)
                                        <option {{isset($account) && $account->agent_id==$item->id?'selected':''}} value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>

                                @error('agent')
                                    <span class="text-danger">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-4">
                            <div class="form-group">
                                <label>Sources<span style="color:red">*</span></label>
                                <select name="source" class="form-control searchOptions" data-validation="required"
                                   >
                                    <option value="0">--Choose--</option>
                                    @foreach ($sources as $item)
                                        <option {{isset($account) && $account->marketing_source_id ==$item->id?'selected':''}} value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>

                                @error('source')
                                    <span class="text-danger">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-xs-12 col-md-4">
                            <div class="form-group">
                                <label>Currency<span style="color:red">*</span></label>
                                <select name="currency" class="form-control searchOptions" data-validation="required"
                                    value="{{ isset($account) ? $account->currency : old('name') }}">
                                    <option value="0">--Choose--</option>
                                    <option {{isset($account) && $account->currency=='usdt'?'selected':''}} value="usdt">USDT</option>
                                    <option {{isset($account) && $account->currency=='inr'?'selected':''}} value="inr">INR</option>
                                    <option {{isset($account) && $account->currency=='aed'?'selected':''}} value="aed">AED</option>
                                    <option {{isset($account) && $account->currency=='pkr'?'selected':''}} value="pkr">PKR</option>
                                </select>
                                 @error('currency')
                                    <span class="text-danger">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-4">
                            <div class="form-group">
                                <label>Total Value<span style="color:red">*</span></label>
                                <input value="{{isset($account)?$account->total_value:old('total_value')}}" type="number" step="any" class="form-control" name="total_value">
                                 @error('total_value')
                                    <span class="text-danger">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-4">
                            <div class="form-group">
                                <label>Provider <span style="color:red">*</span></label>
                                <input type="text" name="provider" class="form-control" value="{{ isset($account) ? $account->provider : old('provider')  }}">
                                @error('provider')
                                    <span class="text-danger">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>


                    <div class="row mt-2">
                        <div class="col-12">
                            <button type="submit" class="btn btn-info">Save</button>
                            <a href="{{ url('/sources') }}" type="button" class="btn btn-default">Cancel</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection
