@extends('layouts.admin')

@section('title', 'Create New Post')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900" style="font-family: 'Montserrat', sans-serif;">Create New Post</h2>
    </div>

    @if($errors->any())
    <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
        <p class="font-semibold mb-2">Có lỗi xảy ra:</p>
        <ul class="list-disc list-inside">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    @if(session('success'))
    <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
        {{ session('success') }}
    </div>
    @endif

    <form method="POST" action="{{ route('admin.posts.store') }}" enctype="multipart/form-data" id="post-form">
        @csrf
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content Column (2/3 width) -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Title -->
                <div class="bg-white shadow rounded-lg p-6">
                    <input type="text" name="title" id="title" value="{{ old('title') }}" required
                           placeholder="Enter post title here..."
                           class="w-full text-3xl font-bold border-0 focus:ring-0 focus:outline-none p-0"
                           style="font-family: 'Montserrat', sans-serif;">
                    @error('title')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Content Editor -->
                <div class="bg-white shadow rounded-lg p-6">
                    <label for="content" class="block text-sm font-medium text-gray-700 mb-2">Content *</label>
                    <textarea name="content" id="content" rows="20" required
                              class="w-full border-gray-300 rounded-md shadow-sm focus:ring-[#f8c2eb] focus:border-[#f8c2eb] sm:text-sm font-sans text-base leading-relaxed"
                              style="white-space: pre-wrap; word-wrap: break-word;"
                              placeholder="Start writing your post content here...">{{ old('content') }}</textarea>
                    @error('content')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Featured Image -->
                <div class="bg-white shadow rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Featured Image</h3>
                    <div class="space-y-4">
                        <div>
                            <label for="featured_image" class="block text-sm font-medium text-gray-700 mb-2">Image URL</label>
                            <input type="url" name="featured_image" id="featured_image" value="{{ old('featured_image') }}"
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:ring-[#f8c2eb] focus:border-[#f8c2eb] sm:text-sm"
                                   placeholder="https://example.com/image.jpg">
                            @error('featured_image')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="text-center text-gray-400">— OR —</div>
                        <div>
                            <label for="image_upload" class="block text-sm font-medium text-gray-700 mb-2">Upload Image</label>
                            <input type="file" name="image_upload" id="image_upload" accept="image/jpeg,image/png,image/jpg,image/gif,image/webp"
                                   class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-[#f8c2eb] file:text-black hover:file:bg-[#e8a8d8] border border-gray-300 rounded-md p-2">
                            <p class="mt-1 text-xs text-gray-500">Max 2MB (JPEG, PNG, JPG, GIF, WebP)</p>
                            <p id="file_info" class="mt-1 text-xs text-green-600 hidden"></p>
                            <div id="image_preview" class="mt-3 hidden">
                                <p class="text-sm text-gray-600 mb-2">Preview:</p>
                                <img id="preview_img" src="" alt="Preview" class="max-w-full h-64 object-cover rounded-lg border border-gray-300">
                            </div>
                            @error('image_upload')
                                <p class="mt-1 text-sm text-red-600 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Excerpt -->
                <div class="bg-white shadow rounded-lg p-6">
                    <label for="excerpt" class="block text-sm font-medium text-gray-700 mb-2">Excerpt</label>
                    <textarea name="excerpt" id="excerpt" rows="3"
                              class="w-full border-gray-300 rounded-md shadow-sm focus:ring-[#f8c2eb] focus:border-[#f8c2eb] sm:text-sm"
                              placeholder="Write a short excerpt...">{{ old('excerpt') }}</textarea>
                    <p class="mt-1 text-xs text-gray-500">A brief description of your post (optional)</p>
                    @error('excerpt')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Review Information (Collapsible - Only for Review type) -->
                <div class="bg-white shadow rounded-lg p-6" id="review_fields">
                    <details class="group">
                        <summary class="cursor-pointer text-lg font-medium text-gray-900 mb-4 list-none">
                            <span class="flex items-center justify-between">
                                <span>Review Information (Optional)</span>
                                <span class="transform transition-transform group-open:rotate-180">▼</span>
                            </span>
                        </summary>
                        <div class="mt-4 space-y-4 pt-4 border-t">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="product_name" class="block text-sm font-medium text-gray-700 mb-1">Product Name</label>
                                    <input type="text" name="product_name" id="product_name" value="{{ old('product_name') }}"
                                           class="w-full border-gray-300 rounded-md shadow-sm focus:ring-[#f8c2eb] focus:border-[#f8c2eb] sm:text-sm">
                                </div>
                                <div>
                                    <label for="brand" class="block text-sm font-medium text-gray-700 mb-1">Brand</label>
                                    <input type="text" name="brand" id="brand" value="{{ old('brand') }}"
                                           class="w-full border-gray-300 rounded-md shadow-sm focus:ring-[#f8c2eb] focus:border-[#f8c2eb] sm:text-sm">
                                </div>
                                <div>
                                    <label for="rating" class="block text-sm font-medium text-gray-700 mb-1">Rating (0-5)</label>
                                    <input type="number" name="rating" id="rating" value="{{ old('rating') }}" step="0.1" min="0" max="5"
                                           class="w-full border-gray-300 rounded-md shadow-sm focus:ring-[#f8c2eb] focus:border-[#f8c2eb] sm:text-sm"
                                           placeholder="4.5">
                                </div>
                                <div>
                                    <label for="price_text" class="block text-sm font-medium text-gray-700 mb-1">Price</label>
                                    <input type="text" name="price_text" id="price_text" value="{{ old('price_text') }}"
                                           class="w-full border-gray-300 rounded-md shadow-sm focus:ring-[#f8c2eb] focus:border-[#f8c2eb] sm:text-sm"
                                           placeholder="$29.99">
                                </div>
                            </div>
                            <div>
                                <label for="badges" class="block text-sm font-medium text-gray-700 mb-1">Badges (one per line)</label>
                                <textarea name="badges" id="badges" rows="3"
                                          class="w-full border-gray-300 rounded-md shadow-sm focus:ring-[#f8c2eb] focus:border-[#f8c2eb] sm:text-sm"
                                          placeholder="e.g., Best Overall&#10;Best Value">{{ is_array(old('badges')) ? implode("\n", old('badges')) : old('badges') }}</textarea>
                            </div>
                        </div>
                    </details>
                </div>
            </div>

            <!-- Sidebar Column (1/3 width) -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Publish Box -->
                <div class="bg-white shadow rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Publish</h3>
                    <div class="space-y-4">
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                            <select name="status" id="status" required
                                    class="w-full border-gray-300 rounded-md shadow-sm focus:ring-[#f8c2eb] focus:border-[#f8c2eb] sm:text-sm">
                                <option value="draft" {{ old('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="published" {{ old('status') === 'published' ? 'selected' : '' }}>Published</option>
                            </select>
                            @error('status')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="published_at" class="block text-sm font-medium text-gray-700 mb-2">Publish Date</label>
                            <input type="datetime-local" name="published_at" id="published_at"
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:ring-[#f8c2eb] focus:border-[#f8c2eb] sm:text-sm">
                            <p class="mt-1 text-xs text-gray-500">Leave empty to publish immediately</p>
                        </div>

                        <div>
                            <label for="author_id" class="block text-sm font-medium text-gray-700 mb-2">Author</label>
                            <select name="author_id" id="author_id" required
                                    class="w-full border-gray-300 rounded-md shadow-sm focus:ring-[#f8c2eb] focus:border-[#f8c2eb] sm:text-sm">
                                @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ old('author_id') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                                @endforeach
                            </select>
                            @error('author_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="pt-4 border-t">
                            <div class="flex justify-between items-center">
                                <a href="{{ route('admin.posts.index') }}" 
                                   class="text-gray-600 hover:text-gray-900 text-sm">
                                    Cancel
                                </a>
                                <button type="submit" 
                                        class="bg-[#f8c2eb] text-black px-6 py-2 rounded-md hover:bg-[#e8a8d8] transition-colors font-semibold"
                                        style="font-family: 'Montserrat', sans-serif;">
                                    Publish
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Post Type -->
                <div class="bg-white shadow rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Post Type</h3>
                    <select name="post_type" id="post_type" required
                            class="w-full border-gray-300 rounded-md shadow-sm focus:ring-[#f8c2eb] focus:border-[#f8c2eb] sm:text-sm">
                        <option value="review" {{ old('post_type') === 'review' ? 'selected' : '' }}>Review</option>
                        <option value="list" {{ old('post_type') === 'list' ? 'selected' : '' }}>Top List</option>
                        <option value="how-to" {{ old('post_type') === 'how-to' ? 'selected' : '' }}>How-to Guide</option>
                    </select>
                    @error('post_type')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Categories -->
                <div class="bg-white shadow rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Categories</h3>
                    <div class="space-y-2 max-h-48 overflow-y-auto">
                        @foreach($categories as $category)
                        <label class="flex items-center">
                            <input type="checkbox" name="categories[]" value="{{ $category->id }}"
                                   {{ in_array($category->id, old('categories', [])) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-[#f8c2eb] focus:ring-[#f8c2eb]">
                            <span class="ml-2 text-sm text-gray-700">{{ $category->name }}</span>
                        </label>
                        @endforeach
                    </div>
                    @error('categories')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tags -->
                <div class="bg-white shadow rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Tags</h3>
                    <div class="space-y-2 max-h-48 overflow-y-auto">
                        @foreach($tags as $tag)
                        <label class="flex items-center">
                            <input type="checkbox" name="tags[]" value="{{ $tag->id }}"
                                   {{ in_array($tag->id, old('tags', [])) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-[#f8c2eb] focus:ring-[#f8c2eb]">
                            <span class="ml-2 text-sm text-gray-700">{{ $tag->name }}</span>
                        </label>
                        @endforeach
                    </div>
                    @error('tags')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Features -->
                <div class="bg-white shadow rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Features</h3>
                    <div class="space-y-3">
                        <label class="flex items-center">
                            <input type="checkbox" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-[#f8c2eb] focus:ring-[#f8c2eb]">
                            <span class="ml-2 text-sm text-gray-700">Featured Post</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" name="is_trending" value="1" {{ old('is_trending') ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-[#f8c2eb] focus:ring-[#f8c2eb]">
                            <span class="ml-2 text-sm text-gray-700">Trending Now</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" name="is_editor_pick" value="1" {{ old('is_editor_pick') ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-[#f8c2eb] focus:ring-[#f8c2eb]">
                            <span class="ml-2 text-sm text-gray-700">Editor's Pick</span>
                        </label>
                    </div>
                </div>

                <!-- SEO (Collapsible) -->
                <div class="bg-white shadow rounded-lg p-6">
                    <details class="group">
                        <summary class="cursor-pointer text-lg font-medium text-gray-900 mb-4 list-none">
                            <span class="flex items-center justify-between">
                                <span>SEO Settings</span>
                                <span class="transform transition-transform group-open:rotate-180">▼</span>
                            </span>
                        </summary>
                        <div class="mt-4 space-y-4 pt-4 border-t">
                            <div>
                                <label for="meta_title" class="block text-sm font-medium text-gray-700 mb-1">Meta Title</label>
                                <input type="text" name="meta_title" id="meta_title" value="{{ old('meta_title') }}"
                                       class="w-full border-gray-300 rounded-md shadow-sm focus:ring-[#f8c2eb] focus:border-[#f8c2eb] sm:text-sm">
                            </div>
                            <div>
                                <label for="meta_description" class="block text-sm font-medium text-gray-700 mb-1">Meta Description</label>
                                <textarea name="meta_description" id="meta_description" rows="3"
                                          class="w-full border-gray-300 rounded-md shadow-sm focus:ring-[#f8c2eb] focus:border-[#f8c2eb] sm:text-sm">{{ old('meta_description') }}</textarea>
                            </div>
                            <div>
                                <label for="meta_keywords" class="block text-sm font-medium text-gray-700 mb-1">Keywords</label>
                                <input type="text" name="meta_keywords" id="meta_keywords" value="{{ is_array(old('meta_keywords')) ? implode(', ', old('meta_keywords')) : old('meta_keywords') }}"
                                       class="w-full border-gray-300 rounded-md shadow-sm focus:ring-[#f8c2eb] focus:border-[#f8c2eb] sm:text-sm"
                                       placeholder="keyword1, keyword2">
                            </div>
                        </div>
                    </details>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Image preview
    const imageUpload = document.getElementById('image_upload');
    const imagePreview = document.getElementById('image_preview');
    const previewImg = document.getElementById('preview_img');
    
    if (imageUpload) {
        imageUpload.addEventListener('change', function(e) {
            const file = e.target.files[0];
            const fileInfo = document.getElementById('file_info');
            
            if (file) {
                // Clear featured_image URL input when uploading file
                const featuredImageInput = document.getElementById('featured_image');
                if (featuredImageInput) {
                    featuredImageInput.value = '';
                }
                
                // Validate file size
                if (file.size > 2 * 1024 * 1024) {
                    alert('File size must be less than 2MB. Your file is ' + (file.size / 1024 / 1024).toFixed(2) + 'MB');
                    e.target.value = '';
                    imagePreview.classList.add('hidden');
                    if (fileInfo) fileInfo.classList.add('hidden');
                    return;
                }
                
                // Validate file type
                const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/webp'];
                if (!allowedTypes.includes(file.type)) {
                    alert('File type not allowed. Please upload JPEG, PNG, JPG, GIF, or WebP');
                    e.target.value = '';
                    imagePreview.classList.add('hidden');
                    if (fileInfo) fileInfo.classList.add('hidden');
                    return;
                }
                
                // Show file info
                if (fileInfo) {
                    fileInfo.textContent = 'Selected: ' + file.name + ' (' + (file.size / 1024).toFixed(2) + 'KB)';
                    fileInfo.classList.remove('hidden');
                }
                
                // Show preview
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    imagePreview.classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            } else {
                imagePreview.classList.add('hidden');
                if (fileInfo) fileInfo.classList.add('hidden');
            }
        });
    }

    // Auto-resize textarea và đảm bảo xuống dòng hoạt động
    const content = document.getElementById('content');
    if (content) {
        // Đảm bảo Enter key hoạt động bình thường để xuống dòng
        content.addEventListener('keydown', function(e) {
            // Cho phép Enter xuống dòng (không prevent default)
            if (e.key === 'Enter') {
                // Cho phép hành vi mặc định (xuống dòng)
                return true;
            }
        });
        
        // Auto-resize
        content.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
        });
        
        // Set initial height
        content.style.height = 'auto';
        content.style.height = (content.scrollHeight) + 'px';
    }
});
</script>
@endpush
@endsection
