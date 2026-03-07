<x-layout>

    <x-page-heading>ДОДАЈ КЊИГУ</x-page-heading>

    <x-forms.form method="POST" action="/books" enctype="multipart/form-data">

        <x-forms.input label="Име аутора" name="author_fname" placeholder="Име аутора" />
        <x-forms.input label="Презиме аутора" name="author_lname" placeholder="Презиме аутора" />
        <x-forms.input label="Наслов" name="title" placeholder="Наслов" />
        <x-forms.input label="Издавач" name="publisher_name" placeholder="Издавач" />
        <x-forms.input label="Мјесто издавања" name="publisher_place" placeholder="Мјесто издавања" />
        <x-forms.input label="година" name="year" placeholder="година" />
        <x-forms.select label="Позајмица" name="loan">
            <option>На полици</option>
            <option>На читању </option>
        </x-forms.select>

        <x-forms.checkbox label="Истакнуто (додатно)" name="featured" />

        <x-forms.divider />

        <x-forms.input label="Описне ознаке (раздвоји запетом)" name="tags"
            placeholder="популарна наука, образовање, историја, књижевност, белетристика, роман, поезија, драма, фантазија, дистопија" />

        <x-forms.button>Сачувај књигу</x-forms.button>

        <x-forms.select label="Описнa ознакa " name="tags">
            @foreach ($tags as $tag)
                <option> <x-tag :tag="$tag" size="small" /> </option>
            @endforeach
        </x-forms.select>
        <x-forms.select label="Описне ознаке " name="tags">
            @foreach ($tags as $tag)
                <option> <x-tag :tag="$tag" size="small" /> </option>
            @endforeach
        </x-forms.select>
        <x-forms.select label="Описне ознаке " name="tags">
            @foreach ($tags as $tag)
                <option> <x-tag :tag="$tag" size="small" /> </option>
            @endforeach
        </x-forms.select>



        <x-forms.divider />
        <x-forms.divider />

    </x-forms.form>

</x-layout>
