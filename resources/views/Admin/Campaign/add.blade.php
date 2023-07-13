@extends('Admin.index')
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{ isset($campaign) ? 'Edit Campaign' : 'Add Campaign' }}</h1>
                    <h6 class="text-danger">* Items marked with an asterisk are required fields and must be completed</h6>
                </div>
            </div>
            @if (session()->has('msg-success'))
                <div class="alert alert-success" role="alert">
                    {{ session('msg-success') }}
                </div>
            @elseif (session()->has('msg-error'))
                <div class="alert alert-danger" role="alert">
                    {{ session('msg-success') }}
                </div>
            @endif
        </div>
    </section>
    <section class="content">
        <div class="card">

            <div class="card-body">
                <form action="{{ isset($campaign) ? url('campaigns/edit') : url('campaigns/add') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <input type="hidden" name="hiddenid" value="{{ isset($campaign) ? $campaign->id : '' }}">
                        <div class="col-xs-12 col-md-4">
                            <div class="form-group">
                                <label>Title<span style="color:red">*</span></label>
                                <input type="text" name="title" class="form-control"
                                    value="{{ isset($campaign) ? $campaign->title : old('title') }}">
                                @error('title')
                                    <span class="text-danger">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-4">
                            <div class="form-group">
                                <label>Account <span class="text-danger">*</span></label>

                                <select name="account" class="form-control searchOptions" data-validation="required">
                                    <option value="0">--Choose--</option>
                                    @foreach ($accounts as $item)
                                        <option
                                            {{ isset($campaign) && $campaign->social_account_id == $item->id ? 'selected' : '' }}
                                            value="{{ $item->id }}">{{ $item->title }}</option>
                                    @endforeach
                                </select>

                                @error('state')
                                    <span class="text-danger">
                                        {{ $message }}
                                    </span>
                                @enderror

                            </div>
                        </div>

                        <div class="col-xs-12 col-md-4">
                            <div class="form-group">
                                <label>State</label>

                                <select onchange="renderCitites(this.value)" name="state" id="state"
                                    class="form-control searchOptions" data-validation="required">
                                    <option value="0">--Choose--</option>
                                    @foreach ($states as $item)
                                        <option
                                            {{ isset($campaign) && $campaign->state_id == $item->id ? 'selected' : '' }}
                                            value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>

                                @error('state')
                                    <span class="text-danger">
                                        {{ $message }}
                                    </span>
                                @enderror

                            </div>
                        </div>
                        <div class="col-xs-12 col-md-4">
                            <div class="form-group">
                                <label>City</label>
                                <select name="city" id="city" class="form-control searchOptions"
                                    data-validation="required">
                                    <option value="0">--Choose--</option>
                                    @if (isset($cities))
                                        @foreach ($cities as $item)
                                            <option
                                                {{ isset($campaign) && $campaign->city_id == $item->id ? 'selected' : '' }}
                                                value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    @endif
                                </select>

                                @error('city')
                                    <span class="text-danger">
                                        {{ $message }}
                                    </span>
                                @enderror

                            </div>
                        </div>
                        <div class="col-xs-12 col-md-4">
                            <div class="form-group">
                                <label>Type <span class="text-danger">*</span></label>
                                <select name="type" id="type" class="form-control searchOptions"
                                    data-validation="required">
                                    <option value="0">--Choose--</option>
                                    <option {{isset($campaign)||$campaign->type=="whatsapp"?'selected':''}} value="whatsapp">Whatsapp</option>
                                    <option {{isset($campaign)||$campaign->type=="wati"?'selected':''}} value="wati">Wati</option>
                                    <option {{isset($campaign)||$campaign->type=="messanger"?'selected':''}} value="messanger">Messanger</option>
                                    <option {{isset($campaign)||$campaign->type=="leads"?'selected':''}} value="leads">Leads</option>
                                    <option {{isset($campaign)||$campaign->type=="leads_reach"?'selected':''}} value="leads_reach">Leads Reach</option>
                                    <option {{isset($campaign)||$campaign->type=="links_clicks"?'selected':''}} value="links_clicks">Link Clicks</option>
                                    <option {{isset($campaign)||$campaign->type=="landing_page"?'selected':''}} value="landing_page">Landing Page Views</option>

                                </select>
                                @error('type')
                                    <span class="text-danger">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <label for="">Phone Numbers</label>
                                <select class="form-control" name="phoneNumbers[]" id="" multiple>
                                    <option value="0">--Choose--</option>
                                    @foreach ($phones as $item)
                                        <option value="{{ $item->id }}">{{ $item->number }}</option>
                                    @endforeach

                                </select>
                                @error('phoneNumbers')
                                    <span class="text-danger">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>


                        <div class="col-xs-12 col-md-4">
                            <div class="form-group">
                                <label>Description </label>
                                <textarea type="text" name="description" id="description" class="form-control" data-validation="required">{{ isset($campaign) ? $campaign->description : old('description') }} 
                                </textarea>
                                @error('description')
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
                            <a href="{{ url('/campaigns') }}" type="button" class="btn btn-default">Cancel</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
    <script>
        function renderCitites(id) {
            $.ajax({
                url: BASE_URL + "/render/cities?state_id=" + id,
                success: function(data) {
                    $('#city').html(data);
                }


            });
        }
    </script>
@endsection
