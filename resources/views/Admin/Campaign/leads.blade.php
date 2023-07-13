@extends('Admin.index')
@section('content')
    <style>
        .custom-modal {
            max-width: 70%;
            width: 70%;
            max-height: 70%;
            height: auto;
        }

        @media (max-width: 580px) {
            .custom-modal {
                max-width: 100%;
                width: 100%;
            }
        }
    </style>
    @php
        function serialToDate($serialNumber)
        {
            $unixTimestamp = ($serialNumber - 25569) * 86400; // adjust for Unix epoch and convert to seconds
            $date = \Carbon\Carbon::createFromTimestamp($unixTimestamp);
            return $date->format('d-m-Y');
        }
    @endphp

    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Duplicate Leads</h1>
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
                <div class="mb-3 d-flex justify-content-between align-items-centers row">
                    
                  
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body table-responsive p-0">
                                <table class="table table-hover text-nowrap">
                                    <thead>
                                        <tr>
                                            <th>S.No.</th>
                                            <th>Source</th>
                                            <th>Date</th>
                                            <th>Name</th>
                                            <th>Number</th>
                                            <th>Language</th>
                                            <th>State</th>
                                            
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($leads as $item)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $item->source_name }}</td>
                                                <td>{{ $item->date }}</td>
                                                <td>{{ $item->name }}</td>
                                                <td>{{ $item->number }}</td>
                                                <td>{{ $item->language }}</td>
                                                <td>{{ $item->state??'' }}</td>
                                                
                                                
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
                            {{-- {{ $leads->links('pagination::bootstrap-4') }} --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>








<script>
    let lead_id;
    let status;
    const searchData = () => {
        event.preventDefault();
        const url = new URL(window.location.href);
        const searchValue = $('#searchInput').val().trim();
        const filter_agent = $('#agent_id').val();
        url.searchParams.set('search', searchValue);
        url.searchParams.set('agent', filter_agent ?? '');
        $('#search-form').attr('action', url.toString()).submit();
    }
    // const openLeadModal = (leadId,idName) => {
       
    //     $(`#modal-default`).modal("show");
    //     $(`#lead_id`).val(leadId);
    //     $(`#idName`).val(idName);
    // }
    // const handleStatusValues = (option) => {
    //     status = $(option).find(':selected').data('second-value');
    //     let submitButton = $('#status-submit-button')
    //     let conditionalInput = $('.conditional-input')
    //     let forDepostied = $('.for-deposited')
    //     conditionalInput.hide()
    //     if (status == "0") {
    //         submitButton.attr('disabled', true);
    //     } else {
    //         if (status == 6||status == 7||status == 8 || status == 11) {

    //             conditionalInput.show()
    //         } else {
    //             conditionalInput.hide()
    //         }
    //         submitButton.removeAttr('disabled');
    //     }
    //     // if (status == 1) {
    //     //     forDepostied.show()
    //     // } else {
    //     //     forDepostied.hide()
    //     // }

    // }
    // const submitStatusChange = () => {
    //     let submitButton = $('#status-submit-button')
    //     event.preventDefault();
    //     let datePicker = $('#datePicker').val()
    //     let amount = $('#amount').val()
    //     let IdName = $('#idName').val()
    //     if ((status == 6||status == 7||status == 8 || status == 11) && !datePicker) {
    //         $('.error-date').html('Please enter valid date')

    //     } 
    //     // else if ((status == 1) && !amount) {
    //     //     $('.error-amount').html('Please enter amount')
    //     // }
    //     // else if ((status == 1) && !IdName) {
    //     //     $('.error-idName').html('Please enter IdName')
    //     // }  
    //     else {
    //         $('#status-form').submit();
    //     }
    // }
    // history modal
    const openHistoryModal = (leadId) => {
        $(`#modal-history`).modal("show");
        $(`#lead_id`).val(leadId);
        searchLeadsStatus(leadId)
    }

    // function searchLeadsStatus(lead_id) {
    //    
    //     const filteredData = leadsStatusData.filter(data => data.lead_id == lead_id);

    //     const table = createTable(filteredData);
    //     const loadingSpinner = "<div class='text-center'><i class='fa fa-spinner fa-spin'></i> Loading...</div>";
    //     const noData = "<div class='text-center'>No Data Found</div>";
    //     $("#historytable").html(loadingSpinner);
    //     setTimeout(() => {
    //         const filteredData = leadsStatusData.filter(data => data.lead_id == lead_id);
    //         if (filteredData.length == 0) {
    //             $("#historytable").html(noData);
    //         } else {
    //             $("#historytable").html(table);
    //         }
    //     }, 500);
    // }

    // function createTable(data) {
    //     let table = "<table class='table '>";
    //     table +=
    //         "<thead><tr><th>Sr.No</th><th style='width:10%;text-align:center'>Status</th><th style='width:30%;text-align:center'>FollowUp Date</th><th style='width:10%;text-align:center'>Amount</th><th>Created at</th><th>Remark</th></tr></thead>";
    //     table += "<tbody>";
    //     data.forEach((item, index) => {
    //         table +=
    //             `<tr><td>${index+1}</td><td style='width:10%;text-align:center'>${item.status_name}</td><td style='width:30%;text-align:center'>${item.followup_date??'--'}</td><th style='width:10%;text-align:center'>${item.amount??'--'}</th><td style="word-wrap">${moment(item.created_at).format('DD-MM-YYYY') ??'--'}</td><td style="word-wrap">${item.remark??'--'}</td></tr>`;
    //     });
    //     table += "</tbody></table>";
    //     return table;
    // }
</script>




@endsection
