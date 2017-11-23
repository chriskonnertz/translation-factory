@extends('translationFactory::base')

@section('title', 'File')

@section('content')
    <div class="header">
        <h1 title="{{ $translationBag->getBaseFile() }}">{{ $translationBag->getTitle() }}</h1>

        <p class="initial-info-text">
            Choose a translation item from the items below, then you will be able to edit it.
            Translations will be saved automatically.
        </p>
    </div>

    <div class="items-box @php echo $currentItemKey ? '' : 'max' @endphp">
        <div class="divider text-center" data-content="Choose item ({{ $translationBag->count() }} available)"></div>

        <ul>
            @foreach($translationBag->getTranslations()[$translationBag->getBaseLanguage()] as $itemKey => $itemValue)
                @include('translationFactory::items_list_item')
            @endforeach
        </ul>
    </div>

    <div class="item-box">
        <div class="divider text-center" data-content="Translate item @if ($currentItemKey) {{ '"'.$currentItemKey.'"' }} @endif"></div>

        @if ($currentItemKey)
            <form>
                <div class="form-group">
                    <label class="form-label">Original in <a href="https://www.loc.gov/standards/iso639-2/php/langcodes_name.php?iso_639_1={{ $baseLanguage }}" target="_blank"><i>{{ $baseLanguage }}</i></a>:</label>

                    <blockquote class="bg-gray">
                        @php $originalText = array_get($translationBag->getTranslations()[$translationBag->getBaseLanguage()], $currentItemKey) @endphp
                        <!-- /&lt;/b&gt; -->
                        @php $originalText = preg_replace(['/(&lt;\/?\w+&gt;)/', '/(:\w+|\||\{\d*\}|\[\d*,(\d*|\*)])/'],
                        ['<span title="HTML Tag">${1}</span>', '<span title=":\w+ = parameter, | = choice, {\d*} = exact amount, [\d,\d|*] = range">${1}</span>'],
                        htmlspecialchars($originalText)) @endphp
                        <p>{!! $originalText !!}</p>
                    </blockquote>
                </div>

                <div class="form-group">
                    <label class="form-label" for="translation">Translation to <a href="https://www.loc.gov/standards/iso639-2/php/langcodes_name.php?iso_639_1={{ $targetLanguage }}" target="_blank"><i>{{ $targetLanguage }}</i></a>:</label>

                    {{-- One giant line to avoid issues with whitespace --}}
                    <textarea class="form-input" id="translation" name="translation" placeholder="Please enter your translation here" rows="5">@if($translationBag->hasTranslation($targetLanguage, $currentItemKey)){{ $translationBag->getTranslation($targetLanguage, $currentItemKey) }}@else{{ $autoTranslation }}@endif</textarea>

                    <progress class="progress save-progress d-invisible" max="100" title="Saving..."></progress>

                    <div class="toast toast-error save-error d-hide">
                        Could not save the translation. <a href="#">Retry?</a>
                    </div>
                </div>

                <div class="button-bar">
                    <button type="button" class="btn btn-sm btn-clear-form">Clear</button>
                    <button type="reset" class="btn btn-sm btn-reset-form">Reset</button>
                    <button type="button" class="btn btn-sm btn-copy-original">Original</button>
                </div>
            </form>
        @else
            <div class="empty">
                <div class="empty-icon">
                    <i class="icon icon-3x icon-edit"></i>
                </div>
                <p class="empty-title h5">No item selected</p>
                <p class="empty-subtitle">Select an item from the list above to start translating.</p>
            </div>
        @endif
    </div>


    <script>
        (function () {
            var ul = document.querySelector('.items-box ul');

            @if ($currentItemKey)
                var li = ul.querySelector('li[data-key="{{ $currentItemKey }}"]');

                // Scroll to the item of the select key and mark it as the current item
                ul.scrollTop = li.offsetTop - ul.offsetTop;
                li.classList.add('current');

                var textArea = document.getElementById('translation');
                var textAreaDirty = false; // True if the text of the text area needs to be saved
                textArea.focus();

                var save = function()
                {
                    var request = new XMLHttpRequest();

                    var form = document.querySelector('form');
                    var data = new FormData(form);

                    // We have to manually add the CSRF token
                    var token = document.head.querySelector('meta[name="csrf-token"]').content;
                    data.append('_token', token);

                    document.querySelector('.save-error').classList.add('d-hide');
                    document.querySelector('.save-progress').classList.remove('d-invisible');

                    request.addEventListener('readystatechange', function() {
                        if (request.readyState === XMLHttpRequest.DONE) {
                            document.querySelector('.save-progress').classList.add('d-invisible');
                            if (request.status !== 200) {
                                document.querySelector('.save-error').classList.remove('d-hide');
                            }
                            if (request.status === 200) {
                                document.querySelector('.save-error').classList.add('d-hide');
                            }
                        }
                    });

                    request.open(
                        'POST',
                        '{{ url('translation-factory/file/'.$translationBag->getHash().'/item/'.$currentItemKey) }}',
                        true
                    );
                    request.send(data);

                };

                textArea.addEventListener('change', function()
                {
                    textAreaDirty = true;
                });

                textArea.addEventListener('focusout', function()
                {
                    // Only save if the text has changed
                    if (textAreaDirty) {
                        textAreaDirty = false;
                        save();
                    }
                });

                document.querySelector('.save-error a').addEventListener('click', function(event)
                {
                    event.preventDefault();
                    save();
                });

                document.querySelector('form .btn-clear-form').addEventListener('click', function(event)
                {
                    textArea.value = '';
                    save();
                });

                document.querySelector('form .btn-reset-form').addEventListener('click', function(event)
                {
                    document.querySelector('form').reset();
                    save();
                });

                document.querySelector('form .btn-copy-original').addEventListener('click', function(event)
                {
                    // Decodes a string with HTML entities.
                    // Source: https://gist.github.com/CatTail/4174511
                    var decodeHtmlEntity = function(str) {
                        return str.replace(/&#(\d+);/g, function(match, dec) {
                            return String.fromCharCode(dec);
                        });
                    };

                    textArea.value = decodeHtmlEntity('{{ $originalText }}');
                    save();
                });
            @endif

            @if ($autoTranslation)
                // We have to set the dirty flag to true here, because if not auto-saving won't happen
                // until the user changes the text - but that would not happen if there is a perfect translation
                textAreaDirty = true;
            @endif

            // When the user clicks on an a-element without the href-attribute set, do nothing
            var noLinks = document.querySelectorAll('a[href=""]');
            noLinks.forEach(function(element)
            {
                element.addEventListener('click', function(event)
                {
                    event.preventDefault();
                });
            });

            var resize = function () {
                var content = document.getElementById('content');
                var header = document.querySelector('#content .header');
                var itemBox = document.querySelector('#content .item-box');

                var li = ul.querySelector('li');
                var maxHeight = window.innerHeight
                    - 20 // content padding top
                    - header.offsetHeight
                    - 40 // header paragraph margin bottom
                    - 20 // items-box ul margin top
                    - 40 // items-box ul margin bottom
                    - itemBox.offsetHeight
                    - 20 // content padding top that is not overlaid by footer
                    - 60 // footer.offsetHeight
                    - 1; // extra offset

                var amount = parseInt(maxHeight / li.offsetHeight);
                var height = Math.max(li.offsetHeight, (amount * li.offsetHeight));

                if (height < 3 * li.offsetHeight) {
                    height = 3 * li.offsetHeight
                }

                ul.style.maxHeight = height + 'px';
            };

            window.addEventListener('resize', function(event) {
                resize();
            });
            resize(); // Immediately resize, do not wait until document is fully loaded
        })();
    </script>
@endsection
