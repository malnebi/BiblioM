import './bootstrap';
import Alpine from 'alpinejs'; // Додајте ово

window.Alpine = Alpine; // Додајте ово
Alpine.start(); // Додајте ово

const bookCoverPreview = document.querySelector('#book_cover_preview');

if (bookCoverPreview) {
    const coverInput = document.querySelector('#book_cover_input');
    const authorFirstNameInput = document.querySelector('#author_fname');
    const authorLastNameInput = document.querySelector('#author_lname');
    const bookTitleInput = document.querySelector('#title');

    const updateBookCoverPlaceholder = () => {
        const placeholder = bookCoverPreview.querySelector('[data-book-cover-placeholder]');
        if (!placeholder) return;

        const initials = [authorFirstNameInput?.value, authorLastNameInput?.value]
            .map((name) => name.trim())
            .filter(Boolean)
            .map((name) => Array.from(name)[0].toLocaleUpperCase());

        placeholder.querySelector('[data-cover-initials]').textContent = initials.length
            ? `${initials.join('.')}.`
            : 'К.';
        placeholder.querySelector('[data-cover-title]').textContent = bookTitleInput?.value.trim() || 'Наслов књиге';
    };

    [authorFirstNameInput, authorLastNameInput, bookTitleInput].forEach((input) => {
        input?.addEventListener('input', updateBookCoverPlaceholder);
    });

    coverInput?.addEventListener('change', (event) => {
        const file = event.target.files[0];
        if (!file) return;

        const reader = new FileReader();
        reader.onload = () => {
            const image = document.createElement('img');
            image.src = reader.result;
            image.alt = 'Преглед корица књиге';
            image.className = 'h-full w-full object-cover';
            bookCoverPreview.replaceChildren(image);
            bookCoverPreview.classList.remove('border-dashed');
            bookCoverPreview.classList.add('border-solid');
        };
        reader.readAsDataURL(file);
    });
}

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

const scrollList = document.querySelector('[data-scroll-list]');

if (scrollList) {
    const cards = scrollList.querySelector('[data-scroll-cards]');
    const sentinel = scrollList.querySelector('[data-scroll-sentinel]');
    const status = scrollList.querySelector('[data-scroll-status]');
    let nextPageUrl = scrollList.dataset.nextPage;
    let loading = false;

    const loadNextPage = async () => {
        if (loading || !nextPageUrl || !sentinel || !status) return;

        loading = true;
        status.textContent = 'Учитавање картица...';

        try {
            const response = await fetch(nextPageUrl, {
                headers: {
                    Accept: 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
            });

            if (!response.ok) {
                throw new Error(`Card page request failed with status ${response.status}`);
            }

            const page = await response.json();

            if (typeof page.html !== 'string' || (page.next_page_url !== null && typeof page.next_page_url !== 'string')) {
                throw new Error('Scroll page response has an invalid format');
            }

            cards.insertAdjacentHTML('beforeend', page.html);
            nextPageUrl = page.next_page_url;

            if (nextPageUrl) {
                scrollList.dataset.nextPage = nextPageUrl;
                status.textContent = '';
            } else {
                sentinel?.remove();
                status.remove();
            }
        } catch (error) {
            console.error('Unable to load the next page of cards.', error);
            status.textContent = 'Сљедеће картице нису могле бити учитане. Освјежите страницу и покушајте поново.';
        } finally {
            loading = false;
        }
    };

    if (sentinel && status) {
        if ('IntersectionObserver' in window) {
            const observer = new IntersectionObserver((entries) => {
                if (entries.some((entry) => entry.isIntersecting)) loadNextPage();
            }, { rootMargin: '100px' });

            observer.observe(sentinel);
        } else {
            const checkSentinel = () => {
                if (sentinel.getBoundingClientRect().top < window.innerHeight + 100) loadNextPage();
            };

            window.addEventListener('scroll', checkSentinel, { passive: true });
            window.addEventListener('resize', checkSentinel);
            checkSentinel();
        }
    }
}

import.meta.glob([
    '../images/**',
    '../fonts/**',
    '../styles/**',
    '../scripts/**',
    '../views/**',
]);