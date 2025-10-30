import './bootstrap';

// Import Alpine.js
import Alpine from 'alpinejs';
import intersect from '@alpinejs/intersect';

// Register Alpine plugins
Alpine.plugin(intersect);

// Trending Posts Alpine component
window.trendingPosts = function(posts, totalPages) {
    return {
        currentIndex: 0,
        posts: posts,
        totalPages: totalPages,
        
        previousPage() {
            if (this.currentIndex > 0) {
                this.currentIndex--;
            }
        },
        
        nextPage() {
            if (this.currentIndex < this.totalPages - 1) {
                this.currentIndex++;
            }
        },
        
        getPostsForPage(page) {
            const start = page * 3;
            return this.posts.slice(start, start + 3);
        },
        
        getPostUrl(post) {
            return `/${post.published_year}/${post.published_month}/${post.slug}`;
        },
        
        formatDate(dateString) {
            const date = new Date(dateString + 'T00:00:00');
            return date.toLocaleDateString('en-US', { 
                year: 'numeric', 
                month: 'long', 
                day: 'numeric' 
            });
        }
    }
};

// Start Alpine
window.Alpine = Alpine;
Alpine.start();
