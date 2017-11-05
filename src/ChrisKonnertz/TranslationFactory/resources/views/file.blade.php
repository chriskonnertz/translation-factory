@extends('translationFactory::base')

@section('title', 'File')

@section('content')
    <h1 title="{{ $translationBag->getSourceFile() }}">{{ $translationBag->getTitle() }}</h1>

    <p class="initial-info-text">
        Choose a translation item from the items below.
        Then you will be able to edit it.
        Translations will be saved automatically.
    </p>

    <div>
        <div class="items-box @php echo $currentItemKey ? '' : 'max' @endphp">
            <div class="divider text-center" data-content="Choose item ({{ sizeof($translationBag->getTranslations(), COUNT_RECURSIVE) }} available)"></div>

            <ul>
                @foreach($translationBag->getTranslations() as $itemKey => $itemValue)
                    @include('translationFactory::items_list_item')
                @endforeach
            </ul>
        </div>

        <div class="item-box">
            <div class="divider text-center" data-content="Translate item @if ($currentItemKey) {{ '"'.$currentItemKey.'"' }} @endif"></div>

            @if ($currentItemKey)
                <form>
                    <div class="form-group">
                        <label class="form-label">Original</label>
                        <blockquote class="bg-gray">
                            @php $originalText = array_get($translationBag->getTranslations(), $currentItemKey) @endphp
                            <p>{!! preg_replace('/(:\w+)/', '<span title="This is a parameter">${1}</span>', htmlspecialchars($originalText)) !!}</p>
                        </blockquote>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="translation">Translation</label>
                        <textarea class="form-input" id="translation" name="translation" placeholder="Please enter your translation here" rows="5"></textarea>
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
    </div>

    @if ($currentItemKey)
        <script>
            var ul = document.querySelector('.items-box ul');
            var li = ul.querySelector('li[data-key="{{ $currentItemKey }}"]');

            ul.scrollTop = li.offsetTop - ul.offsetTop;
            li.classList.add('current');
        </script>
    @endif
@endsection