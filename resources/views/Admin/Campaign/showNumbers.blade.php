@extends('Admin.index')
@section('content')
    <?php $platforms = ['wa' => 'Whatsapp', 'wa_c' => 'Whatsapp Clone', 'wa_b' => 'Whatsapp Business']; ?>
    <?php $devices = ['galaxy_a04' => 'Galaxy A04', 'galaxy_a03_core' => 'Galaxy A03 Core', 'redmi_9a' => 'Redmi 9A', 'oppo_f1s' => 'Oppo F1S', 'galaxy_a03' => 'Galaxy A03', 'galaxy_s22' => 'Galaxy S22']; ?>
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Phone Numbers</h1>
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
                
                   
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body table-responsive p-0">
                                <table class="table table-hover text-nowrap">
                                    <thead>
                                        <tr>
                                            <th>S.No.</th>
                                            <th>Number</th>
                                            <th>Platform</th>
                                            <th>Agent</th>
                                            <th>Device Name</th>
                                            <th>Device Code</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($numbers as $item)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $item->number ?? '' }}</td>
                                                <td>
                                                    @if ($item->platform == 'wa')
                                                        Whatsapp
                                                    @elseif($item->platform == 'wa_b')
                                                        Whatsapp Business
                                                    @elseif($item->platform == 'wa_c')
                                                        Whatsapp Clone
                                                    @elseif($item->platform == 'wati')
                                                        Wati
                                                    @endif

                                                </td>
                                                <td>{{ $item->agentName ?? 'Spare' }}</td>
                                                <td>{{ $item->device_name ?? '' }}</td>
                                                <td>{{ $item->device_code }}</td>
                                                <td style="text-transform: capitalize">
                                                    {{ $item->status == 'active' && $item->agentName ? 'Assigned' : $item->status }}
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
                            {{-- <div class="card-footer clearfix">
                                {{ $numbers->links('pagination::bootstrap-4') }}
                            </div> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
       
    </section>
    

    <script>
        const searchData = () => {
            event.preventDefault();
            const url = new URL(window.location.href);

            const searchValue = $('#searchInput').val().trim();
            const platformSeach = $('#platform_seach').val();
            const agentSearch = $('#agent_id_search').val();
            url.searchParams.set('search', searchValue);
            url.searchParams.set('platform_seach', searchValue);
            url.searchParams.set('agent_id_search', searchValue);
            $('#search-form').attr('action', url.toString()).submit();
        }

        function reassignModal(id) {
            console.log(id);
            $('#reassignModal').modal('show')
            $('#reasignID').val(id)
        }
    </script>
@endsection
