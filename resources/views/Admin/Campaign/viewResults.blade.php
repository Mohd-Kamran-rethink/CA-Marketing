@extends('Admin.index')
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Campagin Results</h1>
                </div>
            </div>
            @if (session()->has('msg-success'))
            <div class="alert alert-success" role="alert">
                {{ session('msg-success') }}
            </div>
        @elseif (session()->has('msg-error'))
            <div class="alert alert-danger" role="alert">
                {{ session('msg-error') }}
            </div>
        @endif
               
           
        </div>
    </section>
    <section class="content">
        <div class="card">
            <div class="card-body">
                <div class="mb-3 d-flex justify-content-between align-items-centers">
                    <form action="{{ url('reports/deposits') }}" method="GET" id="search-form"
                        class="filters d-flex flex-row col-12 pl-0">

                        <div class="col-3 ">
                            <input name="id" type="hidden" value="{{ $id }}">
                            <label for="">From</label>
                            <input name="from_date" type="date" class="form-control from_date" id="from_date"
                                value="{{ isset($startDate) ? $startDate : '' }}">
                        </div>
                        <div class="col-3">
                            <label for="">To</label>
                            <input name="to_date" type="date" class="form-control to_date" id="to_date"
                                value="{{ isset($endDate) ? $endDate : '' }}">
                        </div>
                        
                        
                        
                        <div class="">
                            <label for="" style="visibility: hidden;">filter</label>
                            <button class="btn btn-success form-control" onclick="searchData()">Filter</button>
                        </div>
                        <div class="d-flex flex-column mx-2">
                            <label for="" style="visibility: hidden;">filter</label>
                            <a href="{{ url('campaigns/add-results/?id=' . $id) }}"
                                title="Add Result" class="btn btn-primary">Add Results</a>
                        </div>
                    </form>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body table-responsive p-0">
                                <table class="table table-hover text-nowrap">
                                    <thead>
                                        <tr>
                                            <th>S.No.</th>
                                            <th>Whatsapp Messages</th>
                                            <th>Messanger</th>
                                            <th>Leads</th>
                                            <th>Impression</th>
                                            <th>Lead Reach</th>
                                            <th>Link Click</th>
                                            <th>Landing Page</th>
                                            <th>Date</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($campaignResult as $item)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $item->whatsapp_messages  ?? '--' }}</td>
                                                <td>{{ $item->messanger ?? '--' }}</td>
                                                <td>{{ $item->leads ?? '--' }}</td>
                                                <td>{{ $item->impresssions ?? '--' }}</td>
                                                <td >{{ $item->lead_reach?? '--' }}</td>
                                                <td >{{ $item->link_clicks ?? '--'}}</td>
                                                <td >{{ $item->landing_page_views?? '--' }}</td>
                                                <td >{{ $item->date ?? '--'}}</td>
                                                <td ><a href="{{url('campaigns/edit-results?id='.$item->campaign_id.'&result_id='.$item->id)}}" class="btn btn-primary"><i class="fa fa-pen"></i></a></td>
                                                
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="10" class="text-center">No data</td>
                                            </tr>
                                        @endforelse

                                    </tbody>
                                </table>
                            </div>
                            {{-- <div class="card-footer clearfix">
                                {{ $campaginHistory->links('pagination::bootstrap-4') }}
                            </div> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- spending modal --}}
    <div class="modal fade show" id="spending-modal" style=" padding-right: 17px;" aria-modal="true" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Add Spending</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="{{ url('/campaigns/add-spending') }}" method="POST">
                    @csrf
                    <input type="hidden" name="campaginID" id="campaginID">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="">Amount</label>
                            <input type="number" name="amount" class="form-control">
                        </div>
                    </div>
                   
                    <div class="modal-footer ">
                        <button type="submit" class="btn btn-danger">Submit</button>
                        <button type="button" data-dismiss="modal" aria-label="Close"
                            class="btn btn-default">Cancel</button>
                </form>
            </div>
        </div>
    </div>
    </section>
    <div class="modal fade show" id="modal-default" style=" padding-right: 17px;" aria-modal="true" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Change Status</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="{{ url('/campaigns/change-status') }}" method="POST">
                    @csrf
                    <input type="hidden" name="campagin" id="account_hidden_id">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="">Status</label>
                            <select  name="status" id="status"
                                class="form-control">
                                <option value="0">--Choose--</option>
                                <option value="active">Active</option>
                                <option value="pause">Pause</option>
                                <option value="suspended">Suspended</option>
                                <option value="review">Review</option>
                            </select>
                        </div>
                    </div>
                   
                    <div class="modal-footer ">
                        <button type="submit" class="btn btn-danger">Submit</button>
                        <button type="button" data-dismiss="modal" aria-label="Close"
                            class="btn btn-default">Cancel</button>
                </form>
            </div>
        </div>
    </div>
    
    <script>
        const searchData = () => {
            event.preventDefault();
            const url = new URL(window.location.href);
            const from_date = $('#from_date').val();
            const to_date = $('#to_date').val();
            $('#search-form').attr('action', url.toString()).submit();
        }
        const accountModal = (id) => {
            $('#modal-default').modal('show');
            $('#account_hidden_id').val(id);
        }
        const openSpendingModal=(id)=>
        {
            $('#spending-modal').modal('show');
            $('#campaginID').val(id);
        }


        const handleStatusChange = (value) => {
            if (value == 'banned') {
                $('#holding_funds_form').show();
            } else {
                $('#holding_funds_form').hide();
            }
        }
        const submitStatus = () => {
            let status = $('#status').val()
            let campaignID = $('#account_hidden_id').val()

            $.ajax({
                url: BASE_URL + "/campaigns/change-status?status=" + status +'&campaign=' + campaignID,
                success: function(data) {
                    if (data.hasOwnProperty('msg-success')) {
                        // Show success message
                        $('#alert-success').text(data['msg-success']);

                    } else if (data.hasOwnProperty('msg-error')) {
                        $('#alert-error').text(data['msg-success']);
                    }
                    $('#modal-default').modal('hide');
                    location.reload();
                },
                    
                   
                error: function(xhr, status, error) {
                    var response = JSON.parse(xhr.responseText);
                    if (response.errors) {
                        $('.invalid-feedback').empty();
                        $('.is-invalid').removeClass('is-invalid')
                        // Loop through each error and display it on the respective input field
                        $.each(response.errors, function(key, value) {
                            var inputElement = $('#' + key);
                            inputElement.addClass('is-invalid');
                            inputElement.next('.invalid-feedback').html(value[0]);
                        });
                    } else {

                    }
                }

            });
        }
    </script>
@endsection
