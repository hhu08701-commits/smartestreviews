@extends('layouts.admin')

@section('title', 'Edit Post')

@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-900">Edit Post</h2>
    <p class="mt-1 text-sm text-gray-600">Update the post details below.</p>
</div>

<form method="POST" action="{{ route('admin.posts.update', $post) }}" enctype="multipart/form-data" class="space-y-6">
    @csrf
    @method('PUT')
    
    <!-- Basic Information -->
    <div class="bg-white shadow rounded-lg p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Basic Information</h3>
        
        <div class="space-y-6">
            <div>
                <label for="title" class="block text-sm font-medium text-gray-700">Title *</label>
                <input type="text" name="title" id="title" value="{{ old('title', $post->title) }}" required
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                @error('title')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="slug" class="block text-sm font-medium text-gray-700">Slug</label>
                <input type="text" name="slug" id="slug" value="{{ old('slug', $post->slug) }}"
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                <p class="mt-1 text-sm text-gray-500">Leave empty to auto-generate from title.</p>
                @error('slug')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="excerpt" class="block text-sm font-medium text-gray-700">Excerpt</label>
                <textarea name="excerpt" id="excerpt" rows="3"
                          class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">{{ old('excerpt', $post->excerpt) }}</textarea>
                @error('excerpt')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="content" class="block text-sm font-medium text-gray-700">Content *</label>
                <textarea name="content" id="content" rows="10" required
                          class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">{{ old('content', $post->content) }}</textarea>
                @error('content')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="featured_image" class="block text-sm font-medium text-gray-700">Featured Image URL</label>
                <input type="url" name="featured_image" id="featured_image" value="{{ old('featured_image', $post->featured_image) }}"
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                       placeholder="https://example.com/image.jpg">
                <p class="mt-1 text-sm text-gray-500">Enter the URL of the featured image for this post.</p>
                @error('featured_image')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="image_upload" class="block text-sm font-medium text-gray-700">Or Upload New Image File</label>
                <input type="file" name="image_upload" id="image_upload" accept="image/*"
                       class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                <p class="mt-1 text-sm text-gray-500">Upload an image file (JPEG, PNG, JPG, GIF, WebP) - Max 2MB</p>
                
                @if($post->image_path)
                    <p class="mt-1 text-sm text-green-600">Current image: {{ $post->image_original_name }}</p>
                    <img src="{{ asset('uploads/posts/' . $post->image_path) }}" alt="Current" class="mt-2 max-w-xs h-32 object-cover rounded border">
                @endif
                
                <!-- Image Preview -->
                <div id="image_preview" class="mt-3 hidden">
                    <p class="text-sm text-gray-600 mb-2">Preview new image:</p>
                    <img id="preview_img" src="" alt="Preview" class="max-w-xs h-48 object-cover rounded-lg border border-gray-300">
                </div>
                
                @error('image_upload')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="featured_image_alt" class="block text-sm font-medium text-gray-700">Image Alt Text</label>
                <input type="text" name="featured_image_alt" id="featured_image_alt" value="{{ old('featured_image_alt', $post->featured_image_alt) }}"
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                       placeholder="Description of the image for accessibility">
                @error('featured_image_alt')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="featured_image_caption" class="block text-sm font-medium text-gray-700">Image Caption</label>
                <input type="text" name="featured_image_caption" id="featured_image_caption" value="{{ old('featured_image_caption', $post->featured_image_caption) }}"
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                       placeholder="Caption text to display below the image">
                @error('featured_image_caption')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>

    <!-- Post Settings -->
    <div class="bg-white shadow rounded-lg p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Post Settings</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="post_type" class="block text-sm font-medium text-gray-700">Post Type *</label>
                <select name="post_type" id="post_type" required
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    <option value="">Select post type</option>
                    <option value="review" {{ old('post_type', $post->post_type) == 'review' ? 'selected' : '' }}>Review</option>
                    <option value="list" {{ old('post_type', $post->post_type) == 'list' ? 'selected' : '' }}>List</option>
                    <option value="how-to" {{ old('post_type', $post->post_type) == 'how-to' ? 'selected' : '' }}>How-to</option>
                </select>
                @error('post_type')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="status" class="block text-sm font-medium text-gray-700">Status *</label>
                <select name="status" id="status" required
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    <option value="">Select status</option>
                    <option value="draft" {{ old('status', $post->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="published" {{ old('status', $post->status) == 'published' ? 'selected' : '' }}>Published</option>
                    <option value="archived" {{ old('status', $post->status) == 'archived' ? 'selected' : '' }}>Archived</option>
                </select>
                @error('status')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="author_id" class="block text-sm font-medium text-gray-700">Author *</label>
                <select name="author_id" id="author_id" required
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    <option value="">Select author</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ old('author_id', $post->author_id) == $user->id ? 'selected' : '' }}>
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>
                @error('author_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

                <div>
                    <label for="published_at" class="block text-sm font-medium text-gray-700">Published At</label>
                    <input type="datetime-local" name="published_at" id="published_at" 
                           value="{{ old('published_at', $post->published_at ? $post->published_at->format('Y-m-d\TH:i') : '') }}"
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    <p class="mt-1 text-sm text-gray-500">Leave empty to publish immediately when status is "Published".</p>
                    <p class="mt-1 text-xs text-green-600 font-semibold">
                        <i class="fas fa-info-circle"></i> Khi đăng bài với status "Published", bài sẽ hiển thị ngay trên client!
                    </p>
                    @error('published_at')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2 p-4 bg-purple-50 border-2 border-purple-200 rounded-lg">
                    <div class="flex items-start">
                        <input type="checkbox" name="is_featured" id="is_featured" value="1" {{ old('is_featured', $post->is_featured ?? false) ? 'checked' : '' }}
                               class="mt-1 rounded border-gray-300 text-[#f8c2eb] shadow-sm focus:border-[#f8c2eb] focus:ring focus:ring-[#f8c2eb] focus:ring-opacity-50"
                               onchange="toggleFeaturedFields()">
                        <div class="ml-3 flex-1">
                            <label for="is_featured" class="text-sm font-semibold text-gray-900 cursor-pointer flex items-center">
                                <i class="fas fa-star text-purple-600 mr-2"></i>
                                Featured Posts
                            </label>
                            <p class="mt-1 text-sm text-gray-600">Đánh dấu bài viết này sẽ hiển thị trong section "FEATURED POSTS" trên trang chủ</p>
                            @if($post->status !== 'published' && ($post->is_featured ?? false))
                            <p class="mt-1 text-xs text-red-600 font-semibold">
                                <i class="fas fa-exclamation-triangle"></i> <strong>Warning:</strong> Post đang ở trạng thái "{{ ucfirst($post->status) }}". Chỉ hiển thị trên client khi Status = "Published"!
                            </p>
                            @else
                            <p class="mt-1 text-xs text-orange-600 font-semibold">
                                <i class="fas fa-exclamation-circle"></i> <strong>Lưu ý:</strong> Chỉ hiển thị trên client khi Status = "Published"!
                            </p>
                            @endif
                            <p class="mt-1 text-xs text-gray-500">
                                <i class="fas fa-info-circle"></i> Featured Posts khác với Editor's Picks - bạn có thể đánh dấu cả 2 hoặc chỉ 1 trong 2
                            </p>
                        </div>
                    </div>
                    
                    <div id="featured_order_field" class="mt-4 {{ old('is_featured', $post->is_featured ?? false) ? '' : 'hidden' }}">
                        <label for="featured_order" class="block text-sm font-medium text-gray-700">Featured Order (Thứ tự hiển thị)</label>
                        <input type="number" name="featured_order" id="featured_order" value="{{ old('featured_order', $post->featured_order ?? 0) }}" min="0"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-[#f8c2eb] focus:border-[#f8c2eb] sm:text-sm">
                        <p class="mt-1 text-sm text-gray-500">
                            <i class="fas fa-info-circle"></i> Số nhỏ hơn = hiển thị trước. Ví dụ: 0, 1, 2, 3... (Mặc định: 0)
                        </p>
                        @if($post->status === 'published')
                        <p class="mt-2 text-xs text-green-600 font-semibold">
                            <i class="fas fa-check-circle"></i> Bài viết sẽ <strong>hiển thị ngay</strong> trong section "FEATURED POSTS" trên trang chủ sau khi lưu!
                        </p>
                        @endif
                        @error('featured_order')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label class="flex items-center">
                        <input type="checkbox" name="is_trending" value="1" {{ old('is_trending', $post->is_trending ?? false) ? 'checked' : '' }}
                               class="rounded border-gray-300 text-[#f8c2eb] shadow-sm focus:border-[#f8c2eb] focus:ring focus:ring-[#f8c2eb] focus:ring-opacity-50">
                        <span class="ml-2 text-sm text-gray-700">Trending Now</span>
                    </label>
                    <p class="mt-1 text-sm text-gray-500">Check this to display this post in "Trending Now" sidebar</p>
                </div>

                <div>
                    <label for="trending_order" class="block text-sm font-medium text-gray-700">Trending Order</label>
                    <input type="number" name="trending_order" id="trending_order" value="{{ old('trending_order', $post->trending_order ?? 0) }}" min="0"
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-[#f8c2eb] focus:border-[#f8c2eb] sm:text-sm">
                    <p class="mt-1 text-sm text-gray-500">Lower numbers appear first in Trending Now. Default: 0</p>
                    @error('trending_order')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2 p-4 bg-yellow-50 border-2 border-yellow-200 rounded-lg">
                    <div class="flex items-start">
                        <input type="checkbox" name="is_editor_pick" id="is_editor_pick" value="1" {{ old('is_editor_pick', $post->is_editor_pick ?? false) ? 'checked' : '' }}
                               class="mt-1 rounded border-gray-300 text-[#f8c2eb] shadow-sm focus:border-[#f8c2eb] focus:ring focus:ring-[#f8c2eb] focus:ring-opacity-50"
                               onchange="toggleEditorPickFields()">
                        <div class="ml-3 flex-1">
                            <label for="is_editor_pick" class="text-sm font-semibold text-gray-900 cursor-pointer flex items-center">
                                <i class="fas fa-star text-yellow-600 mr-2"></i>
                                Editor's Pick
                            </label>
                            <p class="mt-1 text-sm text-gray-600">Đánh dấu bài viết này sẽ hiển thị trong section "EDITOR'S PICKS" trên trang chủ</p>
                            @if($post->status !== 'published' && ($post->is_editor_pick ?? false))
                            <p class="mt-1 text-xs text-red-600 font-semibold">
                                <i class="fas fa-exclamation-triangle"></i> <strong>Warning:</strong> Post đang ở trạng thái "{{ ucfirst($post->status) }}". Chỉ hiển thị trên client khi Status = "Published"!
                            </p>
                            @else
                            <p class="mt-1 text-xs text-orange-600 font-semibold">
                                <i class="fas fa-exclamation-circle"></i> <strong>Lưu ý:</strong> Chỉ hiển thị trên client khi Status = "Published"!
                            </p>
                            @endif
                        </div>
                    </div>
                    
                    <div id="editor_pick_order_field" class="mt-4 {{ old('is_editor_pick', $post->is_editor_pick ?? false) ? '' : 'hidden' }}">
                        <label for="editor_pick_order" class="block text-sm font-medium text-gray-700">Editor's Pick Order (Thứ tự hiển thị)</label>
                        <input type="number" name="editor_pick_order" id="editor_pick_order" value="{{ old('editor_pick_order', $post->editor_pick_order ?? 0) }}" min="0"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-[#f8c2eb] focus:border-[#f8c2eb] sm:text-sm">
                        <p class="mt-1 text-sm text-gray-500">
                            <i class="fas fa-info-circle"></i> Số nhỏ hơn = hiển thị trước. Ví dụ: 0, 1, 2, 3... (Mặc định: 0)
                        </p>
                        @if($post->status === 'published')
                        <p class="mt-2 text-xs text-green-600 font-semibold">
                            <i class="fas fa-check-circle"></i> Bài viết sẽ <strong>hiển thị ngay</strong> trong section "EDITOR'S PICKS" trên trang chủ sau khi lưu!
                        </p>
                        @endif
                        @error('editor_pick_order')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

    <!-- Affiliate Links Section -->
    <div class="bg-white shadow rounded-lg p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4" style="font-family: 'Montserrat', sans-serif;">Affiliate Links</h3>
        
        @if($post->affiliateLinks->count() > 0)
            <div class="mb-4">
                <p class="text-sm font-medium text-gray-700 mb-2">Current Affiliate Links ({{ $post->affiliateLinks->count() }}):</p>
                <div class="space-y-2">
                    @foreach($post->affiliateLinks as $link)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded border border-gray-200">
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-900">{{ $link->label }}</p>
                                <p class="text-xs text-gray-500">{{ $link->merchant }} - {{ $link->url }}</p>
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium mt-1 {{ $link->enabled ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $link->enabled ? 'Enabled' : 'Disabled' }}
                                </span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('admin.affiliate-links.edit', $link) }}" 
                                   class="text-[#f8c2eb] hover:text-[#e8a8d8] text-sm font-semibold" target="_blank">
                                    Edit
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @else
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-4">
                <p class="text-sm text-yellow-800">
                    <strong>No affiliate links yet.</strong> 
                </p>
            </div>
        @endif
        
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
            <p class="text-sm text-blue-800 mb-2">
                <strong>Add Affiliate Links:</strong>
            </p>
            <ul class="text-sm text-blue-800 list-disc list-inside space-y-1">
                <li><a href="{{ route('admin.affiliate-links.create') }}?post_id={{ $post->id }}" target="_blank" class="text-[#f8c2eb] hover:underline font-semibold">Create New Link</a> and select this post</li>
                <li>Or <a href="{{ route('admin.affiliate-links.index') }}" target="_blank" class="text-[#f8c2eb] hover:underline font-semibold">Edit existing links</a> to associate them with this post</li>
            </ul>
        </div>
    </div>

    <!-- Review Specific Information (for post_type = review) -->
    <div class="bg-white shadow rounded-lg p-6" id="review_fields">
        <h3 class="text-lg font-medium text-gray-900 mb-4" style="font-family: 'Montserrat', sans-serif;">Review Information</h3>
        <p class="text-sm text-gray-500 mb-4">Fill these fields if this is a product review.</p>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="product_name" class="block text-sm font-medium text-gray-700">Product Name</label>
                <input type="text" name="product_name" id="product_name" value="{{ old('product_name', $post->product_name) }}"
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-[#f8c2eb] focus:border-[#f8c2eb] sm:text-sm">
                @error('product_name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="brand" class="block text-sm font-medium text-gray-700">Brand</label>
                <input type="text" name="brand" id="brand" value="{{ old('brand', $post->brand) }}"
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-[#f8c2eb] focus:border-[#f8c2eb] sm:text-sm">
                @error('brand')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="rating" class="block text-sm font-medium text-gray-700">Rating (0-5)</label>
                <input type="number" name="rating" id="rating" value="{{ old('rating', $post->rating) }}" step="0.1" min="0" max="5"
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-[#f8c2eb] focus:border-[#f8c2eb] sm:text-sm"
                       placeholder="4.5">
                <p class="mt-1 text-sm text-gray-500">Enter rating from 0.0 to 5.0 (e.g., 4.5)</p>
                @error('rating')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="price_text" class="block text-sm font-medium text-gray-700">Price Text</label>
                <input type="text" name="price_text" id="price_text" value="{{ old('price_text', $post->price_text) }}"
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-[#f8c2eb] focus:border-[#f8c2eb] sm:text-sm"
                       placeholder="e.g., $29.99">
                @error('price_text')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="pros" class="block text-sm font-medium text-gray-700">Pros (one per line)</label>
                <textarea name="pros" id="pros" rows="6"
                          class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-[#f8c2eb] focus:border-[#f8c2eb] sm:text-sm"
                          placeholder="Enter each pro on a new line">{{ is_array(old('pros', $post->pros)) ? implode("\n", old('pros', $post->pros)) : (old('pros') ? old('pros') : ($post->pros ? implode("\n", $post->pros) : '')) }}</textarea>
                <p class="mt-1 text-sm text-gray-500">Enter each advantage on a separate line</p>
                @error('pros')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="cons" class="block text-sm font-medium text-gray-700">Cons (one per line)</label>
                <textarea name="cons" id="cons" rows="6"
                          class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-[#f8c2eb] focus:border-[#f8c2eb] sm:text-sm"
                          placeholder="Enter each con on a new line">{{ is_array(old('cons', $post->cons)) ? implode("\n", old('cons', $post->cons)) : (old('cons') ? old('cons') : ($post->cons ? implode("\n", $post->cons) : '')) }}</textarea>
                <p class="mt-1 text-sm text-gray-500">Enter each disadvantage on a separate line</p>
                @error('cons')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="mt-6">
            <label for="badges" class="block text-sm font-medium text-gray-700">Badges (one per line)</label>
            <textarea name="badges" id="badges" rows="3"
                      class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-[#f8c2eb] focus:border-[#f8c2eb] sm:text-sm"
                      placeholder="e.g., Best Overall&#10;Best Value&#10;Editor's Choice">{{ is_array(old('badges', $post->badges)) ? implode("\n", old('badges', $post->badges)) : (old('badges') ? old('badges') : ($post->badges ? implode("\n", $post->badges) : '')) }}</textarea>
            <p class="mt-1 text-sm text-gray-500">Enter each badge on a separate line (e.g., "Best Overall", "Best Value")</p>
            @error('badges')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <!-- SEO Settings -->
    <div class="bg-white shadow rounded-lg p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4" style="font-family: 'Montserrat', sans-serif;">SEO Settings</h3>
        
        <div class="space-y-6">
            <div>
                <label for="meta_title" class="block text-sm font-medium text-gray-700">Meta Title</label>
                <input type="text" name="meta_title" id="meta_title" value="{{ old('meta_title', $post->meta_title) }}"
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-[#f8c2eb] focus:border-[#f8c2eb] sm:text-sm"
                       placeholder="SEO title for search engines">
                <p class="mt-1 text-sm text-gray-500">Leave empty to use post title</p>
                @error('meta_title')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="meta_description" class="block text-sm font-medium text-gray-700">Meta Description</label>
                <textarea name="meta_description" id="meta_description" rows="3"
                          class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-[#f8c2eb] focus:border-[#f8c2eb] sm:text-sm"
                          placeholder="SEO description for search engines">{{ old('meta_description', $post->meta_description) }}</textarea>
                <p class="mt-1 text-sm text-gray-500">Leave empty to use excerpt</p>
                @error('meta_description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="meta_keywords" class="block text-sm font-medium text-gray-700">Meta Keywords</label>
                <input type="text" name="meta_keywords" id="meta_keywords" value="{{ is_array(old('meta_keywords', $post->meta_keywords)) ? implode(', ', old('meta_keywords', $post->meta_keywords)) : (old('meta_keywords', $post->meta_keywords)) }}"
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-[#f8c2eb] focus:border-[#f8c2eb] sm:text-sm"
                       placeholder="keyword1, keyword2, keyword3">
                <p class="mt-1 text-sm text-gray-500">Comma-separated keywords for SEO</p>
                @error('meta_keywords')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>

    <!-- Categories and Tags -->
    <div class="bg-white shadow rounded-lg p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Categories and Tags</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Categories</label>
                <div class="space-y-2">
                    @foreach($categories as $category)
                        <label class="flex items-center">
                            <input type="checkbox" name="categories[]" value="{{ $category->id }}"
                                   {{ in_array($category->id, old('categories', $post->categories->pluck('id')->toArray())) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <span class="ml-2 text-sm text-gray-700">{{ $category->name }}</span>
                        </label>
                    @endforeach
                </div>
                @error('categories')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tags</label>
                <div class="space-y-2">
                    @foreach($tags as $tag)
                        <label class="flex items-center">
                            <input type="checkbox" name="tags[]" value="{{ $tag->id }}"
                                   {{ in_array($tag->id, old('tags', $post->tags->pluck('id')->toArray())) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <span class="ml-2 text-sm text-gray-700">{{ $tag->name }}</span>
                        </label>
                    @endforeach
                </div>
                @error('tags')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>

    <!-- Actions -->
    <div class="flex justify-end space-x-3">
        <a href="{{ route('admin.posts.index') }}" 
           class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400 transition-colors">
            Cancel
        </a>
        <button type="submit" 
                class="bg-[#f8c2eb] text-black px-4 py-2 rounded-md hover:bg-[#e8a8d8] transition-colors font-semibold"
                style="font-family: 'Montserrat', sans-serif;">
            Update Post
        </button>
    </div>
</form>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const imageUpload = document.getElementById('image_upload');
    const imagePreview = document.getElementById('image_preview');
    const previewImg = document.getElementById('preview_img');
    const featuredImageInput = document.getElementById('featured_image');
    
    if (imageUpload) {
        imageUpload.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                // Validate file size (2MB)
                if (file.size > 2 * 1024 * 1024) {
                    alert('File size must be less than 2MB');
                    e.target.value = '';
                    return;
                }
                
                // Show preview
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    imagePreview.classList.remove('hidden');
                };
                reader.readAsDataURL(file);
                
                // Clear featured_image URL input when uploading file
                if (featuredImageInput) {
                    featuredImageInput.value = '';
                }
            } else {
                imagePreview.classList.add('hidden');
            }
        });
    }

    // Toggle Editor's Pick Order field
    function toggleEditorPickFields() {
        const checkbox = document.getElementById('is_editor_pick');
        const orderField = document.getElementById('editor_pick_order_field');
        if (checkbox && orderField) {
            if (checkbox.checked) {
                orderField.classList.remove('hidden');
            } else {
                orderField.classList.add('hidden');
            }
        }
    }
    
    // Initialize on page load
    if (document.getElementById('is_editor_pick')) {
        toggleEditorPickFields();
    }
    
    // Toggle Featured Order field
    function toggleFeaturedFields() {
        const checkbox = document.getElementById('is_featured');
        const orderField = document.getElementById('featured_order_field');
        if (checkbox && orderField) {
            if (checkbox.checked) {
                orderField.classList.remove('hidden');
            } else {
                orderField.classList.add('hidden');
            }
        }
    }
    
    // Initialize on page load
    if (document.getElementById('is_featured')) {
        toggleFeaturedFields();
    }
});
</script>
@endpush
@endsection
