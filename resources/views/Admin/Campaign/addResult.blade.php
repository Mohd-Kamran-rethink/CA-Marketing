@extends('Admin.index')
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 style="text-transform: capitalize">{{ isset($campagin) ? $campagin->title : '' }}</h1>
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
                <form action="{{ isset($result) ? url('campaigns/edit-result') : url('campaigns/add-result') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <input type="hidden" name="campaign" value="{{$campagin->id}}">
                        <input type="hidden" name="resultID" value="{{isset($result)?$result->id:''}}">
                        <div class="col-xs-12 col-md-4">
                            <div class="form-group">
                                <label>Whatsapp Messages</label>
                                <input type="number" step="any" name="whatsappMessage" class="form-control"
                                    value="{{ isset($result) ? $result->whatsapp_messages : old('whatsappMessage') }}">
                                @error('whatsappMessage')
                                    <span class="text-danger">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-4">
                            <div class="form-group">
                                <label>Messanger</label>
                                <input type="number" step="any" name="messanger" class="form-control"
                                    value="{{ isset($result) ? $result->messanger : old('messanger') }}">
                                @error('messanger')
                                    <span class="text-danger">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-4">
                            <div class="form-group">
                                <label>Leads</label>
                                <input type="number" step="any" name="leads" class="form-control"
                                    value="{{ isset($result) ? $result->leads : old('leads') }}">
                                @error('leads')
                                    <span class="text-danger">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-4">
                            <div class="form-group">
                                <label>Impression</label>
                                <input type="number" step="any" name="impression" class="form-control"
                                    value="{{ isset($result) ? $result->impresssions : old('impression') }}">
                                @error('impression')
                                    <span class="text-danger">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-4">
                            <div class="form-group">
                                <label>Lead Reach</label>
                                <input type="number" step="any" name="lead_reach" class="form-control"
                                    value="{{ isset($result) ? $result->lead_reach : old('lead_reach') }}">
                                @error('lead_reach')
                                    <span class="text-danger">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-4">
                            <div class="form-group">
                                <label>Link Clicks</label>
                                <input type="number" step="any" name="link_clicks" class="form-control"
                                    value="{{ isset($result) ? $result->link_clicks : old('link_clicks') }}">
                                @error('link_clicks')
                                    <span class="text-danger">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-xs-12 col-md-4">
                            <div class="form-group">
                                <label>Landing Page Views</label>
                                <input type="number" step="any" name="landing_page_views" class="form-control"
                                    value="{{ isset($result) ? $result->landing_page_views : old('landing_page_views') }}">
                                @error('landing_page_views')
                                    <span class="text-danger">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-4">
                            <div class="form-group">
                                <label>Amount Spent</label>
                                <input type="number" step="any" name="amout_spent" class="form-control"
                                    value="{{ isset($result) ? $result->amout_spent : old('amout_spent') }}">
                                @error('amout_spent')
                                    <span class="text-danger">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-4">
                            <div class="form-group">
                                <label>Date<span style="color:red">*</span></label>
                                <input type="date" step="any" name="date" class="form-control"
                                    value="{{ isset($result) ? $result->date : old('date') }}">
                                @error('date')
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
        function renderCitites(id)
        {
            $.ajax({
                url: BASE_URL + "/render/cities?state_id=" + id,
                success: function(data) {
                    $('#city').html(data);
                }


            });
            }
    </script>
@endsection
