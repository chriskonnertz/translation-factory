@extends('translationFactory::base')

@section('title', 'Home')

@section('content')
    <p>Choose a translation file:</p>

    @foreach($translationBags as $translationBag)
        <div class="bag-tile-wrapper">
            <a href="#" class="bag-tile rounded" title="{{ $translationBag->getSourceFile() }}">
                <div class="icon-wrapper">
                    <i class="icon icon-message"></i>
                </div>

                <span class="name">{{ $translationBag->getName() }}</span>
            </a>
        </div>
    @endforeach
@endsection