@extends('Admin.index')
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Campaign</h1>
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
            <div class="alert alert-success" id="alert-success" role="alert" style="display: none">

            </div>

            <div class="alert alert-danger" id="alert-error" role="alert" style="display: none">
            </div>

        </div>
    </section>
    <section class="content">
        <div class="card">
            <div class="card-body">
                <div class="mb-3 d-flex justify-content-between align-items-centers">
                    <form action="{{ url('campaigns') }}" method="POST" id="search-form"
                        class="filters d-flex flex-row col-11 pl-0">
                        @csrf

                        <div class="col-3 ">
                            <label for="">From</label>
                            <input name="from_date" type="date" class="form-control from_date" id="from_date"
                                value="{{ isset($startDate) ? $startDate : '' }}">
                        </div>
                        <div class="col-3">
                            <label for="">To</label>
                            <input name="to_date" type="date" class="form-control to_date" id="to_date"
                                value="{{ isset($endDate) ? $endDate : '' }}">
                        </div>

                        <div class="col-3">
                            <label for="">Accounts</label>
                            <select class="form-control" name="account_id" id="account_id">
                                <option value="">--Choose--</option>
                                @foreach ($accounts as $item)
                                    <option {{ isset($filterAccount) && $filterAccount == $item->id ? 'selected' : '' }}
                                        value="{{ $item->id }}">{{ $item->title }}</option>
                                @endforeach 
                            </select>

                        </div>
                        @if(session('user')->role=="marketing_manager")
                        <div class="col-3">
                            <label for="">Agents</label>
                            <select class="form-control" name="agent_id" id="agent_id">
                                <option value="">--Choose--</option>
                                @foreach ($agents as $item)
                                    <option {{ isset($agent_id) && $agent_id == $item->id ? 'selected' : '' }}
                                        value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach 
                            </select>
                        </div>
                        @endif


                        <div class="">
                            <label for="" style="visibility: hidden;">filter</label>
                            <button class="btn btn-success form-control" type="submit">Filter</button>
                        </div>
                        <div class="mx-2">
                            <label for="" style="visibility: hidden;">filter</label>
                            <a tabindex="1" href="{{ url('campaigns/add') }}" class="btn btn-primary">Add</a>
                        </div>
                    </form>
                    <div>

                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body table-responsive p-0">
                                <table class="table table-hover text-nowrap">
                                    <thead>
                                        <tr>
                                            <th>S.No.</th>
                                            <th>Title</th>
                                            <th>Account Title</th>
                                            <th>State</th>
                                            <th>City</th>
                                            <th>Description</th>
                                            <th>Total Spending</th>
                                            <th>Status</th>
                                            <th>Created At</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($campaign as $item)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $item->title }}</td>
                                                <td>{{ $item->accountName }}</td>
                                                <td>{{ $item->statename }}</td>
                                                <td>{{ $item->city ?? '--' }}</td>
                                                <td>{{ $item->description ?? '--' }}</td>
                                                <td>{{ $item->amount ?? '--' }}</td>
                                                <td style="text-transform: capitalize">{{ $item->status }}</td>
                                                <td>{{ $item->created_at }}</td>
                                                <td>
                                                    <a tabindex="2" href="{{ url('campaigns/edit/?id=' . $item->id) }}"
                                                        title="Edit this Account" class="btn btn-primary"><i
                                                            class="fa fa-pen"></i></a>
                                                    <button tabindex="3" title="Change Status"
                                                        onclick="accountModal({{ $item->id }})"
                                                        class="btn btn-danger">Change Status</button>
                                                    {{-- <button onclick="openSpendingModal({{ $item->id }})"
                                                        title="Add Spending" class="btn btn-primary">Add Spending
                                                        Amount</button> --}}
                                                    <a tabindex="2"
                                                        href="{{ url('campaigns/view-details/?id=' . $item->id) }}"
                                                        title="View Details" class="btn btn-warning">Spending Details</a>
                                                    {{-- <a href="{{ url('campaigns/add-results/?id=' . $item->id) }}"
                                                        title="Add Result" class="btn btn-primary">Add Results</a> --}}
                                                    <a href="{{ url('campaigns/view-results/?id=' . $item->id) }}"
                                                        title="Add Result" class="btn btn-warning">Results Details</a>
                                                    <a href="{{ url('campaigns/show-numbers/?id=' . $item->id) }}"
                                                        title="Add Result" class="btn btn-danger">Phone Number</a>
                                                    <a href="{{ url('campaigns/view-leads/?id=' . $item->id) }}"
                                                        title="View Leads" class="btn btn-danger">View Leads</a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="10" class="text-center">No data</td>
                                            </tr>
                                        @endforelse

                                    </tbody>
                                </table>
                            </div>
                            <div class="card-footer clearfix">
                                {{ $campaign->links('pagination::bootstrap-4') }}
                            </div>
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
                            <select name="status" id="status" class="form-control">
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
            const account_id = $('#account_id').val();
            url.searchParams.set('to_date', to_date);
            url.searchParams.set('from_date', from_date ?? '');
            url.searchParams.set('account_id', account_id ?? '');
            $('#search-form').attr('action', url.toString()).submit();
        }
        const accountModal = (id) => {
            $('#modal-default').modal('show');
            $('#account_hidden_id').val(id);
        }
        const openSpendingModal = (id) => {
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
                url: BASE_URL + "/campaigns/change-status?status=" + status + '&campaign=' + campaignID,
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
