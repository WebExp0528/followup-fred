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

                    @if (count($followups) > 0)
                        <table class="table table-striped">
                            <thead class="table-primary">
                                <tr>
                                    <th>Email Subject</th>
                                    <th>Recipients</th>
                                    <th>Followups Sent</th>
                                    <th>Status</th>
                                    <th>Responded</th>
                                    <th>Created At</th>
                                    <th>Completed At</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($followups as $followup)
                                <tr>
                                    <td>{{ $followup->email->subject }}</td>
                                    <td>{{ $followup->to_emails }}</td>
                                    <td>{{ $followup->sent_count }}</td>
                                    <td>{{ $followup->status == 0 ? 'Active' : 'Inactive' }}</td>
                                    <td>{{ $followup->responded == 0 ? 'No' : 'Yes' }}</td>
                                    <td>{{ $followup->created_at->tz($followup->user->timezone)->format('F j, Y g:i a') }}</td>
                                    <td>{{ is_null($followup->completed_at) ? 'n/a' : $followup->completed_at->tz($followup->user->timezone)->format('F j, Y g:i a') }}</td>
                                </tr>
                                <tr>
                                    <td colspan="7">
                                        <h4>Follow-ups sent:</h4>
                                        @if ($followup->logs)
                                        <ul>
                                            @foreach ($followup->logs as $log)
                                            <li>{{ $log->created_at->tz($followup->user->timezone)->format('F j, Y g:i a') }} / Log ID: {{ $log->id }}</li>
                                            @endforeach
                                        </ul>
                                        @else
                                            No emails have been sent out yet.
                                        @endif
                                    </td> 
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p>You do not have any follow-ups right now.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
