@extends('Admin.index')
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Accounts</h1>
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
                    <form action="{{ url('accounts') }}" method="post" id="search-form"
                        class="filters d-flex flex-row col-11 pl-0">
                        @csrf
                    <div class="col-3">
                        <label for="">Search</label>
                        <input value="{{isset($searchTerm)?$searchTerm:''}}" name="table_search" class="form-control" placeholder="Search">
                    </div>
                        

                        <div class="col-3">
                            <label for="">Agents</label>
                            <select class="form-control" name="agent" id="agent">
                                <option value="">--Choose--</option>
                                @foreach ($agents as $item)
                                    <option {{ isset($filterAgent) && $filterAgent == $item->id ? 'selected' : '' }}
                                        value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach 
                            </select>

                        </div>

                        <div class="">
                            <label for="" style="visibility: hidden;">filter</label>
                            <button class="btn btn-success form-control" type="submit">Filter</button>
                        </div>
                        <div class="mx-2">
                            <label for="" style="visibility: hidden;">filter</label>
                            <a tabindex="1" href="{{ url('accounts/add') }}" class="btn btn-primary">Add New Account</a>
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
                                            <th>Title</th>
                                            <th>Agent</th>
                                            <th>Source</th>
                                            <th>Total Value</th>
                                            <th>Holding Funds</th>
                                            <th>Status</th>
                                            <th>Provider</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($accounts as $item)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $item->title }}</td>
                                                <td>{{ $item->agentName }}</td>
                                                <td>{{ $item->sourcename }}</td>
                                                <td>{{ $item->total_value }}</td>
                                                <td>{{ $item->holding_funds ?? 0 }}</td>
                                                <td style="text-transform: capitalize">{{ $item->status }}</td>
                                                <td style="text-transform: capitalize">{{ $item->provider }}</td>
                                                <td>
                                                    <a tabindex="2" href="{{ url('accounts/edit/?id=' . $item->id) }}"
                                                        title="Edit this Account" class="btn btn-primary"><i
                                                            class="fa fa-pen"></i></a>
                                                    <button tabindex="3" title="Deactivate this account"
                                                        onclick="accountModal({{ $item->id }})"
                                                        class="btn btn-danger">Change Status</button>
                                                    <a href="{{url('/campaigns?account_id='.$item->id)}}"
                                                        class="btn btn-danger">View Campaigns</a>
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
                                {{ $accounts->links('pagination::bootstrap-4') }}
                            </div>
                        </div>
                    </div>
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
                <form action="{{ url('/accounts/change-status') }}" method="GET">
                    <input type="hidden" name="account" id="account_hidden_id">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="">Status</label>
                            <select onchange="handleStatusChange(this.value)" name="status" id="status"
                                class="form-control">
                                <option value="0">--Choose--</option>
                                <option value="active">Active</option>
                                <option value="banned">Banned</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group mx-3" style="display: none" id="holding_funds_form">
                        <label for="">Holding Funds</label>
                        <input type="number" step="any" name="holding_funds" id="holding_funds" class="form-control">
                    </div>
                    <div class="modal-footer ">
                        <button type="button" onclick="submitStatus()" class="btn btn-danger">Submit</button>
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
            const searchValue = $('#searchInput').val().trim();
            url.searchParams.set('search', searchValue);
            $('#search-form').attr('action', url.toString()).submit();
        }
        const accountModal = (id) => {
            $('#modal-default').modal('show');
            $('#account_hidden_id').val(id);
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
            let holding_funds = $('#holding_funds').val()
            let account = $('#account_hidden_id').val()

            $.ajax({
                url: BASE_URL + "/accounts/change-status?status=" + status + '&holding_funds=' + holding_funds +
                    '&account=' + account,
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
