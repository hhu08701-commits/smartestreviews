@extends('layouts.admin')

@section('title', 'Create Product Showcase')

@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-900">Create Product Showcase</h2>
    <p class="mt-1 text-sm text-gray-600">Add a new product showcase with affiliate link.</p>
</div>

<form method="POST" action="{{ route('admin.product-showcases.store') }}" enctype="multipart/form-data" class="space-y-6">
    @csrf
    
    <!-- Basic Information -->
    <div class="bg-white shadow rounded-lg p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Product Information</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <div class="flex items-center justify-between mb-1">
                    <label for="post_id" class="block text-sm font-medium text-gray-700">Post *</label>
                    <div id="view-products-link" class="hidden">
                        <a href="#" id="view-products-btn" target="_blank" 
                           class="text-xs text-blue-600 hover:text-blue-800 font-medium">
                            📦 Xem sản phẩm của post này
                        </a>
                    </div>
                </div>
                <select name="post_id" id="post_id" required
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    <option value="">Select a post</option>
                    @foreach($posts as $post)
                        <option value="{{ $post->id }}" {{ old('post_id', $selectedPostId ?? '') == $post->id ? 'selected' : '' }}>
                            {{ $post->title }}
                        </option>
                    @endforeach
                </select>
                @error('post_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-xs text-gray-500">Chọn post để thêm sản phẩm vào. Sau khi tạo xong, bạn có thể thêm nhiều sản phẩm khác vào cùng post này.</p>
            </div>

            <div>
                <label for="product_name" class="block text-sm font-medium text-gray-700">Product Name *</label>
                <input type="text" name="product_name" id="product_name" value="{{ old('product_name') }}" required
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                @error('product_name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="brand" class="block text-sm font-medium text-gray-700">Brand</label>
                <input type="text" name="brand" id="brand" value="{{ old('brand') }}"
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                @error('brand')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="image_url" class="block text-sm font-medium text-gray-700">Product Image URL</label>
                <input type="url" name="image_url" id="image_url" value="{{ old('image_url') }}"
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                       placeholder="https://example.com/product-image.jpg">
                @error('image_url')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="image_upload" class="block text-sm font-medium text-gray-700">Or Upload Image File</label>
                <input type="file" name="image_upload" id="image_upload" accept="image/*"
                       class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                <p class="mt-1 text-sm text-gray-500">Upload an image file (JPEG, PNG, JPG, GIF, WebP) - Max 2MB</p>
                @error('image_upload')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="price_text" class="block text-sm font-medium text-gray-700">Price Text</label>
                <input type="text" name="price_text" id="price_text" value="{{ old('price_text') }}"
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                       placeholder="$29.99">
                @error('price_text')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="rating" class="block text-sm font-medium text-gray-700">Rating</label>
                <input type="number" name="rating" id="rating" value="{{ old('rating') }}" min="0" max="5" step="0.1"
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                       placeholder="4.5">
                @error('rating')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="mt-6">
            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
            <textarea name="description" id="description" rows="3"
                      class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">{{ old('description') }}</textarea>
            @error('description')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <!-- Affiliate Information -->
    <div class="bg-white shadow rounded-lg p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Affiliate Information</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="affiliate_url" class="block text-sm font-medium text-gray-700">Affiliate URL *</label>
                <input type="url" name="affiliate_url" id="affiliate_url" value="{{ old('affiliate_url') }}" required
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                       placeholder="https://amazon.com/product?ref=yourcode">
                <p class="mt-1 text-sm text-gray-500">The affiliate link where users will be redirected</p>
                @error('affiliate_url')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="affiliate_label" class="block text-sm font-medium text-gray-700">Button Text</label>
                <input type="text" name="affiliate_label" id="affiliate_label" value="{{ old('affiliate_label') }}"
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                       placeholder="Buy on Amazon">
                <p class="mt-1 text-sm text-gray-500">Text to display on the affiliate button</p>
                @error('affiliate_label')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="merchant" class="block text-sm font-medium text-gray-700">Merchant</label>
                <input type="text" name="merchant" id="merchant" value="{{ old('merchant') }}"
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                       placeholder="Amazon, eBay, etc.">
                @error('merchant')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="sort_order" class="block text-sm font-medium text-gray-700">Sort Order</label>
                <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', 0) }}" min="0"
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                <p class="mt-1 text-sm text-gray-500">Lower numbers appear first</p>
                @error('sort_order')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>

    <!-- Features and Reviews -->
    <div class="bg-white shadow rounded-lg p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Features and Reviews</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="features" class="block text-sm font-medium text-gray-700">Features (one per line)</label>
                <textarea name="features" id="features" rows="4"
                          class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                          placeholder="Wireless connectivity&#10;Long battery life&#10;Water resistant">{{ old('features') }}</textarea>
                <p class="mt-1 text-sm text-gray-500">Enter each feature on a new line</p>
                @error('features')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="pros" class="block text-sm font-medium text-gray-700">Pros (one per line)</label>
                <textarea name="pros" id="pros" rows="4"
                          class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                          placeholder="Great value for money&#10;Easy to use&#10;Good quality">{{ old('pros') }}</textarea>
                <p class="mt-1 text-sm text-gray-500">Enter each pro on a new line</p>
                @error('pros')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="cons" class="block text-sm font-medium text-gray-700">Cons (one per line)</label>
                <textarea name="cons" id="cons" rows="4"
                          class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                          placeholder="Limited color options&#10;Expensive&#10;Heavy">{{ old('cons') }}</textarea>
                <p class="mt-1 text-sm text-gray-500">Enter each con on a new line</p>
                @error('cons')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="review_count" class="block text-sm font-medium text-gray-700">Review Count</label>
                <input type="number" name="review_count" id="review_count" value="{{ old('review_count', 0) }}" min="0"
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                @error('review_count')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>

    <!-- Settings -->
    <div class="bg-white shadow rounded-lg p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Settings</h3>
        
        <div class="space-y-4">
            <div class="flex items-center">
                <input type="checkbox" name="is_featured" id="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}
                       class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                <label for="is_featured" class="ml-2 block text-sm text-gray-900">
                    Featured Product
                </label>
            </div>

            <div class="flex items-center">
                <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                       class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                <label for="is_active" class="ml-2 block text-sm text-gray-900">
                    Active
                </label>
            </div>
        </div>
    </div>

    <!-- Actions -->
    <div class="flex justify-end space-x-3">
        <a href="{{ route('admin.product-showcases.index') }}" 
           class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400 transition-colors">
            Cancel
        </a>
        <button type="submit" 
                class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition-colors">
            Create Product Showcase
        </button>
    </div>
</form>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const postSelect = document.getElementById('post_id');
    const viewProductsLink = document.getElementById('view-products-link');
    const viewProductsBtn = document.getElementById('view-products-btn');
    
    // Update link when post is selected
    function updateViewLink() {
        const selectedPostId = postSelect.value;
        if (selectedPostId) {
            viewProductsLink.classList.remove('hidden');
            viewProductsBtn.href = '{{ route("admin.product-showcases.index") }}?post_id=' + selectedPostId;
        } else {
            viewProductsLink.classList.add('hidden');
        }
    }
    
    // Check if there's a default selected post from URL
    const urlParams = new URLSearchParams(window.location.search);
    const postIdFromUrl = urlParams.get('post_id');
    if (postIdFromUrl && postSelect.value) {
        updateViewLink();
    }
    
    postSelect.addEventListener('change', updateViewLink);
});
</script>
@endpush
@endsection
