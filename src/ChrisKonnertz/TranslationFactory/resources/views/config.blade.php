@extends('translationFactory::base')

@section('title', 'Config')

@section('content')
    <div class="header">
        <h1>Config</h1>

        <p class="initial-info-text">
            Below you can see all the config values of this package.
        </p>
    </div>

    <div>
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>Key</th>
                    <th>Value</th>
                    <th>Type</th>
                </tr>
            </thead>
            <tbody>
                @foreach($configValues as $configKey => $configValue)
                    <tr class="">
                        <td>
                            {{ $configKey }}
                        </td>
                        <td>
                            @if (is_array($configValue))
                                {{ implode(', ', $configValue) }}
                            @elseif (is_bool($configValue))
                                @if ($configValue)
                                    <span title="true">✓</span>
                                @else
                                    <span title="false">🞪</span>
                                @endif
                            @else
                                {{ $configValue }}
                            @endif
                        </td>
                        <td>
                            {{ gettype($configValue)  }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
