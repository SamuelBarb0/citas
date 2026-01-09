<select
    name="{{ $name }}"
    id="{{ $id }}"
    {{ $required ? 'required' : '' }}
    class="{{ $class }}"
    {{ $attributes }}
>
    <option value="">{{ $placeholder }}</option>

    @foreach($options as $grupo => $items)
        @if(count($options) > 1 && $grupo !== 'Sin grupo')
            <optgroup label="{{ $grupo }}">
        @endif

        @foreach($items as $option)
            <option
                value="{{ $option->valor }}"
                {{ old($name, $value) == $option->valor ? 'selected' : '' }}
            >
                {{ $option->etiqueta }}
            </option>
        @endforeach

        @if(count($options) > 1 && $grupo !== 'Sin grupo')
            </optgroup>
        @endif
    @endforeach
</select>

@if($useSelect2)
    @push('scripts')
    <script>
        $(document).ready(function() {
            $('#{{ $id }}').select2({
                placeholder: '{{ $placeholder }}',
                allowClear: true,
                language: {
                    noResults: function() {
                        return "No se encontraron resultados";
                    },
                    searching: function() {
                        return "Buscando...";
                    }
                }
            });
        });
    </script>
    @endpush
@endif