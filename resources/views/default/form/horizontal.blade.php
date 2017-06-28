<?php /** @var \Lego\Widget\Form $form */ ?>

@include('lego::_default.snippets.top-buttons', ['widget' => $form])

@include('lego::_default.messages', ['object' => $form])
<form id="{{ $form->elementId() }}" method="post" class="form-horizontal" action="{{ $form->getAction() }}">
    @foreach($form->fields() as $field)
        @include('lego::_default.form.horizontal-form-group', ['field' => $field])
    @endforeach

    @if($form->isEditable())
        {{ csrf_field() }}

        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <button type="submit" class="btn btn-primary">提交</button>
            </div>
        </div>
    @endif
</form>

@include('lego::_default.snippets.bottom-buttons', ['widget' => $form])

@push('lego-scripts')
    @include('lego::_default.form.condition-group', ['form' => $form])
@endpush
