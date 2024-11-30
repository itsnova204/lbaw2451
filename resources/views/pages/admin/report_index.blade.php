@extends('layouts.app')

@section('title', 'Users')

@section('content')
    <div class="rectangle-div">
        <div id="user-crate">
            <h1>Reports</h1>
        </div>
        <table border="1" id="user-table">
            <thead>
            <tr>
                <th>ID<br><input type="text" id="filter-id" placeholder="Filter ID"></th>
                <th>Username<br><input type="text" id="filter-username" placeholder="Filter Username"></th>
                <th>Reason<br><input type="text" id="filter-email" placeholder="Filter Reason"></th>
                <th>Auction<br><input type="text" id="filter-date" placeholder="Filter Auction"></th>
                <th>Status<br><input type="text" id="filter-status" placeholder="Filter Status"></th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            @foreach($reports as $report)
                <tr>
                    <td>{{ $report->id }}</td>
                    <td>{{ $report->user->username }}</td>
                    <td>{{ $report->reason }}</td>
                    <td>{{ $report->auction->title }}</td>
                    <td>{{ $report->status() }}</td>
                    <td class="table-flex">
                        @if($report->status == 'not_processed')
                            <form action="{{ route('admin.reports.process', $report) }}" method="POST">
                                @csrf
                                <button type="submit">Resolve</button>
                            </form>
                            <form action="{{ route('admin.reports.discard', $report) }}" method="POST">
                                @csrf
                                <button type="submit">Discard</button>
                            </form>
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

@endsection