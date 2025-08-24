<x-layout>

    <x-page-heading>LOAN A BOOK TO  {{ $users->name }}</x-page-heading>

    <x-forms.form method="POST" action="/loans" enctype="multipart/form-data">
    
        
       <input type="hidden" name="user_id" value="{{$users->id}}">

        <x-forms.select label="Book" name="book_id">
            @foreach($books as $book)
                <option value="{{ $book->id }}" selected> {{ $book->id }} {{ $book->title }} / {{ $book->author_fname }} {{ $book->author_lname}} </option>
            @endforeach
        </x-forms.select> 

        <x-forms.divider />

        <x-forms.button>Loan the book for 30 days</x-forms.button>

    </x-forms.form>

</x-layout>
