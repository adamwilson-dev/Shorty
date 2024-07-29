<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Shorty</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

        <script src="https://cdn.tailwindcss.com"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
        <link rel="stylesheet" href="{{ asset('styles/custom.css') }}">

        <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    </head>
    <body class="font-sans antialiased dark:bg-black dark:text-white/50">
        <div class="bg-gray-50 text-black/50 dark:bg-black dark:text-white/50">
            <img id="background" class="absolute left-0 top-0 w-full h-full object-cover" src="{{ asset('images/front/background.svg') }}" />
            <div class="relative min-h-screen flex flex-col items-center justify-center selection:bg-[#FF2D20] selection:text-white">
                <div class="relative w-full max-w-2xl px-6 lg:max-w-7xl">
                    <header class="grid grid-cols-2 items-center gap-2 py-10 lg:grid-cols-3">
                        <div class="flex lg:justify-center lg:col-start-2">
                            <img src="{{ asset('images/logo/white.svg') }}" />
                        </div>
                        @if (Route::has('login'))
                            <nav class="-mx-3 flex flex-1 justify-end">
                                @auth
                                    <a
                                        href="{{ url('/dashboard') }}"
                                        class="rounded-md px-3 py-2 text-white ring-1 ring-transparent transition hover:text-white/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
                                    >
                                        Dashboard
                                    </a>
                                @else
                                    <a
                                        href="{{ route('login') }}"
                                        class="rounded-md px-3 py-2 text-white ring-1 ring-transparent transition hover:text-white/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
                                    >
                                        Log in
                                    </a>

                                    @if (Route::has('register'))
                                        <a
                                            href="{{ route('register') }}"
                                            class="rounded-md px-3 py-2 text-white ring-1 ring-transparent transition hover:text-white/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
                                        >
                                            Register
                                        </a>
                                    @endif
                                @endauth
                            </nav>
                        @endif
                    </header>

                    <main class="mt-6">
                        <div id="short-url-result" class="container max-w-4xl mx-auto hidden opacity-0 transition-opacity duration-500 ease-in-out">
                            <div class="bg-white shadow-md rounded-lg p-6 mb-6">
                                <div class="flex justify-between items-start">
                                    <div class="flex-1">
                                        <div class="flex flex-col">
                                            <dt class="flex items-center gap-x-3 text-base font-semibold leading-7 text-gray-900">
                                                <svg class="h-5 w-5 flex-none text-indigo-600" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                    <path fill-rule="evenodd" d="M12.586 4.586a2 2 0 112.828 2.828l-3 3a2 2 0 01-2.828 0 1 1 0 00-1.414 1.414 4 4 0 005.656 0l3-3a4 4 0 00-5.656-5.656l-1.5 1.5a1 1 0 101.414 1.414l1.5-1.5zm-5 5a2 2 0 012.828 0 1 1 0 101.414-1.414 4 4 0 00-5.656 0l-3 3a4 4 0 105.656 5.656l1.5-1.5a1 1 0 10-1.414-1.414l-1.5 1.5a2 2 0 11-2.828-2.828l3-3z" clip-rule="evenodd" />
                                                </svg>
                                                <span id="short-url-success-title"></span>
                                            </dt>
                                            <dd class="mt-4 flex flex-auto flex-col text-base leading-7 text-gray-600">
                                                <p id="short-url-container" class="flex-auto"></p>
                                            </dd>
                                        </div>
                                    </div>
                                    <div>
                                        <svg class="checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52"> <circle class="checkmark__circle" cx="26" cy="26" r="25" fill="none"/> <path class="checkmark__check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8"/></svg>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <form id="create-short-url-form" action="{{ route("ajax.url.create") }}" method="POST" class="max-w-4xl mx-auto">
                            @csrf
                            <div class="relative">
                                <textarea
                                    id="long-url"
                                    name="long-url"
                                    class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 min-h-[100px] resize-none overflow-hidden"
                                    placeholder="Paste your long URL here..."
                                    rows="3"
                                    required="required"
                                    autofocus="autofocus"
                                ></textarea>
                                <div class="absolute bottom-3 right-2 flex items-center">
                                    <span id="selected-date" class="mr-1 text-sm text-gray-600"></span>
                                    <span id="clear-date-icon" class="clear-icon text-red-500 cursor-pointer mr-2 hidden">&times;</span>
                                    <a
                                        type="button"
                                        id="date-picker-button"
                                        class="mr-2 p-2 text-gray-700 rounded hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-400"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </a>
                                    <input id="expiry-date" name="expiry-date" type="hidden"/>
                                    <button
                                        id="create-short-url-button"
                                        type="submit"
                                        class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-400 disabled:opacity-50 disabled:cursor-not-allowed"
                                    >
                                        Create Short URL
                                    </button>
                                    <button
                                        id="create-short-url-processing-button"
                                        type="button"
                                        class="inline-flex items-center px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-400 disabled:opacity-50 disabled:cursor-not-allowed hidden"
                                        disabled
                                    >
                                        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        Creating...
                                    </button>
                                </div>
                            </div>
                        </form>

                        <script>
                            const textarea = $('#long-url');
                            const maxHeight = 700; // Maximum height in pixels
                            const internalYOffset = 38;

                            $(document).ready(function() {
                                // Setup form submission via ajax
                                setupFormHandler();

                                // Call autoResize on input event
                                textarea.on('input', autoResize);

                                // Initial call to set correct height
                                autoResize();

                                // Setup date picker
                                setupDatepicker();

                                // Setup clear date icon
                                setupClearDateIcon();
                            });

                            function setupFormHandler() {
                                $('#create-short-url-form').on('submit', function(e) {
                                    // Prevent the default form submission
                                    e.preventDefault();

                                    // Disable form elements
                                    setFormDisabled();

                                    // Clear any previous results
                                    resetPreviousResults();

                                    // Submit the form via ajax
                                    $.ajax({
                                        url: $(this).attr('action'),
                                        method: 'POST',
                                        data: $(this).serialize(),
                                        dataType: 'json',
                                        success: function(response) {
                                            // Re-enable the form
                                            setFormEnabled();

                                            // Show the result
                                            showShortUrlResult(response);
                                        },
                                        error: function(xhr, status, error) {
                                            // Re-enable the form
                                            setFormEnabled();

                                            console.error('AJAX Error:', status, error);
                                        }
                                    });
                                });
                            }

                            function setFormDisabled() {
                                $('#long-url').attr('readonly', true);
                                $('#create-short-url-button').addClass('hidden');
                                $('#create-short-url-processing-button').removeClass('hidden');
                            }

                            function setFormEnabled() {
                                $('#long-url').attr('readonly', false);
                                $('#create-short-url-processing-button').addClass('hidden');
                                $('#create-short-url-button').removeClass('hidden');
                            }

                            function autoResize() {
                                textarea.css('height', 'auto');
                                const newHeight = Math.min(textarea[0].scrollHeight + internalYOffset, maxHeight);
                                textarea.css('height', newHeight + 'px');

                                // Show/hide scrollbar based on content height
                                textarea.css('overflowY', textarea[0].scrollHeight + internalYOffset > maxHeight ? 'auto' : 'hidden');
                            }

                            function setupDatepicker() {
                                const datePickerButton = document.getElementById('date-picker-button');

                                flatpickr(datePickerButton, {
                                    enableTime: true,
                                    dateFormat: "Y-m-d H:i",
                                    minDate: "today",
                                    time_24hr: true,
                                    minuteIncrement: 1,
                                    defaultDate: new Date().setHours(23, 59, 0, 0),
                                    onChange: function(selectedDates, dateStr) {
                                        $('#expiry-date').val(dateStr);
                                        $('#selected-date').html('<strong>Expiry:</strong> ' + formatDateTime(dateStr));
                                        $('#clear-date-icon').removeClass('hidden');
                                    }
                                });
                            }

                            function formatDateTime(dateTimeStr) {
                                const dateTime = new Date(dateTimeStr);
                                const day = dateTime.getDate();
                                const month = dateTime.toLocaleString('default', { month: 'long' });
                                const year = dateTime.getFullYear();
                                const time = dateTime.toLocaleString('default', { hour: '2-digit', minute: '2-digit', hour12: false });

                                const suffix = getOrdinalSuffix(day);

                                return `${day}${suffix} ${month} ${year} at ${time}`;
                            }

                            function getOrdinalSuffix(day) {
                                if (day > 3 && day < 21) return 'th';
                                switch (day % 10) {
                                    case 1:  return "st";
                                    case 2:  return "nd";
                                    case 3:  return "rd";
                                    default: return "th";
                                }
                            }

                            function setupClearDateIcon() {
                                $('#clear-date-icon').on('click', function() {
                                    clearDateSelection();
                                });
                            }

                            function clearDateSelection() {
                                $('#expiry-date').val('');
                                $('#selected-date').html('');
                                $('#clear-date-icon').addClass('hidden');
                            }

                            function showShortUrlResult(result) {
                                $('#short-url-success-title').html(result.message);
                                $('#short-url-container').html(result.short_url);
                                $('#short-url-result').removeClass('hidden')
                                    .removeClass('-translate-y-4')
                                    .addClass('translate-y-0')
                                    .delay(50)  // Small delay to ensure the transition happens
                                    .queue(function(next) {
                                        $(this).removeClass('opacity-0').addClass('opacity-100');
                                        next();
                                    });

                                resetLongUrlForm();
                            }

                            function resetLongUrlForm() {
                                $('#long-url').val('');
                                clearDateSelection();
                            }

                            function resetPreviousResults() {
                                $('#short-url-result').addClass('hidden')
                                    .removeClass('-translate-y-0')
                                    .addClass('translate-y-4')
                                    .removeClass('opacity-100')
                                    .addClass('opacity-0');
                            }
                        </script>
                    </main>

                    <footer class="py-16 text-center text-sm text-white dark:text-white/70">
                        Shorty v{{ config('app.version') }}
                    </footer>
                </div>
            </div>
        </div>
    </body>
</html>
