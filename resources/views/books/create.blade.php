<x-layout>

    <x-page-heading>ADD A BOOK</x-page-heading>

    <x-forms.form method="POST" action="/books" enctype="multipart/form-data">

        <x-forms.input label="Authors first name" name="author_fname" placeholder="Authors first name" />
        <x-forms.input label="Authors last name" name="author_lname" placeholder="Authors last name" />
        <x-forms.input label="Title" name="title" placeholder="Title of the book" />
        <x-forms.input label="Publisher name" name="publisher_name" placeholder="Publisher name" />
        <x-forms.input label="Publisher place" name="publisher_place" placeholder="Publisher place" />
        <x-forms.input label="Year" name="year" placeholder="year" />
        <x-forms.select label="Loan" name="loan">
            <option>Free for loan</option>
            <option>Book is out </option>
        </x-forms.select>

        <x-forms.checkbox label="Feature (Costs Extra)" name="featured" />

        <x-forms.divider />

        <x-forms.input label="Tags (comma separated)" name="tags"
            placeholder="scientific, education, history, novel, poetry, fantasy, dystopia, romance" />

        <x-forms.button>Publish</x-forms.button>

        <x-forms.divider />
        <x-forms.divider />

    </x-forms.form>

</x-layout>
