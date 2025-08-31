<x-layout>

    <x-page-heading>Измјена података о књизи</x-page-heading>

    <form method="POST" action="/books/{{ $book->id }}" enctype="multipart/form-data">
        @csrf
        {{ method_field ('PUT')}}

        <x-forms.input label="Име аутора" name="author_fname" value="{{ $book->author_fname }}"  />
        <x-forms.input label="Презиме аутора" name="author_lname" value="{{ $book->author_lname }}" />
        <x-forms.input label="Наслов" name="title"  value="{{ $book->title }}"/>
        <x-forms.input label="Издавач" name="publisher_name" value="{{ $book->publisher_name }}" />
        <x-forms.input label="Мјесто издавања" name="publisher_place" value="{{ $book->publisher_place }}" />
        <x-forms.input label="Година" name="year" value="{{ $book->year }}"/>
             

       <!-- <x-forms.checkbox label="Feature (Costs Extra)" name="featured" />  -->

        <x-forms.divider />

        <x-forms.input label="Предметне одреднице (одвоји запетом)" name="tags" value="{{ $book->tags->implode(', ') }}" />

        

        <a href="/books/{{ $book->id}}" class="text-sm text-gray-100 hover:text-gray-600" >Откажи</a>
        <x-forms.button>Ажурирај податке</x-forms.button>

        <x-forms.divider />
        <x-forms.divider />

        </form>

   


</x-layout>
