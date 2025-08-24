<x-layout>

    <x-page-heading>Izmjena podataka knjige</x-page-heading>

    <form method="POST" action="/books/{{ $book->id }}" enctype="multipart/form-data">
        @csrf
        {{ method_field ('PUT')}}

        <x-forms.input label="Authors first name" name="author_fname" value="{{ $book->author_fname }}"  />
        <x-forms.input label="Authors last name" name="author_lname" value="{{ $book->author_lname }}" />
        <x-forms.input label="Title" name="title"  value="{{ $book->title }}"/>
        <x-forms.input label="Publisher name" name="publisher_name" value="{{ $book->publisher_name }}" />
        <x-forms.input label="Publisher place" name="publisher_place" value="{{ $book->publisher_place }}" />
        <x-forms.input label="Year" name="year" value="{{ $book->year }}"/>
        <x-forms.select label="Loan" name="loan">
            <option>Na polici</option>
            <option>Na čitanju </option>
        </x-forms.select>

       <!-- <x-forms.checkbox label="Feature (Costs Extra)" name="featured" />  -->

        <x-forms.divider />

        <x-forms.input label="Tags (comma separated)" name="tags" value="{{ $book->tags->implode(', ') }}" />

        

        <a href="/books/{{ $book->id}}" class="text-sm text-gray-100 hover:text-gray-600" >Otkaži</a>
        <x-forms.button>Ažuriraj</x-forms.button>

        <x-forms.divider />
        <x-forms.divider />

        </form>

   


</x-layout>
