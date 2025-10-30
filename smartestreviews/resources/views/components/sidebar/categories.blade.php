@props(['categories' => []])

@if($categories->count() > 0)
    <div class="bg-white rounded-lg border border-gray-200 p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Categories</h3>
        <div class="space-y-2">
            @foreach($categories as $category)
                <a 
                    href="{{ route('categories.show', $category) }}" 
                    class="flex items-center justify-between p-2 rounded-lg hover:bg-gray-50 transition-colors group"
                >
                    <div class="flex items-center space-x-3">
                        <div 
                            class="w-3 h-3 rounded-full bg-blue-500"
                        ></div>
                        <span class="text-sm text-gray-700 group-hover:text-gray-900">
                            {{ $category->name }}
                        </span>
                    </div>
                    <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded-full">
                        {{ $category->posts_count }}
                    </span>
                </a>
            @endforeach
        </div>
    </div>
@endif
