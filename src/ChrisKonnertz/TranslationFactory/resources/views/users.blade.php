@extends('translationFactory::base')

@section('title', 'File')

@section('content')
    <div class="header">
        <h1>Accounts</h1>

        <p class="page-info-text">
            See the accounts of all registered translators.
        </p>
    </div>

    <div class="table-wrapper">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Registered at</th>
                    <th>Activated</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                    <tr>
                        <td>
                            {{ $user->id }}
                        </td>
                        <td>
                            {{ $user->name }}
                        </td>
                        <td>
                            {{ $user->email }}
                        </td>
                        <td>
                            {{ $user->created_at }}
                        </td>
                        <td>
                            @if ($user->{'translation_factory'.'_activated'})
                                <span title="true">✓</span>
                            @else
                                <span title="false">🞪</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
