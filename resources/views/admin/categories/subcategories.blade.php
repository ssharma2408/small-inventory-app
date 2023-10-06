
 @foreach($entry->childCategories as $child=>$value)
            <option value="{{ $value->id }}" {{ old('category_id') == $value->id ? 'selected' : '' }} {{ $selected == $value->id ? 'selected' : '' }}   > 
              @for ($i = 0; $i < $level; $i++)
                 &nbsp;-
              @endfor
             {{ $value->name }}</option>
            @if(count($value->childCategories) > 0)
              @php $level++; @endphp
              @include('admin.categories.subcategories', ['entry' => $value,'selected'=>$selected]);
            @endif
@endforeach