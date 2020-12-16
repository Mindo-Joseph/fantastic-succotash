<script src="https://ajax.googleapis.com/ajax/libs/webfont/1.6.26/webfont.js"></script>

@php
    $activatedFont = app('Webkul\Webfont\Repositories\WebfontRepository');

    $font = $activatedFont->findOneWhere([
        'activated' => 1
    ]);

    if (isset($font)) {
        $activeFont = $font->font;

        $font = explode(',', $activeFont)[0];

        $family = explode(',', $activeFont)[1];
    }
@endphp

@if ($font)
    <script>
        WebFont.load({
            google: {
                families: ['{{ $font }}']
            }
        });

        window.addEventListener('load', (event) => {
            console.log(
                $('.mce-tinymce iframe')
                    .contents()
                    .find("body")
                    .attr("style", "font-family: '{{ $font }}', {{ $family }} !important")
            );
        });
    </script>

    <style>
        * {
            font-family: "{{ $font }}", {{ $family }};
        }

        *::-webkit-input-placeholder {
            font-family: $font-montserrat;
        }

        *::-webkit-input-placeholder {
            font-family: $font-montserrat;
        }

        input,
        select,
        textarea,
        .mce-content-body * {
            font-family: "{{ $font }}", {{ $family }} !important;
        }

        body {
            font-family: "{{ $font }}", {{ $family }};
        }
    </style>
@endif
