@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.slider.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.sliders.update", [$slider->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label for="slider_text">{{ trans('cruds.slider.fields.slider_text') }}</label>
                <input class="form-control {{ $errors->has('slider_text') ? 'is-invalid' : '' }}" type="text" name="slider_text" id="slider_text" value="{{ old('slider_text', $slider->slider_text) }}">
                @if($errors->has('slider_text'))
                    <span class="text-danger">{{ $errors->first('slider_text') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.slider.fields.slider_text_helper') }}</span>
            </div>
            

            <div class="form-group col-lg-4 col-md-4 col-sm-12">
				<label class="" for="slider_img">{{ trans('cruds.slider.fields.slider_img') }}</label>
				<input class="form-control {{ $errors->has('slider_img') ? 'is-invalid' : '' }}" type="file"
					name="slider_img" id="slider_img"
					value="{{ old('slider_img', $slider->slider_img) }}" />
				@if($errors->has('slider_img'))
				<span class="text-danger">{{ $errors->first('slider_img') }}</span>
				@endif
				<span class="help-block">{{ trans('cruds.slider.fields.slider_img_helper') }}</span>
			</div>

            <div class="form-group">
                <label for="slider_order">{{ trans('cruds.slider.fields.slider_order') }}</label>
                <input class="form-control {{ $errors->has('slider_order') ? 'is-invalid' : '' }}" type="number" name="slider_order" id="slider_order" value="{{ old('slider_order', $slider->slider_order) }}" step="1">
                @if($errors->has('slider_order'))
                    <span class="text-danger">{{ $errors->first('slider_order') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.slider.fields.slider_order_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required">{{ trans('cruds.slider.fields.slider_status') }}</label>
                <select class="form-control {{ $errors->has('slider_status') ? 'is-invalid' : '' }}" name="slider_status" id="slider_status" required>
                    <option value disabled {{ old('slider_status', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                    @foreach(App\Models\Slider::SLIDER_STATUS_SELECT as $key => $label)
                        <option value="{{ $key }}" {{ old('slider_status', $slider->slider_status) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @if($errors->has('slider_status'))
                    <span class="text-danger">{{ $errors->first('slider_status') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.slider.fields.slider_status_helper') }}</span>
            </div>
            <div class="form-group">
                <button class="btn btn-danger" type="submit">
                    {{ trans('global.save') }}
                </button>
            </div>
        </form>
    </div>
</div>



@endsection

@section('scripts')
<script>
    Dropzone.options.sliderImgDropzone = {
    url: '{{ route('admin.sliders.storeMedia') }}',
    maxFilesize: 20, // MB
    acceptedFiles: '.jpeg,.jpg,.png,.gif',
    maxFiles: 1,
    addRemoveLinks: true,
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
      size: 20,
      width: 1920,
      height: 800
    },
    success: function (file, response) {
      $('form').find('input[name="slider_img"]').remove()
      $('form').append('<input type="hidden" name="slider_img" value="' + response.name + '">')
    },
    removedfile: function (file) {
      file.previewElement.remove()
      if (file.status !== 'error') {
        $('form').find('input[name="slider_img"]').remove()
        this.options.maxFiles = this.options.maxFiles + 1
      }
    },
    init: function () {
@if(isset($slider) && $slider->slider_img)
      var file = {!! json_encode($slider->slider_img) !!}
          this.options.addedfile.call(this, file)
      this.options.thumbnail.call(this, file, file.preview ?? file.preview_url)
      file.previewElement.classList.add('dz-complete')
      $('form').append('<input type="hidden" name="slider_img" value="' + file.file_name + '">')
      this.options.maxFiles = this.options.maxFiles - 1
@endif
    },
    error: function (file, response) {
        if ($.type(response) === 'string') {
            var message = response //dropzone sends it's own error messages in string
        } else {
            var message = response.errors.file
        }
        file.previewElement.classList.add('dz-error')
        _ref = file.previewElement.querySelectorAll('[data-dz-errormessage]')
        _results = []
        for (_i = 0, _len = _ref.length; _i < _len; _i++) {
            node = _ref[_i]
            _results.push(node.textContent = message)
        }

        return _results
    }
}

</script>
@endsection