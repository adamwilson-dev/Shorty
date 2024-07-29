<x-home-layout>
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
                </main>

                <script src="{{ asset('scripts/create-url.js') }}"></script>

                <footer class="py-16 text-center text-sm text-white dark:text-white/70">
                    <a href="https://github.com/adamwilson-dev/Shorty">Shorty v{{ config('app.version') }}</a>
                </footer>
            </div>
        </div>
    </div>
</x-home-layout>
