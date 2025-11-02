@extends('layouts.admin')

@section('title', 'Create Breaking News')

@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-900" style="font-family: 'Montserrat', sans-serif;">Create Breaking News</h2>
    <p class="mt-1 text-sm text-gray-600">Thêm tin tức mới vào phần Breaking News marquee</p>
</div>

<form method="POST" action="{{ route('admin.breaking-news.store') }}" enctype="multipart/form-data" class="space-y-6">
    @csrf

    <div class="bg-white shadow-md rounded-lg p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4" style="font-family: 'Montserrat', sans-serif;">Breaking News Information</h3>
        
        <div class="space-y-4">
            <div>
                <label for="title" class="block text-sm font-medium text-gray-700">Title *</label>
                <input type="text" name="title" id="title" value="{{ old('title') }}" required
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#f8c2eb] focus:ring-[#f8c2eb]"
                       placeholder="Enter breaking news title">
                @error('title')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="post_id" class="block text-sm font-medium text-gray-700">Link to Post (Optional)</label>
                <select name="post_id" id="post_id"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#f8c2eb] focus:ring-[#f8c2eb]">
                    <option value="">-- Select a Post --</option>
                    @foreach($posts as $post)
                        <option value="{{ $post->id }}" {{ old('post_id') == $post->id ? 'selected' : '' }}>
                            {{ $post->title }} ({{ $post->published_at->format('M d, Y') }})
                        </option>
                    @endforeach
                </select>
                <p class="mt-1 text-xs text-gray-500">Nếu chọn post, link sẽ tự động trỏ đến bài viết đó</p>
                @error('post_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="link" class="block text-sm font-medium text-gray-700">OR Custom Link URL (Optional)</label>
                <input type="text" name="link" id="link" value="{{ old('link') }}"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#f8c2eb] focus:ring-[#f8c2eb]"
                       placeholder="https://example.com/article">
                <p class="mt-1 text-xs text-gray-500">Chỉ dùng nếu không chọn Post ở trên</p>
                @error('link')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="image_upload" class="block text-sm font-medium text-gray-700">Upload Image</label>
                <input type="file" name="image_upload" id="image_upload" accept="image/jpeg,image/png,image/jpg,image/gif,image/webp"
                       class="mt-1 block w-full text-sm text-gray-500 
                       file:mr-4 file:py-2 file:px-4 
                       file:rounded-md file:border-0 
                       file:text-sm file:font-semibold 
                       file:bg-[#f8c2eb] file:text-black 
                       hover:file:bg-[#e8a8d8]
                       file:cursor-pointer
                       cursor-pointer
                       border border-gray-300 rounded-md p-2">
                <p class="mt-1 text-xs text-gray-500">
                    Cho phép: JPEG, PNG, JPG, GIF, WEBP (tối đa 5MB)
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
            </div>

            <div>
                <label for="image_url" class="block text-sm font-medium text-gray-700">OR Image URL</label>
                <input type="url" name="image_url" id="image_url" value="{{ old('image_url') }}"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#f8c2eb] focus:ring-[#f8c2eb]"
                       placeholder="https://example.com/image.jpg">
                <p class="mt-1 text-xs text-gray-500">Hình ảnh sẽ hiển thị trong marquee. Nếu không có, sẽ lấy từ post (nếu có)</p>
                @error('image_url')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="sort_order" class="block text-sm font-medium text-gray-700">Sort Order</label>
                    <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', 0) }}" min="0"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#f8c2eb] focus:ring-[#f8c2eb]">
                    <p class="mt-1 text-xs text-gray-500">Thứ tự hiển thị (số nhỏ hơn sẽ hiển thị trước)</p>
                    @error('sort_order')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center pt-6">
                    <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
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
            Create Breaking News
        </button>
    </div>
</form>
@endsection

