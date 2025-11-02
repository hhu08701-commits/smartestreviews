<?php

namespace App\Http\View\Composers;

use App\Models\Post;
use Illuminate\View\View;

class MissedPostsComposer
{
    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        // Get latest 6 posts for "You May Have Missed" section
        $missedPosts = Post::published()
            ->with(['author', 'categories'])
            ->latest('published_at')
            ->limit(6)
            ->get();
        
        $view->with('missedPosts', $missedPosts);
    }
}

