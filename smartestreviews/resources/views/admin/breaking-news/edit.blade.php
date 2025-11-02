@extends('layouts.admin')

@section('title', 'Edit Breaking News')

@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-900" style="font-family: 'Montserrat', sans-serif;">Edit Breaking News</h2>
    <p class="mt-1 text-sm text-gray-600">Chỉnh sửa tin tức trong phần Breaking News marquee</p>
</div>

<form method="POST" action="{{ route('admin.breaking-news.update', $breakingNews) }}" enctype="multipart/form-data" class="space-y-6">
    @csrf
    @method('PUT')

    <div class="bg-white shadow-md rounded-lg p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4" style="font-family: 'Montserrat', sans-serif;">Breaking News Information</h3>
        
        <div class="space-y-4">
            <div>
                <label for="title" class="block text-sm font-medium text-gray-700">Title *</label>
                <input type="text" name="title" id="title" value="{{ old('title', $breakingNews->title) }}" required
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#f8c2eb] focus:ring-[#f8c2eb]"
                       placeholder="Enter breaking news title">
                @error('title')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                <div class="flex items-center mb-3">
                    <i class="fas fa-link text-blue-600 mr-2"></i>
                    <label class="block text-sm font-semibold text-blue-900">Link Options</label>
                </div>
                <p class="text-xs text-blue-700 mb-4">Chọn một trong các tùy chọn dưới đây (ưu tiên: Affiliate Link > Post > Custom URL)</p>
                
                <div class="space-y-4">
                    <div>
                        <label for="affiliate_link_id" class="block text-sm font-medium text-gray-700 mb-1">
                            <i class="fas fa-tag text-orange-500 mr-1"></i>Affiliate Link <span class="text-orange-600 font-semibold">(Ưu tiên cao nhất)</span>
                        </label>
                        <select name="affiliate_link_id" id="affiliate_link_id"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#f8c2eb] focus:ring-[#f8c2eb]">
                            <option value="">-- Chọn Affiliate Link --</option>
                            @foreach($affiliateLinks as $affLink)
                                <option value="{{ $affLink->id }}" {{ old('affiliate_link_id', $breakingNews->affiliate_link_id) == $affLink->id ? 'selected' : '' }}>
                                    {{ $affLink->label }} @if($affLink->merchant)({{ $affLink->merchant }})@endif
                                </option>
                            @endforeach
                        </select>
                        <p class="mt-1 text-xs text-gray-500">Sử dụng cloaked URL và tracking từ affiliate link</p>
                        @error('affiliate_link_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="border-t border-blue-200 pt-4">
                        <label for="post_id" class="block text-sm font-medium text-gray-700 mb-1">
                            <i class="fas fa-file-alt text-purple-500 mr-1"></i>HOẶC Link to Post
                        </label>
                        <select name="post_id" id="post_id"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#f8c2eb] focus:ring-[#f8c2eb]">
                            <option value="">-- Chọn Post --</option>
                            @foreach($posts as $post)
                                <option value="{{ $post->id }}" {{ old('post_id', $breakingNews->post_id) == $post->id ? 'selected' : '' }}>
                                    {{ $post->title }} ({{ $post->published_at->format('M d, Y') }})
                                </option>
                            @endforeach
                        </select>
                        <p class="mt-1 text-xs text-gray-500">Link sẽ tự động trỏ đến bài viết</p>
                        @error('post_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="border-t border-blue-200 pt-4">
                        <label for="link" class="block text-sm font-medium text-gray-700 mb-1">
                            <i class="fas fa-globe text-green-500 mr-1"></i>HOẶC Custom Link URL
                        </label>
                        <input type="text" name="link" id="link" value="{{ old('link', $breakingNews->link) }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#f8c2eb] focus:ring-[#f8c2eb]"
                               placeholder="https://example.com/article">
                        <p class="mt-1 text-xs text-gray-500">Nhập URL tùy chỉnh</p>
                        @error('link')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
            
            <script>
            document.addEventListener('DOMContentLoaded', function() {
                const affiliateLink = document.getElementById('affiliate_link_id');
                const postId = document.getElementById('post_id');
                const link = document.getElementById('link');
                
                function clearOthers(exclude) {
                    if (exclude !== 'affiliate' && affiliateLink.value) {
                        postId.value = '';
                        link.value = '';
                    }
                    if (exclude !== 'post' && postId.value) {
                        affiliateLink.value = '';
                        link.value = '';
                    }
                    if (exclude !== 'link' && link.value) {
                        affiliateLink.value = '';
                        postId.value = '';
                    }
                }
                
                affiliateLink.addEventListener('change', function() {
                    if (this.value) {
                        postId.value = '';
                        link.value = '';
                    }
                });
                
                postId.addEventListener('change', function() {
                    if (this.value) {
                        affiliateLink.value = '';
                        link.value = '';
                    }
                });
                
                link.addEventListener('input', function() {
                    if (this.value) {
                        affiliateLink.value = '';
                        postId.value = '';
                    }
                });
            });
            </script>

            @if($breakingNews->image_url)
                <div>
                    <label class="block text-sm font-medium text-gray-700">Current Image</label>
                    @php
                        $imageUrl = $breakingNews->image_url;
                        if (!filter_var($imageUrl, FILTER_VALIDATE_URL) && !str_starts_with($imageUrl, '/')) {
                            $imageUrl = asset($breakingNews->image_url);
                        } elseif (!filter_var($imageUrl, FILTER_VALIDATE_URL)) {
                            $imageUrl = asset($breakingNews->image_url);
                        }
                    @endphp
                    <img src="{{ $imageUrl }}" 
                         alt="{{ $breakingNews->title }}"
                         class="mt-2 w-32 h-32 object-cover rounded"
                         onerror="this.onerror=null; this.style.display='none'; this.nextElementSibling.style.display='flex';">
                    <div style="width: 128px; height: 128px; background: #f0f0f0; display: none; align-items: center; justify-content: center; border-radius: 4px; margin-top: 8px;">
                        <i class="fas fa-image text-gray-400 text-2xl"></i>
                    </div>
                </div>
            @endif

            <div>
                <label for="image_upload" class="block text-sm font-medium text-gray-700">Upload New Image</label>
                <input type="file" name="image_upload" id="image_upload" accept="image/jpeg,image/png,image/jpg,image/gif,image/webp"
                       class="mt-1 block w-full text-sm text-gray-500 
                       file:mr-4 file:py-2 file:px-4 
                       file:rounded-md file:border-0 
                       file:text-sm file:font-semibold 
                       file:bg-[#f8c2eb] file:text-black 
                       hover:file:bg-[#e8a8d8]
                       file:cursor-pointer
                       cursor-pointer
                       border border-gray-300 rounded-md p-2"
                       onchange="document.getElementById('file-info').textContent = this.files[0] ? this.files[0].name + ' (' + (this.files[0].size / 1024 / 1024).toFixed(2) + ' MB)' : 'Chưa chọn file'">
                <p class="mt-1 text-xs text-gray-500">
                    Cho phép: JPEG, PNG, JPG, GIF, WEBP (tối đa <strong>2MB</strong>). Nếu upload ảnh mới, ảnh cũ sẽ bị thay thế.
                </p>
                <p class="mt-1 text-xs text-orange-600">
                    <i class="fas fa-exclamation-triangle mr-1"></i><strong>Lưu ý:</strong> Server PHP chỉ cho phép upload tối đa 2MB. Nếu file lớn hơn sẽ không thể upload.
                </p>
                <p id="file-info" class="mt-1 text-xs text-blue-600 font-medium">
                    Chưa chọn file
                </p>
                @error('image_upload')
                    <p class="mt-1 text-sm text-red-600 font-medium">
                        <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                    </p>
                @enderror
                @if(session('upload_error'))
                    <p class="mt-1 text-sm text-red-600 font-medium">
                        <i class="fas fa-exclamation-circle mr-1"></i>{{ session('upload_error') }}
                    </p>
                @endif
                @if($errors->any())
                    <div class="mt-2 p-3 bg-red-50 border border-red-200 rounded">
                        <p class="text-sm font-semibold text-red-800 mb-1">Có lỗi xảy ra:</p>
                        <ul class="text-xs text-red-700 list-disc list-inside">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>


            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="sort_order" class="block text-sm font-medium text-gray-700">Sort Order</label>
                    <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', $breakingNews->sort_order) }}" min="0"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#f8c2eb] focus:ring-[#f8c2eb]">
                    <p class="mt-1 text-xs text-gray-500">Thứ tự hiển thị (số nhỏ hơn sẽ hiển thị trước)</p>
                    @error('sort_order')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center pt-6">
                    <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $breakingNews->is_active) ? 'checked' : '' }}
                           class="h-4 w-4 text-[#f8c2eb] focus:ring-[#f8c2eb] border-gray-300 rounded">
                    <label for="is_active" class="ml-2 block text-sm text-gray-900">
                        Active (hiển thị trên website)
                    </label>
                </div>
            </div>
        </div>
    </div>

    <div class="flex justify-end space-x-3">
        <a href="{{ route('admin.breaking-news.index') }}" 
           class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
            Cancel
        </a>
        <button type="submit" 
                class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-black bg-[#f8c2eb] hover:bg-[#e8a8d8] font-semibold">
            Update Breaking News
        </button>
    </div>
</form>
@endsection

