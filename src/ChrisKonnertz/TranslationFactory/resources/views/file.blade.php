@extends('translationFactory::base')

@section('title', 'File')

@section('content')
    <div class="header">
        <h1 title="{{ $translationBag->getSourceFile() }}">{{ $translationBag->getTitle() }}</h1>

        <p class="initial-info-text">
            Choose a translation item from the items below.
            Then you will be able to edit it.
            Translations will be saved automatically.
        </p>
    </div>

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
                        <p>{!! preg_replace('/(:\w+|\||\{\d*\}|\[\d*,(\d*|\*)])/', '<span title="This is a special expression">${1}</span>', htmlspecialchars($originalText)) !!}</p>
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


    <script>
        (function () {
            var ul = document.querySelector('.items-box ul');

            @if ($currentItemKey)
                var li = ul.querySelector('li[data-key="{{ $currentItemKey }}"]');

                ul.scrollTop = li.offsetTop - ul.offsetTop;
                li.classList.add('current');
            @endif

            var resize = function () {
                var content = document.getElementById('content');
                var header = document.querySelector('#content .header');
                var itemBox = document.querySelector('#content .item-box');
                var footer = document.getElementById('footer');

                var li = ul.querySelector('li');
                var maxHeight = window.innerHeight
                    - 20 // content padding top
                    - header.offsetHeight
                    - 40 // header paragraph margin bottom
                    - 20 // items-box ul margin top
                    - 40 // items-box ul margin bottom
                    - itemBox.offsetHeight
                    - 20 // content padding top that is not overlaid by footer
                    - footer.offsetHeight
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
            document.addEventListener('DOMContentLoaded', function(event) {
                resize();
            });
        })();
    </script>
@endsection