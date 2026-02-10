<div class="mx-auto">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        @foreach ($movies as $movie)
            <div class="bg-white border border-gray-200 rounded-xl flex flex-col">
                <div class="p-4 border-b border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-900 line-clamp-2">
                        {{ $movie->title[app()->getLocale()] }}
                    </h3>

                    <p class="mt-1 text-sm text-gray-500">
                        {{ $movie->release_date }}
                    </p>
                </div>

                <div class="p-4 flex-1">
                    <div class="text-sm text-gray-600">
                        <span class="font-medium">Original language:</span>
                        {{ strtoupper($movie->original_language) }}
                    </div>
                </div>

                @if($movie->popularity)
                    <div class="p-4 border-t border-gray-100 flex justify-between text-sm">
                        <span class="text-gray-500">Popularity</span>
                        <span class="font-semibold text-gray-800">
                            {{ number_format($movie->popularity, 1) }}
                        </span>
                    </div>
                @endif
            </div>
        @endforeach
    </div>

    {{ $movies->links() }}
</div>
