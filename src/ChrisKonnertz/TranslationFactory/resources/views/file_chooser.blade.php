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
                @if ($targetLang === $currentTargetLanguage)
                    @php $selected = 'selected="selected"' @endphp
                @else
                    @php $selected = '' @endphp
                @endif
                @php $options .= '<option value="'.e($targetLang).'" '.$selected.'>'.e($targetLang).'</option>' @endphp
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

            <div class="toast toast-error save-error d-hide">
                Could not save your choice. <a href="#">Retry?</a>
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
            <a href="{{ url('translation-factory/file/'.$translationBag->getHash()) }}" class="tile bag-tile rounded" title="{{ $translationBag->getBaseFile() }}">
                <div class="tile-icon">
                    <div class="icon-wrapper">
                        <i class="icon icon-message"></i>
                    </div>
                </div>
                <div class="tile-content">
                    <p class="tile-title">
                        {{ $translationBag->getName() }}
                    </p>
                    <p class="tile-subtitle text-gray">
                        This file contains
                        @if ($translationBag->count() === 1)
                            one item
                        @else
                            {{ $translationBag->count() }} items
                        @endif
                        in
                        @if ($translationBag->countLanguages() === 1)
                            one language.
                        @else
                            {{ $translationBag->countLanguages() }} languages.
                        @endif
                        The file size of the base file is {{ filesize($translationBag->getBaseFile()) }} Bytes.
                    </p>
                </div>
            </a>
        </div>
    @endforeach

    <script>
        (function () {
            var targetLang = document.getElementById('target-language');

            var save = function()
            {
                var request = new XMLHttpRequest();

                var form = document.querySelector('form');
                var data = new FormData(form);

                document.querySelector('.save-error').classList.add('d-hide');

                request.addEventListener('readystatechange', function() {
                    if (request.readyState === XMLHttpRequest.DONE) {
                        if (request.status !== 200) {
                            document.querySelector('.save-error').classList.remove('d-hide');
                        }
                        if (request.status === 200) {
                            document.querySelector('.save-error').classList.add('d-hide');
                        }
                    }
                });

                request.open('POST', '{{ url('translation-factory') }}', true);
                request.send(data);
            };

            document.querySelector('.save-error a').addEventListener('click', function(event)
            {
                event.preventDefault();
                save();
            });
            targetLang.addEventListener('focusout', save);
        })();
    </script>
@endsection
