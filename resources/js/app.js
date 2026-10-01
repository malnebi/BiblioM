import './bootstrap';
import Alpine from 'alpinejs'; // Додајте ово

window.Alpine = Alpine; // Додајте ово
Alpine.start(); // Додајте ово

const bookAutocomplete = document.querySelector('[data-book-autocomplete]');

if (bookAutocomplete) {
    const bookInput = bookAutocomplete.querySelector('#book_search');
    const bookId = bookAutocomplete.querySelector('#book_id');
    const suggestions = bookAutocomplete.querySelector('#book_suggestions');
    const noResults = bookAutocomplete.querySelector('#book_no_results');
    const allBookOptions = Array.from(suggestions.querySelectorAll('[data-book-option]'));
    let matchingOptions = [];
    let activeOptionIndex = -1;

    const closeSuggestions = () => {
        suggestions.hidden = true;
        noResults.hidden = true;
        bookInput.setAttribute('aria-expanded', 'false');
        bookInput.removeAttribute('aria-activedescendant');
        matchingOptions.forEach((option) => option.setAttribute('aria-selected', 'false'));
        activeOptionIndex = -1;
    };

    const updateSuggestions = () => {
        const search = bookInput.value.trim().toLocaleLowerCase();
        bookId.value = '';
        bookInput.setCustomValidity('');
        activeOptionIndex = -1;

        if (!search) {
            matchingOptions = [];
            closeSuggestions();
            return;
        }

        matchingOptions = allBookOptions.filter((option) =>
            option.textContent.trim().toLocaleLowerCase().includes(search)
        );

        allBookOptions.forEach((option) => {
            option.hidden = !matchingOptions.includes(option);
            option.setAttribute('aria-selected', 'false');
        });

        suggestions.hidden = matchingOptions.length === 0;
        noResults.hidden = matchingOptions.length > 0;
        bookInput.setAttribute('aria-expanded', 'true');
        bookInput.removeAttribute('aria-activedescendant');
    };

    const selectBook = (option) => {
        bookId.value = option.dataset.bookOption;
        bookInput.value = option.textContent.trim();
        bookInput.setCustomValidity('');
        matchingOptions = [];
        closeSuggestions();
    };

    const setActiveOption = (index) => {
        activeOptionIndex = (index + matchingOptions.length) % matchingOptions.length;
        matchingOptions.forEach((option, optionIndex) => {
            const isActive = optionIndex === activeOptionIndex;
            option.setAttribute('aria-selected', String(isActive));
            if (isActive) {
                bookInput.setAttribute('aria-activedescendant', option.id);
                option.scrollIntoView({ block: 'nearest' });
            }
        });
    };

    bookInput.addEventListener('input', updateSuggestions);
    bookInput.addEventListener('keydown', (event) => {
        if (event.key === 'ArrowDown' && matchingOptions.length > 0) {
            event.preventDefault();
            setActiveOption(activeOptionIndex + 1);
        } else if (event.key === 'ArrowUp' && matchingOptions.length > 0) {
            event.preventDefault();
            setActiveOption(activeOptionIndex < 0 ? matchingOptions.length - 1 : activeOptionIndex - 1);
        } else if (event.key === 'Enter' && activeOptionIndex >= 0) {
            event.preventDefault();
            selectBook(matchingOptions[activeOptionIndex]);
        } else if (event.key === 'Escape') {
            matchingOptions = [];
            closeSuggestions();
        }
    });

    suggestions.addEventListener('click', (event) => {
        const option = event.target.closest('[data-book-option]');
        if (option) selectBook(option);
    });

    bookInput.form.addEventListener('submit', (event) => {
        if (!bookId.value) {
            event.preventDefault();
            bookInput.setCustomValidity('Изаберите књигу из листе резултата.');
            bookInput.reportValidity();
        }
    });

    document.addEventListener('click', (event) => {
        if (!bookAutocomplete.contains(event.target)) {
            matchingOptions = [];
            closeSuggestions();
        }
    });
}

import.meta.glob([
    '../images/**',
    '../fonts/**',
    '../styles/**',
    '../scripts/**',
    '../views/**',
]);