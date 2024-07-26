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
                                        type="submit"
                                        class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-400 disabled:opacity-50 disabled:cursor-not-allowed"
                                    >
                                        Create Short URL
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

                                    // Submit the form via ajax
                                    $.ajax({
                                        url: $(this).attr('action'),
                                        method: 'POST',
                                        data: $(this).serialize(),
                                        dataType: 'json',
                                        success: function(response) {
                                            // Re-enable the form
                                            setFormEnabled();

                                            // Assuming the response is a JSON object
                                            console.log(response);
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
                                $('#create-short-url-form').find('button').attr('disabled', true);
                            }

                            function setFormEnabled() {
                                $('#long-url').attr('readonly', false);
                                $('#create-short-url-form').find('button').attr('disabled', false);
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
                                    $('#expiry-date').val('');
                                    $('#selected-date').html('');
                                    $('#clear-date-icon').addClass('hidden');
                                });
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
