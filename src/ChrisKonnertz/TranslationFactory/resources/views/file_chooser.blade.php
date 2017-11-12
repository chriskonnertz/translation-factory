@extends('translationFactory::base')

@section('title', 'Home')

@section('content')
    <h1>Welcome</h1>

    <p>Choose the target language and a translation file from the files below.</p>

    <div class="divider text-center" data-content="Choose the target language"></div>

    <form class="form-horizontal">
        @php $options = '' @endphp
        @foreach($targetLanguages as $targetLang)
            @if ($targetLang !== Config::get('app.locale'))
                @php $options .= '<option value="'.e($targetLang).'">'.e($targetLang).'</option>' @endphp
            @endif
        @endforeach

        @if ($options !== '')
            <div class="form-group">
                <div class="col-3">
                    <label class="form-label" for="target-language">Translate from <a href="https://www.loc.gov/standards/iso639-2/php/langcodes_name.php?iso_639_1={{ $baseLanguage }}" target="_blank"><i>{{ $baseLanguage }}</i></a> to:</label>
                </div>
                <div class="col-9">
                    <select class="form-select" id="target-language" name="target_language">
                        {!! $options !!}
                    </select>
                </div>
            </div>
        @else
            <div class="toast toast-error">
                There is no target language available. You may add one in the config file.
            </div>
        @endif
    </form>

    @php $currentDir = null @endphp
    @foreach($translationBags as $translationBag)
        @if ($currentDir !== $translationBag->getSourceDir())
            @php $currentDir = $translationBag->getSourceDir() @endphp
            <div class="divider text-center" data-content="{{ $currentDir }}"></div>
        @endif
        <div class="bag-tile-wrapper">
            <a href="{{ url('translation-factory/file/'.$translationBag->getHash()) }}" class="bag-tile rounded" title="{{ $translationBag->getBaseFile() }}">
                <div class="icon-wrapper">
                    <i class="icon icon-message"></i>
                </div>

                <span class="name">{{ $translationBag->getName() }}</span>
            </a>
        </div>
    @endforeach
@endsection
