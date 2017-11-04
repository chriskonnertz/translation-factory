@extends('translationFactory::base')

@section('title', 'Home')

@section('content')
    <p>Choose a translation file from the files below.</p>

    <?php $currentDir = null ?>
    @foreach($translationBags as $translationBag)
        @if ($currentDir !== $translationBag->getSourceDir())
            <?php $currentDir = $translationBag->getSourceDir() ?>
            <div class="divider text-center" data-content="{{ $currentDir }}"></div>
        @endif
        <div class="bag-tile-wrapper">
            <a href="{{ url('translation-factory/file/'.$translationBag->getHash()) }}" class="bag-tile rounded" title="{{ $translationBag->getSourceFile() }}">
                <div class="icon-wrapper">
                    <i class="icon icon-message"></i>
                </div>

                <span class="name">{{ $translationBag->getName() }}</span>
            </a>
        </div>
    @endforeach
@endsection